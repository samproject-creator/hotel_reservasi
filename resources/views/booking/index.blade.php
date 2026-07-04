@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Reservations</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Coordinate guest stays and manage booking lifecycles.</p>
        </div>
        <a href="{{ route('booking.create') }}" class="px-6 py-3 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 flex items-center gap-2 transform active:scale-95">
            <i data-lucide="calendar-plus" class="w-5 h-5 text-accent-gold"></i>
            New Reservation
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Patron</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Itinerary</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $booking->kode_booking }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $booking->tamu->nama_lengkap }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold mt-0.5 tracking-wider">{{ $booking->tamu->nik }}</p>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700">
                                <span class="text-[10px] font-black text-gray-600 dark:text-gray-300">{{ $booking->tanggal_checkin->format('d M') }}</span>
                                <i data-lucide="arrow-right" class="w-3 h-3 text-gray-300"></i>
                                <span class="text-[10px] font-black text-gray-600 dark:text-gray-300">{{ $booking->tanggal_checkout->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $booking->status === 'checkin' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : '' }}
                                {{ $booking->status === 'checkout' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                {{ $booking->status === 'pending' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' : '' }}
                            ">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right relative z-10">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('booking.show', $booking) }}" class="p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-blue-900/20 rounded-xl transition-colors" title="Audit Reservation"><i data-lucide="file-search" class="w-5 h-5"></i></a>
                                @can('cancel', $booking)
                                <form action="{{ route('booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Annul this reservation?')" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-colors" title="Cancel Booking"><i data-lucide="calendar-x" class="w-5 h-5"></i></button>
                                </form>
                                @endcan
                                @if($booking->status === 'cancelled' && auth()->user()->isAdmin())
                                <form action="{{ route('booking.destroy', $booking) }}" method="POST" onsubmit="return confirm('Purge this record from history?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Delete Permanent"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    </x-card>
</div>
@endsection
