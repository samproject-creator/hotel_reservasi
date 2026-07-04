@extends('layouts.app')

@section('title', 'Laporan Finansial')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Intelligence & Analytics</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Deep insights into LuxeHotel's financial performance.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('laporan.pdf', request()->all()) }}" class="px-6 py-3 bg-white dark:bg-slate-800 text-red-600 dark:text-red-400 font-black rounded-2xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all border-2 border-red-100 dark:border-red-900/30 flex items-center gap-2 text-xs uppercase tracking-widest shadow-sm">
                <i data-lucide="file-text" class="w-5 h-5"></i> Export PDF
            </a>
            <a href="{{ route('laporan.excel', request()->all()) }}" class="px-6 py-3 bg-white dark:bg-slate-800 text-green-600 dark:text-green-400 font-black rounded-2xl hover:bg-green-50 dark:hover:bg-green-900/20 transition-all border-2 border-green-100 dark:border-green-900/30 flex items-center gap-2 text-xs uppercase tracking-widest shadow-sm">
                <i data-lucide="file-spreadsheet" class="w-5 h-5"></i> Export Excel
            </a>
        </div>
    </div>

    <x-card>
        <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Audit Commencement</label>
                <input type="date" name="dari" value="{{ $dari->format('Y-m-d') }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Audit Conclusion</label>
                <input type="date" name="sampai" value="{{ $sampai->format('Y-m-d') }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
            </div>
            <div class="pb-0.5">
                <button type="submit" class="w-full px-6 py-3.5 bg-gray-900 dark:bg-slate-700 text-white font-black rounded-2xl hover:bg-black dark:hover:bg-slate-600 transition-all shadow-xl shadow-gray-900/10 uppercase text-xs tracking-widest">Execute Filter</button>
            </div>
        </form>
    </x-card>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-8 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Aggregate Revenue</p>
            <p class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="p-8 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm">
            <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Down Payment</p>
            <p class="text-2xl font-black text-blue-600 dark:text-blue-400">Rp {{ number_format($totalUangMuka, 0, ',', '.') }}</p>
        </div>
        <div class="p-8 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm">
            <p class="text-[10px] font-black text-green-500 uppercase tracking-widest mb-2">Settlements</p>
            <p class="text-2xl font-black text-green-600 dark:text-green-400">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</p>
        </div>
        <div class="p-8 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm">
            <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-2">Outstanding</p>
            <p class="text-2xl font-black text-orange-600 dark:text-orange-400">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</p>
        </div>
    </div>

    <x-card title="Financial Transaction Ledger">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-700">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Identifier</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Client Name</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">State</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                        <td class="px-8 py-5 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $booking->created_at->format('M d, Y') }}</td>
                        <td class="px-8 py-5 text-sm font-black text-gray-900 dark:text-white">{{ $booking->kode_booking }}</td>
                        <td class="px-8 py-5 text-sm font-bold text-gray-600 dark:text-gray-300">{{ $booking->tamu->nama_lengkap }}</td>
                        <td class="px-8 py-5 text-sm">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'check-in' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                    'check-out' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                ];
                                $class = $statusClasses[strtolower($booking->status)] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $class }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-sm font-black text-gray-900 dark:text-white text-right">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
