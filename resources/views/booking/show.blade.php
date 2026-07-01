@extends('layouts.app')

@section('title', 'Booking Details - ' . $booking->kode_booking)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('booking.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-500"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Booking Details</h1>
        </div>
        <div class="flex items-center gap-3">
            @can('update', $booking)
                <a href="{{ route('booking.edit', $booking) }}" class="px-4 py-2 bg-amber-50 text-amber-700 font-bold rounded-xl hover:bg-amber-100 transition-colors border border-amber-200">Edit Booking</a>
            @endcan
            @if($booking->status === 'confirmed')
                <form action="{{ route('checkin.proses', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">Process Check-In</button>
                </form>
            @elseif($booking->status === 'checkin')
                <a href="{{ route('checkout.show', $booking) }}" class="px-4 py-2 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors">Process Check-Out</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-card title="General Information">
                <div class="grid grid-cols-2 gap-y-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Booking ID</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->kode_booking }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</p>
                        <span class="mt-1 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $booking->status === 'confirmed' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $booking->status === 'checkin' ? 'bg-orange-50 text-orange-700' : '' }}
                            {{ $booking->status === 'checkout' ? 'bg-green-50 text-green-700' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-50 text-red-700' : '' }}
                        ">
                            {{ $booking->status }}
                        </span>
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
                    @foreach($booking->kamars as $kamar)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="bg-white p-2 rounded-xl text-primary-600 shadow-sm border border-gray-100">
                                <i data-lucide="door-closed" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Room {{ $kamar->nomor_kamar }}</p>
                                <p class="text-sm text-gray-500">{{ $kamar->tipeKamar->nama_tipe }}</p>
                            </div>
                        </div>
                        <p class="font-bold text-gray-900">Rp {{ number_format($kamar->pivot->harga_malam, 0, ',', '.') }}<span class="text-xs text-gray-400 font-normal"> /night</span></p>
                    </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card title="Guest Information">
                <div class="space-y-4">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $booking->tamu->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->tamu->nik }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600 flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4"></i> {{ $booking->tamu->no_hp }}</p>
                        <p class="text-sm text-gray-600 flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4"></i> {{ $booking->tamu->email ?? '-' }}</p>
                    </div>
                    <a href="{{ route('tamu.show', $booking->tamu) }}" class="block text-center text-sm font-bold text-primary-600 hover:text-primary-700 pt-2">View Guest History</a>
                </div>
            </x-card>

            <x-card title="Billing Summary">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Bill</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Paid (DP)</span>
                        <span class="font-bold text-blue-600">- Rp {{ number_format($booking->uang_muka, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between">
                        <span class="font-bold text-gray-900">Remaining</span>
                        <span class="font-black text-lg text-primary-600">Rp {{ number_format($booking->total_harga - $booking->uang_muka, 0, ',', '.') }}</span>
                    </div>
                </div>
            </x-card>

            <x-card title="Booking QR" class="flex flex-col items-center justify-center text-center">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-4 inline-block">
                    {!! \App\Services\QrCodeService::generate($booking->kode_booking, 150) !!}
                </div>
                <p class="text-xs text-gray-400">Scan this code for quick check-in</p>
            </x-card>
        </div>
    </div>
</div>
@endsection
