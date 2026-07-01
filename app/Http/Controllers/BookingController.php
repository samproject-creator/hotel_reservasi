<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['tamu', 'kamars.tipeKamar', 'user']);

        if ($request->filled('search')) {
            $query->where('kode_booking', 'like', '%' . $request->search . '%')
                  ->orWhereHas('tamu', fn($q) => $q->where('nama_lengkap', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $tamus  = Tamu::orderBy('nama_lengkap')->get();
        $kamars = Kamar::with('tipeKamar')->where('status', 'tersedia')->get();
        return view('booking.create', compact('tamus', 'kamars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tamu_id'          => 'required|exists:tamu,id',
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_tamu'      => 'required|integer|min:1',
            'kamar_ids'        => 'required|array|min:1',
            'kamar_ids.*'      => 'exists:kamar,id',
            'uang_muka'        => 'nullable|numeric|min:0',
            'catatan'          => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $checkin  = \Carbon\Carbon::parse($validated['tanggal_checkin']);
            $checkout = \Carbon\Carbon::parse($validated['tanggal_checkout']);
            $malam    = $checkin->diffInDays($checkout);

            // Hitung total harga dari kamar yang dipilih
            $totalHarga = 0;
            $kamarData  = [];

            foreach ($validated['kamar_ids'] as $kamarId) {
                $kamar       = Kamar::with('tipeKamar')->findOrFail($kamarId);
                $harga       = $kamar->tipeKamar->harga_per_malam;
                $subtotal    = $harga * $malam;
                $totalHarga += $subtotal;

                $kamarData[$kamarId] = [
                    'harga_malam'  => $harga,
                    'jumlah_malam' => $malam,
                    'subtotal'     => $subtotal,
                ];
            }

            $booking = Booking::create([
                'kode_booking'     => Booking::generateKode(),
                'tamu_id'          => $validated['tamu_id'],
                'user_id'          => auth()->id(),
                'tanggal_checkin'  => $validated['tanggal_checkin'],
                'tanggal_checkout' => $validated['tanggal_checkout'],
                'jumlah_tamu'      => $validated['jumlah_tamu'],
                'status'           => 'confirmed',
                'total_harga'      => $totalHarga,
                'uang_muka'        => $validated['uang_muka'] ?? 0,
                'catatan'          => $validated['catatan'],
            ]);

            // Attach kamar (Many-to-Many)
            $booking->kamars()->attach($kamarData);
        });

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['tamu', 'kamars.tipeKamar', 'user', 'checkin.user', 'checkout.user']);
        return view('booking.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking tidak dapat diedit dalam status ini.');
        }

        $tamus  = Tamu::orderBy('nama_lengkap')->get();
        $kamars = Kamar::with('tipeKamar')->where('status', 'tersedia')->get();
        $booking->load('kamars.tipeKamar');
        return view('booking.edit', compact('booking', 'tamus', 'kamars'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'tanggal_checkin'  => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_tamu'      => 'required|integer|min:1',
            'uang_muka'        => 'nullable|numeric|min:0',
            'catatan'          => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('booking.show', $booking)->with('success', 'Booking berhasil diperbarui.');
    }

    public function cancel(Booking $booking)
    {
        if (in_array($booking->status, ['checkin', 'checkout'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);
            // Kembalikan status kamar ke tersedia
            $booking->kamars()->update(['status' => 'tersedia']);
        });

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->status !== 'cancelled') {
            return back()->with('error', 'Hanya booking yang sudah dibatalkan yang dapat dihapus.');
        }

        $booking->kamars()->detach();
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }
    public function toggleStatus(Booking $booking)
    {
        if (in_array($booking->status, ['checkin', 'checkout', 'cancelled'])) {
            return back()->with('error', 'Status transaksi yang sudah diproses tidak dapat diubah kembali.');
        }

        // Tukar nilai status secara bergantian
        $newStatus = ($booking->status === 'pending') ? 'confirmed' : 'pending';
        $booking->update(['status' => $newStatus]);

        return redirect()->route('booking.index')->with('success', 'Status manifes booking berhasil diperbarui menjadi: ' . ucfirst($newStatus));
    }
}
