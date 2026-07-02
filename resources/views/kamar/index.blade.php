@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Accommodation Units</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Oversee and manage the status of all available rooms.</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('kamar.create') }}" class="px-6 py-3 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 flex items-center gap-2 transform active:scale-95">
            <i data-lucide="plus" class="w-5 h-5 text-accent-gold"></i>
            Register Room
        </a>
        @endif
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Room Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Classification</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Current Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @foreach($kamars as $kamar)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">Room {{ $kamar->nomor_kamar }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold mt-0.5 tracking-wider uppercase">Level {{ $kamar->lantai }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $kamar->tipeKamar->nama_tipe }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $kamar->status === 'tersedia' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                {{ $kamar->status === 'ditempati' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : '' }}
                                {{ $kamar->status === 'maintenance' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                            ">
                                {{ $kamar->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kamar.show', $kamar) }}" class="p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-blue-900/20 rounded-xl transition-colors"><i data-lucide="eye" class="w-5 h-5"></i></a>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('kamar.edit', $kamar) }}" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-colors"><i data-lucide="edit-3" class="w-5 h-5"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $kamars->links() }}
        </div>
    </x-card>
</div>
@endsection
