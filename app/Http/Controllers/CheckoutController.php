<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\KuitansiCheckoutMail;
use App\Services\FonnteService;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['tamu', 'kamar'])->where('status', 'checkout');

        if ($request->filled('search')) {
            $query->where('kode_booking', 'like', '%' . $request->search . '%')
                  ->orWhereHas('tamu', fn($q) => $q->where('nama_lengkap', 'like', '%' . $request->search . '%'));
        }

        $checkouts = $query->latest()->paginate(10)->withQueryString();
        return view('checkout.index', compact('checkouts'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['tamu', 'kamar.tipeKamar', 'checkin', 'checkout.user']);
        $sisaTagihan = $booking->total_harga - $booking->uang_muka;
        return view('checkout.show', compact('booking', 'sisaTagihan'));
    }
    public function proses(Request $request, Booking $booking, FonnteService $fonnte)
    {
        if ($booking->status !== 'checkin') {
            return back()->with('error', 'Proses check-out hanya bisa dilakukan pada tamu yang berstatus Sedang Menginap (Check-In).');
        }

        $request->validate([
            'total_bayar'       => 'required|numeric|min:' . ($booking->total_harga - ($booking->uang_muka ?? 0)),
            'metode_pembayaran' => 'required|string',
        ]);

        DB::transaction(function () use ($booking, $request) {
            $booking->update(['status' => 'checkout']);

            $totalTagihan = $booking->total_harga;
            $uangDiterima = $request->total_bayar;
            $sisaTagihan  = $totalTagihan - ($booking->uang_muka ?? 0);
            $kembalian    = $uangDiterima - $sisaTagihan;

            $booking->checkout()->create([
                'user_id'           => auth()->id(),
                'waktu_checkout'    => Carbon::now(),
                'total_tagihan'     => $totalTagihan,
                'total_bayar'       => $uangDiterima,
                'biaya_tambahan'    => 0,
                'metode_pembayaran' => $request->metode_pembayaran,
                'kembalian'         => $kembalian,
            ]);

            $booking->kamar()->update(['status' => 'tersedia']);
        });

        $booking->load('tamu');

        if ($booking->tamu && $booking->tamu->email) {
            Mail::to($booking->tamu->email)->send(new KuitansiCheckoutMail($booking));
        }

        if ($booking->tamu && $booking->tamu->no_hp) {
            $pesanWa = "Yth. Bapak/Ibu " . $booking->tamu->nama_lengkap . ",\n\n" .
                       "Proses Check-out Anda dengan Kode Booking *" . $booking->kode_booking . "* telah BERHASIL.\n" .
                       "Status Tagihan: *LUNAS*\n\n" .
                       "Terima kasih telah menginap di LuxeHotel. Kami menantikan kunjungan Anda kembali!";

            $fonnte->sendMessage($booking->tamu->no_hp, $pesanWa);
        }

        return redirect()->route('booking.index')->with('success', 'Check-out berhasil! Pelunasan dicatat dan kuitansi otomatis terkirim.');
    }
}