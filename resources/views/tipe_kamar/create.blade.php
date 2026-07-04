@extends('layouts.app')

@section('title', 'Add Room Type')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Define Suite Class</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Create a new premium room category for LuxeHotel.</p>
        </div>
        <a href="{{ route('tipe-kamar.index') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 font-bold transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to Classes
        </a>
    </div>

    <x-card>
        <form action="{{ route('tipe-kamar.store') }}" method="POST" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="nama_tipe" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Classification Name</label>
                    <input type="text" name="nama_tipe" id="nama_tipe" value="{{ old('nama_tipe') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="e.g. Imperial Suite" required>
                    @error('nama_tipe') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="harga_per_malam" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Daily Rate (IDR)</label>
                    <input type="number" name="harga_per_malam" id="harga_per_malam" value="{{ old('harga_per_malam') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="0" required>
                    @error('harga_per_malam') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="kapasitas" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Max Occupancy</label>
                    <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="Persons" required>
                    @error('kapasitas') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="fasilitas" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">In-Suite Amenities</label>
                    <input type="text" name="fasilitas" id="fasilitas" value="{{ is_array(old('fasilitas')) ? implode(',', old('fasilitas')) : old('fasilitas') }}"
                        placeholder="WiFi, AC, Minibar, Smart TV"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
                    <p class="text-[10px] text-gray-400 mt-2 italic">Separated by commas for registry entry.</p>
                    @error('fasilitas') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="deskripsi" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Marketing Description</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="Compose an elegant description of this unit class...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-8 border-t border-gray-50 dark:border-slate-800">
                <button type="submit" class="w-full py-4 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 transform active:scale-95 flex items-center justify-center gap-3 text-sm uppercase tracking-widest">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-accent-gold"></i>
                    Initialize Suite Classification
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
