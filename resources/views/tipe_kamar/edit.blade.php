@extends('layouts.app')

@section('title', 'Edit Room Type')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Room Type</h1>
            <p class="text-gray-500">Update details for {{ $tipeKamar->nama_tipe }}.</p>
        </div>
        <a href="{{ route('tipe-kamar.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    <x-card>
        <form action="{{ route('tipe-kamar.update', $tipeKamar) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="nama_tipe" class="text-sm font-bold text-gray-700">Type Name</label>
                        <input type="text" name="nama_tipe" id="nama_tipe" value="{{ old('nama_tipe', $tipeKamar->nama_tipe) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        @error('nama_tipe') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="harga_per_malam" class="text-sm font-bold text-gray-700">Price per Night (Rp)</label>
                        <input type="number" name="harga_per_malam" id="harga_per_malam" value="{{ old('harga_per_malam', $tipeKamar->harga_per_malam) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        @error('harga_per_malam') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="kapasitas" class="text-sm font-bold text-gray-700">Capacity (Persons)</label>
                        <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', $tipeKamar->kapasitas) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        @error('kapasitas') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="fasilitas" class="text-sm font-bold text-gray-700">Facilities (Comma separated)</label>
                        @php
                            $currentFasilitas = is_array($tipeKamar->fasilitas) ? implode(',', $tipeKamar->fasilitas) : $tipeKamar->fasilitas;
                        @endphp
                        <input type="text" name="fasilitas" id="fasilitas" value="{{ old('fasilitas', $currentFasilitas) }}"
                            placeholder="WiFi, AC, TV"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <p class="text-xs text-gray-400">Separate facilities with commas.</p>
                        @error('fasilitas') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="deskripsi" class="text-sm font-bold text-gray-700">Description</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('deskripsi', $tipeKamar->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50">
                <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">
                    Update Room Type
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
