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
        $query = Booking::with(['tamu', 'kamars.tipeKamar', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_booking', 'like', '%' . $search . '%')
                  ->orWhereHas('tamu', fn($t) => $t->where('nama_lengkap', 'like', '%' . $search . '%'));
            });
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

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());
        return redirect()->route('booking.show', $booking)->with('success', 'Booking berhasil diperbarui.');
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        \Illuminate\Support\Facades\DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);
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

        $newStatus = ($booking->status === 'pending') ? 'confirmed' : 'pending';
        $booking->update(['status' => $newStatus]);

        return redirect()->route('booking.index')->with('success', 'Status booking berhasil diperbarui.');
    }
}
