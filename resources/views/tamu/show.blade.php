@extends('layouts.app')

@section('title', 'Guest Details - ' . $tamu->nama_lengkap)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('tamu.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 text-gray-500"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Guest Profile</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            <x-card>
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center mb-4">
                        <span class="text-3xl font-black">{{ substr($tamu->nama_lengkap, 0, 1) }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $tamu->nama_lengkap }}</h2>
                    <p class="text-sm text-gray-500">{{ $tamu->pekerjaan }}</p>

                    <div class="mt-6 w-full space-y-3 pt-6 border-t border-gray-50">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="credit-card" class="w-4 h-4 text-gray-400"></i>
                            {{ $tamu->nik }}
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                            {{ $tamu->no_hp }}
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                            {{ $tamu->email ?? '-' }}
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                            {{ $tamu->alamat }}
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4 w-full">
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] uppercase font-bold text-gray-400">Gender</p>
                            <p class="text-sm font-bold text-gray-700">{{ $tamu->jenis_kelamin === 'L' ? 'Male' : 'Female' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] uppercase font-bold text-gray-400">Citizenship</p>
                            <p class="text-sm font-bold text-gray-700">{{ $tamu->kewarganegaraan }}</p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <x-card title="Booking History">
                @if($tamu->bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stay Dates</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tamu->bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('booking.show', $booking) }}" class="text-sm font-bold text-primary-600 hover:underline">{{ $booking->kode_booking }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $booking->tanggal_checkin }} to {{ $booking->tanggal_checkout }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase border
                                        {{ $booking->status === 'checkout' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <p class="text-gray-400">No booking history found.</p>
                </div>
                @endif
            </x-card>
        </div>
    </div>
</div>
@endsection
