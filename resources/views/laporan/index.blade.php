@extends('layouts.app')

@section('title', 'Laporan Finansial')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-6 h-6 text-purple-400"></i> Neraca & Laporan Finansial
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Pantau ringkasan arus kas, pelunasan reservasi, dan performa omzet hunian.</p>
        </div>
        
        {{-- Tombol Cetak / Export --}}
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <a href="{{ route('laporan.pdf', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}" 
               class="px-3 py-2 bg-red-950/40 hover:bg-red-900/40 text-red-300 border border-red-900/40 text-xs font-semibold rounded flex items-center gap-1.5 transition-colors">
                <i data-lucide="file-text" class="w-4 h-4"></i> Cetak PDF
            </a>
            <a href="{{ route('laporan.excel', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}" 
               class="px-3 py-2 bg-emerald-950/40 hover:bg-emerald-900/40 text-emerald-300 border border-emerald-900/40 text-xs font-semibold rounded flex items-center gap-1.5 transition-colors">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Ekspor Excel
            </a>
        </div>
    </div>

    {{-- FILTER TANGGAL --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-3 text-xs">
            <div class="w-full sm:w-auto">
                <label class="block text-slate-400 font-medium mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari->toDateString() }}" 
                       class="w-full sm:w-44 px-3 py-1.5 bg-slate-950 border border-purple-950 rounded text-slate-200 font-mono text-xs focus:outline-none focus:border-purple-600">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-slate-400 font-medium mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai->toDateString() }}" 
                       class="w-full sm:w-44 px-3 py-1.5 bg-slate-950 border border-purple-950 rounded text-slate-200 font-mono text-xs focus:outline-none focus:border-purple-600">
            </div>
            <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-purple-950 hover:bg-purple-900 text-purple-200 font-semibold border border-purple-800 rounded transition-colors">
                Buka Riwayat Kas
            </button>
        </form>
    </div>

    {{-- KARTU RESUME FINANSIAL --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
        <div class="bg-slate-900/30 border border-purple-950 rounded-lg p-4">
            <span class="text-slate-400 block font-medium">Total Nilai Kontrak</span>
            <span class="text-xl font-mono font-bold text-purple-300 block mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            <span class="text-[10px] text-slate-500 block mt-0.5">// Omzet kotor terpesan</span>
        </div>
        <div class="bg-slate-900/30 border border-purple-950 rounded-lg p-4">
            <span class="text-slate-400 block font-medium">Total Uang Muka (DP)</span>
            <span class="text-xl font-mono font-bold text-amber-400 block mt-1">Rp {{ number_format($totalUangMuka, 0, ',', '.') }}</span>
            <span class="text-[10px] text-slate-500 block mt-0.5">// Dana jaminan awal masuk</span>
        </div>
        <div class="bg-slate-900/30 border border-purple-950 rounded-lg p-4">
            <span class="text-slate-400 block font-medium">Pelunasan Checkout</span>
            <span class="text-xl font-mono font-bold text-emerald-400 block mt-1">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</span>
            <span class="text-[10px] text-slate-500 block mt-0.5">// Arus kas lunas di tangan</span>
        </div>
        <div class="bg-slate-900/30 border border-purple-950 rounded-lg p-4">
            <span class="text-slate-400 block font-medium">Sisa Piutang Berjalan</span>
            <span class="text-xl font-mono font-bold text-sky-400 block mt-1">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
            <span class="text-[10px] text-slate-500 block mt-0.5">// Tertahan di tamu menginap</span>
        </div>
    </div>

    {{-- TABEL BREAKDOWN TRANSAKSI --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Tamu</th>
                        <th class="py-3 px-4">Bilik Kamar</th>
                        <th class="py-3 px-4 text-right">Uang Muka</th>
                        <th class="py-3 px-4 text-right">Total Tarif</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300 font-sans">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-3 px-4 font-mono font-bold text-purple-300">{{ $booking->kode_booking }}</td>
                        <td class="py-3 px-4 font-medium">{{ $booking->tamu->nama_lengkap ?? '-' }}</td>
                        <td class="py-3 px-4 font-mono">
                            @foreach($booking->kamars as $k)
                                <span class="bg-slate-950 px-1 py-0.5 rounded border border-purple-950 text-[11px]">RM-{{ $k->nomor_kamar }}</span>
                            @endforeach
                        </td>
                        <td class="py-3 px-4 font-mono text-right text-amber-500">Rp {{ number_format($booking->uang_muka, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 font-mono text-right font-bold text-slate-100">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($booking->status === 'checkout')
                                <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-900 rounded">Lunas</span>
                            @else
                                <span class="px-2 py-0.5 text-[9px] font-bold bg-sky-950 text-sky-400 border border-sky-900 rounded">Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 italic">Tidak ditemukan pergerakan kas pada periode tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection