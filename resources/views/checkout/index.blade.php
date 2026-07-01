@extends('layouts.app')

@section('title', 'Arsip Transaksi Selesai (Check-out)')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-red-200 font-bold flex items-center gap-2">
                <i data-lucide="archive" class="w-6 h-6 text-red-400"></i> Log & Arsip Pelunasan Check-Out
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Daftar riwayat reservasi lama yang telah merampungkan masa tinggal dan menyelesaikan administrasi.</p>
        </div>
        
        <a href="{{ route('booking.index') }}" 
           class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold rounded border border-purple-950/60 flex items-center gap-2 transition-all self-start sm:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Manifes Utama
        </a>
    </div>

    {{-- FILTER SEARCH --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('checkout.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Booking / Nama Mantan Tamu..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 text-xs focus:outline-none focus:border-purple-600 transition-all">
            </div>
            <button type="submit" class="px-5 py-2 bg-red-950/40 hover:bg-red-900/40 text-red-300 text-xs font-medium border border-red-900/40 rounded transition-colors">
                Saring Arsip
            </button>
        </form>
    </div>

    {{-- TABEL DATA CHECK-OUT --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">Kode Booking</th>
                        <th class="py-3 px-4">Nama Mantan Tamu</th>
                        <th class="py-3 px-4">Bilik Kamar Terpakai</th>
                        <th class="py-3 px-4">Total Biaya</th>
                        <th class="py-3 px-4">Status Finansial</th>
                        <th class="py-3 px-4 text-center">Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($checkouts as $booking)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-4 px-4 font-mono font-bold text-amber-500 text-sm">
                            {{ $booking->kode_booking }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-semibold text-purple-100 text-sm">{{ $booking->tamu->nama_lengkap ?? 'Tanpa Nama' }}</div>
                            <span class="text-[10px] text-slate-500">// NIK: {{ $booking->tamu->nik ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-4">
                            @foreach($booking->kamars as $kamar)
                                <span class="px-1.5 py-0.5 bg-slate-950 border border-purple-950 rounded text-slate-400 font-mono text-[11px] inline-block mr-1">
                                    {{ $kamar->nomor_kamar }}
                                </span>
                            @endforeach
                        </td>
                        <td class="py-4 px-4 font-mono font-bold text-purple-300">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-950 text-emerald-400 border border-emerald-950">Lunas Telah Keluar</span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            {{-- Tombol Cetak Nota Digital / Kwitansi --}}
                            <a href="#" class="px-2 py-1 bg-purple-950/60 hover:bg-purple-900/60 text-purple-300 border border-purple-900/40 rounded text-[10px] font-semibold flex items-center justify-center gap-1 w-max mx-auto transition-colors">
                                <i data-lucide="printer" class="w-3 h-3"></i> Cetak Kwitansi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 italic">Belum ada riwayat transaksi check-out yang terekam.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $checkouts->links() }}
        </div>
    </div>
</div>
@endsection