<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckinController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['tamu', 'kamar'])
            ->where('status', 'checkin'); 

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_booking', 'like', '%' . $request->search . '%')
                  ->orWhereHas('tamu', fn($subQ) => $subQ->where('nama_lengkap', 'like', '%' . $request->search . '%'));
            });
        }

        $checkins = $query->latest()->paginate(10)->withQueryString();

        return view('checkin.index', compact('checkins'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['tamu', 'kamar.tipeKamar', 'user', 'checkin']);
        return view('checkin.show', compact('booking'));
    }
    public function proses(Booking $booking)
        {
            if ($booking->status !== 'confirmed') {
                return back()->with('error', 'Proses check-in hanya bisa dilakukan pada data booking yang berstatus Confirmed.');
            }

            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'checkin']);

                $booking->checkin()->create([
                    'waktu_checkin' => Carbon::now(),
                    'user_id'       => auth()->id(),
                ]);

                $booking->kamar()->update(['status' => 'ditempati']);
            });

            return redirect()->route('booking.index')->with('success', 'Pintu portal bilik terbuka! Tamu berhasil masuk ke dalam kamar.');
        }
}