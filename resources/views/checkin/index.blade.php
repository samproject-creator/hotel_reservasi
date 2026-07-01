@extends('layouts.app')

@section('title', 'Check-In List')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Check-In Management</h1>
    </div>

    <x-card>
        <form action="{{ route('checkin.index') }}" method="GET" class="mb-6">
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
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rooms</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($checkins as $checkin)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $checkin->kode_booking }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $checkin->tamu->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $checkin->tamu->no_hp }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($checkin->kamars as $kamar)
                                <span class="px-2 py-0.5 bg-primary-50 text-primary-700 rounded-md text-[10px] font-bold border border-primary-100">{{ $kamar->nomor_kamar }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                {{ $checkin->status === 'checkin' ? 'bg-orange-50 text-orange-700' : 'bg-green-50 text-green-700' }}">
                                {{ $checkin->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('checkin.show', $checkin) }}" class="text-primary-600 hover:text-primary-700 font-bold text-xs">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $checkins->links() }}
        </div>
    </x-card>
</div>
@endsection
