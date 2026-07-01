@extends('layouts.app')

@section('title', 'Manifes Hunian Aktif (Check-in)')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-emerald-200 font-bold flex items-center gap-2">
                <i data-lucide="door-open" class="w-6 h-6 text-emerald-400"></i> Log Hunian & Check-In Aktif
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Pantau daftar makhluk yang saat ini sedang menempati bilik tidur dan durasi singgah mereka.</p>
        </div>
        
        <a href="{{ route('booking.index') }}" 
           class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold rounded border border-purple-950/60 flex items-center gap-2 transition-all self-start sm:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Lihat Manifes Utama
        </a>
    </div>

    {{-- FILTER SEARCH --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('checkin.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Booking / Nama Penghuni..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 text-xs focus:outline-none focus:border-purple-600 transition-all">
            </div>
            <button type="submit" class="px-5 py-2 bg-emerald-950/60 hover:bg-emerald-900/60 text-emerald-300 text-xs font-medium border border-emerald-900/40 rounded transition-colors">
                Cari Penghuni
            </button>
        </form>
    </div>

    {{-- TABEL DATA CHECK-IN --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">Kode Booking</th>
                        <th class="py-3 px-4">Nama Penghuni</th>
                        <th class="py-3 px-4">Bilik Kamar</th>
                        <th class="py-3 px-4">Rencana Menginap</th>
                        <th class="py-3 px-4">Status Transaksi</th>
                        <th class="py-3 px-4 text-center">Aksi Lanjutan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($checkins as $booking)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-4 px-4 font-mono font-bold text-amber-500 text-sm">
                            {{ $booking->kode_booking }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-semibold text-purple-100 text-sm">{{ $booking->tamu->nama_lengkap ?? 'Tanpa Nama' }}</div>
                            <span class="text-[10px] text-slate-500">// HP: {{ $booking->tamu->no_hp ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-4">
                            @foreach($booking->kamars as $kamar)
                                <span class="px-1.5 py-0.5 bg-slate-950 border border-emerald-950/80 rounded text-emerald-400 font-mono text-[11px] inline-block mr-1">
                                    🚪 Kamar {{ $kamar->nomor_kamar }}
                                </span>
                            @endforeach
                        </td>
                        <td class="py-4 px-4 text-slate-400">
                            <div class="text-xs text-slate-300">{{ $booking->tanggal_checkin->format('d M Y') }} s/d {{ $booking->tanggal_checkout->format('d M Y') }}</div>
                            <span class="text-[10px] text-purple-400 font-medium">{{ $booking->jumlah_malam }} Malam Masa Singgah</span>
                        </td>
                        <td class="py-4 px-4">
                            @if($booking->status === 'checkin')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-900">Sedang Menginap</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-950 text-slate-400 border border-purple-950">Sudah Check-out</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($booking->status === 'checkin')
                                {{-- Tombol shortcut langsung menuju aksi Checkout --}}
                                <form action="{{ route('checkout.proses', $booking->id) }}" method="POST" onsubmit="return confirm('Selesaikan masa tinggal dan lakukan ritual Check-out?')" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-950 hover:bg-red-900 text-red-400 border border-red-900 rounded text-[10px] font-semibold transition-colors flex items-center gap-1 mx-auto">
                                        <i data-lucide="log-out" class="w-3 h-3"></i> Eksekusi Check-out
                                    </button>
                                </form>
                            @else
                                <span class="text-[11px] text-slate-500 italic">Arsip Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 italic">Tidak ada makhluk yang sedang menginap saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $checkins->links() }}
        </div>
    </div>
</div>
@endsection