@extends('layouts.app')

@section('title', 'Laporan Finansial')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Financial Reports</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('laporan.pdf', request()->all()) }}" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5"></i> PDF
            </a>
            <a href="{{ route('laporan.excel', request()->all()) }}" class="px-4 py-2 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-colors flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-5 h-5"></i> Excel
            </a>
        </div>
    </div>

    <x-card>
        <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Start Date</label>
                <input type="date" name="dari" value="{{ $dari->format('Y-m-d') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">End Date</label>
                <input type="date" name="sampai" value="{{ $sampai->format('Y-m-d') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">Filter</button>
            </div>
        </form>
    </x-card>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Income</p>
            <p class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Down Payment</p>
            <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalUangMuka, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Settlements</p>
            <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Outstanding</p>
            <p class="text-xl font-bold text-orange-600 mt-1">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</p>
        </div>
    </div>

    <x-card title="Transaction List">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $booking->kode_booking }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->tamu->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="uppercase font-bold text-[10px]">{{ $booking->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
