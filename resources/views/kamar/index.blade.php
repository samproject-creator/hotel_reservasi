@extends('layouts.app')
@section('title', 'Rooms')
@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                Accommodation Units
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">
                Oversee and manage the status of all available rooms.
            </p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('kamar.create') }}"
               class="px-6 py-3 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 flex items-center gap-2">

                <i data-lucide="plus" class="w-5 h-5"></i>
                Register Room
            </a>
        @endif
    </div>
    <x-card>
        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                <tr>
                    <th class="px-8 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-400">
                        Room Identity
                    </th>
                    <th class="px-8 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-400">
                        Classification
                    </th>
                    <th class="px-8 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-400">
                        Current Status
                    </th>
                    <th class="px-8 py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-400">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">

                @forelse($kamar as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition">
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white">
                                Room {{ $item->nomor_kamar }}
                            </p>
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">
                                Level {{ $item->lantai }}
                            </p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-gray-700 dark:text-gray-300">
                                {{ $item->tipeKamar->nama_tipe }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            @php
                                $badge = match($item->status){
                                    'tersedia'    => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'ditempati'   => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'maintenance' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    default       => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $badge }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('kamar.show',$item) }}"
                                   class="p-2 rounded-xl text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('kamar.edit',$item) }}"
                                       class="p-2 rounded-xl text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-400">
                            No rooms found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $kamar->links() }}
        </div>
    </x-card>
</div>
@endsection