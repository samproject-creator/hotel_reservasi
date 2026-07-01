@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Rooms</h1>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('kamar.create') }}" class="px-4 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors flex items-center gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Add Room
        </a>
        @endif
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($kamars as $kamar)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">Room {{ $kamar->nomor_kamar }}</p>
                            <p class="text-xs text-gray-500">Floor {{ $kamar->lantai }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $kamar->tipeKamar->nama_tipe }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $kamar->status === 'tersedia' ? 'bg-green-50 text-green-700' : '' }}
                                {{ $kamar->status === 'ditempati' ? 'bg-orange-50 text-orange-700' : '' }}
                                {{ $kamar->status === 'maintenance' ? 'bg-red-50 text-red-700' : '' }}
                            ">
                                {{ $kamar->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <a href="{{ route('kamar.show', $kamar) }}" class="text-primary-600 hover:text-primary-700"><i data-lucide="eye" class="w-5 h-5"></i></a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('kamar.edit', $kamar) }}" class="text-amber-600 hover:text-amber-700"><i data-lucide="edit-3" class="w-5 h-5"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $kamars->links() }}
        </div>
    </x-card>
</div>
@endsection
