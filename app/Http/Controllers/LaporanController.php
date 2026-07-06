<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default filter bulan ini jika user belum memilih tanggal
        $dari = $request->filled('dari') ? Carbon::parse($request->dari)->startOfDay() : Carbon::now()->startOfMonth();
        $sampai = $request->filled('sampai') ? Carbon::parse($request->sampai)->endOfDay() : Carbon::now()->endOfMonth();

        // Query data booking yang menghasilkan income (Check-in & Check-out)
        $query = Booking::with(['tamu', 'kamar'])
            ->whereIn('status', ['checkin', 'checkout'])
            ->whereBetween('tanggal_checkin', [$dari, $sampai]);

        $bookings = $query->latest()->get();

        // Ringkasan Finansial Akumulatif
        $totalPendapatan = $bookings->sum('total_harga');
        $totalUangMuka   = $bookings->sum('uang_muka');
        $totalPelunasan  = $bookings->where('status', 'checkout')->sum(function($b) {
            return $b->total_harga - $b->uang_muka;
        });
        $totalPiutang    = $bookings->where('status', 'checkin')->sum(function($b) {
            return $b->total_harga - $b->uang_muka;
        });

        return view('laporan.index', compact(
            'bookings', 'dari', 'sampai', 
            'totalPendapatan', 'totalUangMuka', 'totalPelunasan', 'totalPiutang'
        ));
    }

    // Ekstensi Export PDF
    public function exportPdf(Request $request)
    {
        $dari = \Carbon\Carbon::parse($request->dari)->startOfDay();
        $sampai = \Carbon\Carbon::parse($request->sampai)->endOfDay();

        $bookings = Booking::with(['tamu', 'kamar'])
            ->whereIn('status', ['checkin', 'checkout'])
            ->whereBetween('tanggal_checkin', [$dari, $sampai])
            ->latest()
            ->get();

        // Pastikan memanggil kelas Pdf dari package dompdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', compact('bookings', 'dari', 'sampai'));
        
        return $pdf->download('Laporan_Finansial_'.$dari->format('Ymd').'_'.$sampai->format('Ymd').'.pdf');
    }

    // Ekstensi Export Excel
    public function exportExcel(Request $request)
    {
        // Menggunakan header bawaan browser untuk format Excel sederhana (.xls) tanpa library tambahan
        $dari = Carbon::parse($request->dari)->startOfDay();
        $sampai = Carbon::parse($request->sampai)->endOfDay();

        $bookings = Booking::with(['tamu', 'kamar'])
            ->whereIn('status', ['checkin', 'checkout'])
            ->whereBetween('created_at', [$dari, $sampai])
            ->get();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Finansial_Hotel.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo view('laporan.excel', compact('bookings', 'dari', 'sampai'))->render();
        exit;
    }
}