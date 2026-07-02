@extends('layouts.app')

@section('title', 'Check-Out List')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Departure Registry</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Finalized accounts and checkout history of LuxeHotel patrons.</p>
        </div>
    </div>

    <x-card>
        <form action="{{ route('checkout.index') }}" method="GET" class="mb-8">
            <div class="relative max-w-md group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest or booking ID..."
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all dark:text-white font-medium">
            </div>
        </form>

        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Transaction ID</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Patron</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Settlement Time</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Total Settlement</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @foreach($checkouts as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $booking->kode_booking }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $booking->tamu->nama_lengkap }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold mt-0.5 tracking-wider uppercase">{{ $booking->tamu->nik }}</p>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $booking->checkout->waktu_checkout->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $booking->checkout->waktu_checkout->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <p class="text-sm font-black text-green-600 dark:text-green-400">Rp {{ number_format($booking->checkout->total_bayar, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('checkout.show', $booking) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 dark:bg-blue-900/20 text-primary-700 dark:text-blue-400 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-primary-600 hover:text-white transition-all border border-primary-100 dark:border-blue-900/30">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                View Receipt
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $checkouts->links() }}
        </div>
    </x-card>
</div>
@endsection
