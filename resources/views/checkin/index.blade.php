@extends('layouts.app')

@section('title', 'Check-In List')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Active Occupancy</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Monitor and manage guests currently residing within LuxeHotel.</p>
        </div>
    </div>

    <x-card>
        <form action="{{ route('checkin.index') }}" method="GET" class="mb-8">
            <div class="relative max-w-md group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest or booking ID..."
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all dark:text-white">
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Booking ID</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Client Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Unit Assignment</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @foreach($checkins as $checkin)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $checkin->kode_booking }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $checkin->tamu->nama_lengkap }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold mt-0.5 tracking-wider">{{ $checkin->tamu->no_hp }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-wrap gap-2">
                                @foreach($checkin->kamar as $kamar)
                                <span class="px-2.5 py-1 bg-primary-50 dark:bg-blue-900/20 text-primary-700 dark:text-blue-400 rounded-lg text-[10px] font-black border border-primary-100 dark:border-blue-900/30 uppercase tracking-widest">Room {{ $kamar->nomor_kamar }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $checkin->status === 'checkin' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                {{ $checkin->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('checkin.show', $checkin) }}" class="px-4 py-2 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors border border-gray-200 dark:border-slate-700">Audit Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $checkins->links() }}
        </div>
    </x-card>
</div>
@endsection
