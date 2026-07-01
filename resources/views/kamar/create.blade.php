@extends('layouts.app')

@section('title', 'Add New Room')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Room</h1>
            <p class="text-gray-500">Create a new room in the system.</p>
        </div>
        <a href="{{ route('kamar.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    <x-card>
        <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nomor_kamar" class="text-sm font-bold text-gray-700">Room Number</label>
                    <input type="text" name="nomor_kamar" id="nomor_kamar" value="{{ old('nomor_kamar') }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    @error('nomor_kamar') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="tipe_kamar_id" class="text-sm font-bold text-gray-700">Room Type</label>
                    <select name="tipe_kamar_id" id="tipe_kamar_id"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        <option value="">Select Type</option>
                        @foreach($tipeKamars as $tipe)
                            <option value="{{ $tipe->id }}" {{ old('tipe_kamar_id') == $tipe->id ? 'selected' : '' }}>
                                {{ $tipe->nama_tipe }} (Rp {{ number_format($tipe->harga_per_malam) }})
                            </option>
                        @endforeach
                    </select>
                    @error('tipe_kamar_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="lantai" class="text-sm font-bold text-gray-700">Floor</label>
                    <input type="number" name="lantai" id="lantai" value="{{ old('lantai') }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    @error('lantai') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="status" class="text-sm font-bold text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="ditempati" {{ old('status') == 'ditempati' ? 'selected' : '' }}>Ditempati (Occupied)</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="images" class="text-sm font-bold text-gray-700">Room Images (Multiple)</label>
                    <input type="file" name="images[]" id="images" multiple
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500">Select one or more images (JPG, PNG, max 2MB each).</p>
                    @error('images.*') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="keterangan" class="text-sm font-bold text-gray-700">Description / Notes</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50">
                <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">
                    Save Room
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
