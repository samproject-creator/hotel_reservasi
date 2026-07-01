<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Models\Checkout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Statistik Ringkasan ---
        $totalKamar       = Kamar::count();
        $kamarTersedia    = Kamar::where('status', 'tersedia')->count();
        $kamarDitempati   = Kamar::where('status', 'ditempati')->count();
        $totalTamu        = Tamu::count();
        $totalBooking     = Booking::count();
        $bookingHariIni   = Booking::whereDate('created_at', today())->count();
        $bookingCheckin   = Booking::where('status', 'checkin')->count();

        // Pendapatan bulan ini dari checkout
        $pendapatanBulanIni = Checkout::whereMonth('waktu_checkout', now()->month)
                                       ->whereYear('waktu_checkout', now()->year)
                                       ->sum('total_bayar');

        // --- Data Grafik: Booking 6 bulan terakhir ---
        $grafikBooking = Booking::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // --- Data Grafik: Pendapatan 6 bulan terakhir ---
        $grafikPendapatan = Checkout::select(
                DB::raw("DATE_FORMAT(waktu_checkout, '%Y-%m') as bulan"),
                DB::raw('SUM(total_bayar) as total')
            )
            ->where('waktu_checkout', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // --- Booking terbaru ---
        $bookingTerbaru = Booking::with(['tamu', 'kamars.tipeKamar'])
                                  ->latest()
                                  ->take(5)
                                  ->get();

        return view('dashboard.index', compact(
            'totalKamar', 'kamarTersedia', 'kamarDitempati',
            'totalTamu', 'totalBooking', 'bookingHariIni',
            'bookingCheckin', 'pendapatanBulanIni',
            'grafikBooking', 'grafikPendapatan', 'bookingTerbaru'
        ));
    }
}
