@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bookings</h1>
        <a href="{{ route('booking.create') }}" class="px-4 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors flex items-center gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            New Booking
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $booking->kode_booking }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $booking->tamu->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $booking->tanggal_checkin->format('Y-m-d') }} to {{ $booking->tanggal_checkout->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $booking->status === 'confirmed' ? 'bg-blue-50 text-blue-700' : '' }}
                                {{ $booking->status === 'checkin' ? 'bg-orange-50 text-orange-700' : '' }}
                                {{ $booking->status === 'checkout' ? 'bg-green-50 text-green-700' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-red-50 text-red-700' : '' }}
                                {{ $booking->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <a href="{{ route('booking.show', $booking) }}" class="text-primary-600 hover:text-primary-700"><i data-lucide="eye" class="w-5 h-5"></i></a>
                            @can('cancel', $booking)
                            <form action="{{ route('booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Cancel this booking?')">
                                @csrf
                                <button class="text-amber-600 hover:text-amber-700" title="Cancel Booking"><i data-lucide="x-circle" class="w-5 h-5"></i></button>
                            </form>
                            @endcan

                            @if($booking->status === 'cancelled' && auth()->user()->isAdmin())
                            <form action="{{ route('booking.destroy', $booking) }}" method="POST" onsubmit="return confirm('Delete this booking permanently?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-700" title="Delete Booking"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </x-card>
</div>
@endsection
