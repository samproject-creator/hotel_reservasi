@extends('layouts.app')

@section('title', 'Booking Details - ' . $booking->kode_booking)

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            <a href="{{ route('booking.index') }}" class="p-3 bg-white dark:bg-slate-850 border border-gray-200 dark:border-slate-800 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm group">
                <i data-lucide="arrow-left" class="w-6 h-6 text-gray-400 group-hover:text-primary-600 transition-colors"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Booking Dossier</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Detailed itinerary and financial summary for reservation #{{ $booking->kode_booking }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @can('update', $booking)
                <a href="{{ route('booking.edit', $booking) }}" class="px-5 py-2.5 bg-white dark:bg-slate-850 text-amber-600 dark:text-amber-400 font-black rounded-xl hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all border border-amber-100 dark:border-amber-900/30 text-xs uppercase tracking-widest shadow-sm">Refine Reservation</a>
            @endcan
            @if($booking->status === 'confirmed')
                <form action="{{ route('checkin.proses', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-black rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/20 text-xs uppercase tracking-widest transform active:scale-95">Authorize Check-In</button>
                </form>
            @elseif($booking->status === 'checkin')
                <a href="{{ route('checkout.show', $booking) }}" class="px-5 py-2.5 bg-orange-600 text-white font-black rounded-xl hover:bg-orange-700 transition-all shadow-lg shadow-orange-500/20 text-xs uppercase tracking-widest transform active:scale-95">Initiate Departure</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <x-card title="General Information">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Booking ID</p>
                        <p class="text-xl font-black text-gray-900 dark:text-white">{{ $booking->kode_booking }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</p>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                            {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                            {{ $booking->status === 'checkin' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : '' }}
                            {{ $booking->status === 'checkout' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                            {{ $booking->status === 'pending' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' : '' }}
                        ">
                            {{ $booking->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Stay Duration</p>
                        <p class="text-gray-900 dark:text-white font-bold">{{ $booking->jumlah_malam }} Nights</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Check-In Date</p>
                        <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Check-Out Date</p>
                        <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Number of Guests</p>
                        <p class="text-gray-900 font-medium">{{ $booking->jumlah_tamu }} Persons</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Created By</p>
                        <p class="text-gray-900 font-medium">{{ $booking->user->name }}</p>
                    </div>
                </div>
                @if($booking->catatan)
                <div class="mt-6 pt-6 border-t border-gray-50">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Notes</p>
                    <p class="text-gray-600 mt-1">{{ $booking->catatan }}</p>
                </div>
                @endif
            </x-card>

            <x-card title="Reserved Rooms">
                <div class="space-y-4">
                    @foreach($booking->kamar as $kamar)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            <div class="bg-white dark:bg-gray-700 p-2 rounded-xl text-primary-600 shadow-sm border border-gray-100 dark:border-gray-600">
                                <i data-lucide="door-closed" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">Room {{ $kamar->nomor_kamar }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kamar->tipeKamar->nama_tipe }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($kamar->pivot->harga_malam, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">per night</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card title="Guest Information">
                <div class="space-y-4">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-50 dark:border-gray-700">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white leading-tight">{{ $booking->tamu->nama_lengkap }}</p>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">{{ $booking->tamu->nik }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-3"><i data-lucide="phone" class="w-4 h-4 text-primary-500"></i> {{ $booking->tamu->no_hp }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-3"><i data-lucide="mail" class="w-4 h-4 text-primary-500"></i> {{ $booking->tamu->email ?? '-' }}</p>
                    </div>
                    <a href="{{ route('tamu.show', $booking->tamu) }}" class="block w-full py-2.5 bg-gray-50 dark:bg-gray-700 text-center text-xs font-bold text-primary-600 dark:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-xl transition-colors">View Guest History</a>
                </div>
            </x-card>

            <x-card title="Billing Summary">
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Total Bill</span>
                        <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Paid (DP)</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">- Rp {{ number_format($booking->uang_muka, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-end">
                        <span class="font-bold text-gray-900 dark:text-white">Remaining</span>
                        <span class="font-black text-xl text-primary-600">Rp {{ number_format($booking->total_harga - $booking->uang_muka, 0, ',', '.') }}</span>
                    </div>
                </div>
            </x-card>

            <x-card title="Booking QR" class="flex flex-col items-center justify-center text-center">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-4 inline-block">
                    {!! \App\Services\QrCodeService::generate($booking, 150) !!}
                </div>
                <p class="text-xs text-gray-400">Scan this code for quick check-in</p>
            </x-card>
        </div>
    </div>
</div>
@endsection
