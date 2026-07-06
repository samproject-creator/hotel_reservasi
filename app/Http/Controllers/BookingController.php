<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
        $this->authorizeResource(Booking::class, 'booking');
    }

    public function index(Request $request)
    {
        $query = Booking::with(['tamu', 'kamar.tipeKamar', 'user']);

        // Filter 1: Pencarian Berdasarkan Kode / Nama Tamu (Eksisting)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_booking', 'like', '%' . $search . '%')
                  ->orWhereHas('tamu', fn($t) => $t->where('nama_lengkap', 'like', '%' . $search . '%'));
            });
        }

        // Filter 2: Berdasarkan Status Pilihan (Eksisting)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter 3: Rentang Tanggal Menginap Mulai (Baru - Sangat Cocok untuk Reservasi)
        if ($request->filled('start_date')) {
            $query->where('tanggal_checkin', '>=', $request->start_date);
        }

        // Filter 4: Rentang Tanggal Menginap Sampai (Baru)
        if ($request->filled('end_date')) {
            $query->where('tanggal_checkin', '<=', $request->end_date);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        return view('booking.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $tamus  = Tamu::orderBy('nama_lengkap')->get();

        $query = Kamar::with('tipeKamar');

        if ($request->filled(['checkin', 'checkout'])) {
            $checkin = $request->checkin;
            $checkout = $request->checkout;

            $occupiedKamarIds = Booking::where(function ($q) use ($checkin, $checkout) {
                $q->where('tanggal_checkin', '<', $checkout)
                  ->where('tanggal_checkout', '>', $checkin);
            })
            ->whereNotIn('status', ['cancelled', 'checkout'])
            ->when($request->exclude_booking_id, function ($q) use ($request) {
                $q->where('id', '!=', $request->exclude_booking_id);
            })
            ->with('kamar')
            ->get()
            ->pluck('kamar.*.id')
            ->flatten()
            ->unique();

            $query->whereNotIn('id', $occupiedKamarIds)
                  ->where('status', '!=', 'maintenance');
        } else {
            $query->where('status', 'tersedia');
        }

        $kamar = $query->get();

        if ($request->ajax()) {
            return response()->json($kamar);
        }

        return view('booking.create', compact('tamus', 'kamar'));
    }

    public function store(StoreBookingRequest $request)
    {
        if (!$this->bookingService->checkAvailability($request->kamar_ids, $request->tanggal_checkin, $request->tanggal_checkout)) {
            return back()->with('error', 'Satu atau lebih kamar yang dipilih tidak tersedia untuk tanggal tersebut.');
        }

        $this->bookingService->createBooking($request->validated());

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['tamu', 'kamar.tipeKamar', 'user', 'checkin.user', 'checkout.user']);
        return view('booking.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking tidak dapat diedit dalam status ini.');
        }

        $tamus  = Tamu::orderBy('nama_lengkap')->get();
        $kamar = Kamar::with('tipeKamar')->where('status', 'tersedia')->get();
        $booking->load('kamar.tipeKamar');
        return view('booking.edit', compact('booking', 'tamus', 'kamar'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // If dates or rooms change, check availability
        if ($request->filled('tanggal_checkin') || $request->filled('tanggal_checkout') || $request->filled('kamar_ids')) {
            $checkin = $request->input('tanggal_checkin', $booking->tanggal_checkin);
            $checkout = $request->input('tanggal_checkout', $booking->tanggal_checkout);
            $kamarIds = $request->input('kamar_ids', $booking->kamar->pluck('id')->toArray());

            if (!$this->bookingService->checkAvailability($kamarIds, $checkin, $checkout, $booking->id)) {
                return back()->with('error', 'Satu atau lebih kamar tidak tersedia untuk jadwal baru ini.');
            }
        }

        $this->bookingService->updateBooking($booking, $request->validated());

        return redirect()->route('booking.show', $booking)->with('success', 'Booking berhasil diperbarui.');
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        \Illuminate\Support\Facades\DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);
            $booking->kamar()->update(['status' => 'tersedia']);
        });

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->status !== 'cancelled') {
            return back()->with('error', 'Hanya booking yang sudah dibatalkan yang dapat dihapus.');
        }

        $booking->kamar()->detach();
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }

    public function toggleStatus(Booking $booking)
    {
        if (in_array($booking->status, ['checkin', 'checkout', 'cancelled'])) {
            return back()->with('error', 'Status transaksi yang sudah diproses tidak dapat diubah kembali.');
        }

        $newStatus = ($booking->status === 'pending') ? 'confirmed' : 'pending';
        $booking->update(['status' => $newStatus]);

        return redirect()->route('booking.index')->with('success', 'Status booking berhasil diperbarui.');
    }
}
