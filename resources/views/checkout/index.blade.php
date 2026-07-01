@extends('layouts.app')

@section('title', 'Check-Out List')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Check-Out Management</h1>
    </div>

    <x-card>
        <form action="{{ route('checkout.index') }}" method="GET" class="mb-6">
            <div class="relative max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest or booking ID..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Checkout Time</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Paid</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($checkouts as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $booking->kode_booking }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->tamu->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $booking->checkout->waktu_checkout->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">
                            Rp {{ number_format($booking->checkout->total_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('checkout.show', $booking) }}" class="text-primary-600 hover:text-primary-700 font-bold text-xs">Receipt</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $checkouts->links() }}
        </div>
    </x-card>
</div>
@endsection
