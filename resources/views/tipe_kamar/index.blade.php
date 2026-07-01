@extends('layouts.app')

@section('title', 'Room Types')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Room Types</h1>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('tipe-kamar.create') }}" class="px-4 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors flex items-center gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            New Type
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tipeKamars as $tipe)
        <x-card class="flex flex-col">
            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $tipe->nama_tipe }}</h3>
                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $tipe->deskripsi }}</p>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400">Capacity</p>
                        <p class="text-sm font-bold text-gray-700">{{ $tipe->kapasitas }} Persons</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400">Price /Night</p>
                        <p class="text-sm font-bold text-primary-600">Rp {{ number_format($tipe->harga_per_malam, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Facilities</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tipe->fasilitas as $fasilitas)
                        <span class="px-2 py-1 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs">{{ $fasilitas }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="mt-6 pt-6 border-t border-gray-50 flex items-center gap-3">
                <a href="{{ route('tipe-kamar.edit', $tipe) }}" class="flex-1 px-4 py-2 bg-gray-50 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition-colors text-center text-sm border border-gray-200">Edit</a>
                <form action="{{ route('tipe-kamar.destroy', $tipe) }}" method="POST" onsubmit="return confirm('Delete this room type?')">
                    @csrf @method('DELETE')
                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors border border-transparent hover:border-red-100"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                </form>
            </div>
            @endif
        </x-card>
        @endforeach
    </div>
</div>
@endsection
