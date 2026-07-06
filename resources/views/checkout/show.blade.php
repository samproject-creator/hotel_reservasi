@extends('layouts.app')

@section('title', 'Process Check-Out - ' . $booking->kode_booking)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-6">
        <a href="{{ route('checkout.index') }}" class="p-3 bg-white dark:bg-slate-850 border border-gray-200 dark:border-slate-800 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm group">
            <i data-lucide="arrow-left" class="w-6 h-6 text-gray-400 group-hover:text-primary-600 transition-colors"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Settlement Portfolio</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Finalizing financial records and departure for #{{ $booking->kode_booking }}</p>
        </div>
    </div>

    @if($booking->status === 'checkin')
    <form action="{{ route('checkout.proses', $booking) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @csrf
        <div class="space-y-6">
            <x-card title="Billing Summary">
                <div class="space-y-4">
                    <div class="flex justify-between pb-3 border-b border-gray-50">
                        <span class="text-gray-500">Total Accommodation</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-50 text-blue-600">
                        <span class="font-medium">Down Payment Already Paid</span>
                        <span class="font-bold">- Rp {{ number_format($booking->uang_muka, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-lg font-black text-gray-900">Remaining Balance</span>
                        <span class="text-2xl font-black text-primary-600">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </x-card>

            <x-card title="Guest & Stay Details">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Guest Name</span>
                        <span class="font-bold text-gray-900">{{ $booking->tamu->nama_lengkap }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Stay Duration</span>
                        <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal_checkin)->diffInDays($booking->tanggal_checkout) }} Nights</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Rooms</span>
                        <span class="font-bold text-gray-900">
                            @foreach($booking->kamar as $kamar) {{ $kamar->nomor_kamar }}{{ !$loop->last ? ',' : '' }} @endforeach
                        </span>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card title="Payment Processing">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Payment Method</label>
                        <!-- Ditambahkan dark:bg-slate-800 dan dark:text-white -->
                        <select name="metode_pembayaran" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 dark:text-white transition-all">
                            <option value="cash">Cash</option>
                            <option value="transfer">Bank Transfer</option>
                            <option value="kartu_kredit">Credit Card</option>
                            <option value="debit">Debit Card</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Amount Paid (Customer Pay)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 font-bold">Rp</span>
                            <!-- Diubah bg-white menjadi bg-white dark:bg-slate-800 serta ditambah dark:text-white -->
                            <input type="number" name="total_bayar" value="{{ $sisaTagihan }}" min="{{ $sisaTagihan }}" required class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all text-xl font-black dark:text-white">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">Enter the amount received from the guest. Change will be calculated automatically.</p>
                    </div>

                    <button type="submit" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-2xl shadow-lg shadow-primary-200 dark:shadow-none transition-all transform active:scale-[0.98] mt-4 flex items-center justify-center gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        COMPLETE CHECK-OUT
                    </button>
                </div>
            </x-card>
        </div>
    </form>
    @else
    {{-- Display Receipt for already checked-out bookings --}}
    <x-card title="Payment Receipt" class="max-w-2xl mx-auto">
        <div class="text-center mb-8 border-b border-dashed border-gray-200 pb-8">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-1 uppercase tracking-tighter">LuxeHotel</h2>
            <p class="text-gray-500 text-sm">Official Payment Receipt</p>
        </div>

        <div class="space-y-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] uppercase font-bold text-gray-400">Booking ID</p>
                    <p class="font-black text-gray-900 dark:text-white">#{{ $booking->kode_booking }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Date</p>
                    <p class="font-bold text-gray-900 dark:text-white">{{ $booking->checkout->waktu_checkout->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="py-4 border-y border-gray-100 dark:border-slate-800 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Accommodation Charge</span>
                    <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($booking->checkout->total_tagihan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Method</span>
                    <span class="font-bold text-gray-900 dark:text-white uppercase">{{ $booking->checkout->metode_pembayaran }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between text-lg">
                    <span class="font-bold text-gray-900 dark:text-white">Amount Paid</span>
                    <span class="font-black text-gray-900 dark:text-white">Rp {{ number_format($booking->checkout->total_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-green-600">
                    <span class="font-medium">Change</span>
                    <span class="font-bold">Rp {{ number_format($booking->checkout->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-primary-50 dark:bg-primary-950/40 p-4 rounded-xl flex items-center justify-between">
                <div>
                    <p class="text-primary-700 dark:text-primary-400 font-bold">Payment Status</p>
                    <p class="text-xs text-primary-600 dark:text-primary-500">Paid in full via {{ $booking->checkout->metode_pembayaran }}</p>
                </div>
                <div class="bg-primary-600 text-white p-2 rounded-lg font-black text-xs">PAID</div>
            </div>

            <div class="pt-8 text-center">
                <p class="text-xs text-gray-400">Thank you for staying at LuxeHotel Premium.</p>
                <div class="mt-6 flex justify-center gap-4 no-print">
                    <button onclick="window.print()" class="px-6 py-2 bg-gray-900 dark:bg-slate-800 text-white font-bold rounded-xl hover:bg-gray-800 dark:hover:bg-slate-700 transition-colors">Print Receipt</button>
                    <a href="{{ route('checkout.index') }}" class="px-6 py-2 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">Back to List</a>
                </div>
            </div>
        </div>
    </x-card>
    @endif
</div>

<style>
@media print {
    .no-print, header, aside, footer { display: none !important; }
    main { padding: 0 !important; }
    .max-w-4xl, .max-w-2xl { max-width: 100% !important; margin: 0 !important; }
    .bg-white { border: none !important; box-shadow: none !important; }
}
</style>
@endsection