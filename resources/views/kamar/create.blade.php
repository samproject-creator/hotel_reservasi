@extends('layouts.app')

@section('title', 'Add New Room')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Register Accommodation</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Define a new physical unit within the LuxeHotel premises.</p>
        </div>
        <a href="{{ route('kamar.index') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 font-bold transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to Units
        </a>
    </div>

    <x-card>
        <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="nomor_kamar" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Unit Number</label>
                    <input type="text" name="nomor_kamar" id="nomor_kamar" value="{{ old('nomor_kamar') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('nomor_kamar') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="tipe_kamar_id" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Classification</label>
                    <select name="tipe_kamar_id" id="tipe_kamar_id"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                        <option value="">Select Type</option>
                        @foreach($tipekamar as $tipe)
                            <option value="{{ $tipe->id }}" {{ old('tipe_kamar_id') == $tipe->id ? 'selected' : '' }}>
                                {{ $tipe->nama_tipe }} (Rp {{ number_format($tipe->harga_per_malam) }})
                            </option>
                        @endforeach
                    </select>
                    @error('tipe_kamar_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="lantai" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Floor Level</label>
                    <input type="number" name="lantai" id="lantai" value="{{ old('lantai') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('lantai') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="status" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Initial Status</label>
                    <select name="status" id="status"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia (Available)</option>
                        <option value="ditempati" {{ old('status') == 'ditempati' ? 'selected' : '' }}>Ditempati (Occupied)</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="images" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Portfolio Images</label>
                    <div class="p-8 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-3xl text-center group hover:border-primary-500 transition-colors bg-gray-50/50 dark:bg-slate-800/50">
                        <input type="file" name="images[]" id="images" multiple class="hidden">
                        <label for="images" class="cursor-pointer block">
                            <i data-lucide="upload-cloud" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4 group-hover:text-primary-500 transition-colors"></i>
                            <p id="upload-text" class="text-sm font-bold text-gray-600 dark:text-gray-300">Click to upload room imagery</p>
                            <p id="upload-info" class="text-xs text-gray-400 mt-1">Professional JPG or PNG formats up to 2MB each.</p>
                        </label>
                        
                        <div id="preview" class="grid grid-cols-3 gap-4 mt-4"></div>
                    </div>
                    @error('images.*') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="keterangan" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Detailed Specifications</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-8 border-t border-gray-50 dark:border-slate-800">
                <button type="submit" class="w-full py-4 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 transform active:scale-95 flex items-center justify-center gap-3">
                    <i data-lucide="save" class="w-5 h-5 text-accent-gold"></i>
                    Save Unit Profile
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('images');
    const text = document.getElementById('upload-text');
    const info = document.getElementById('upload-info');
    const preview = document.getElementById('preview');

    input.addEventListener('change', function () {
        preview.innerHTML = ''; // Reset preview area

        if (this.files.length) {
            // 1. Ubah text & deskripsi file terpilih
            text.innerHTML = this.files.length + " file(s) selected";
            info.innerHTML = [...this.files].map(f => f.name).join("<br>");

            // 2. Generate thumbnail preview gambar
            [...this.files].forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.innerHTML += `
                            <img src="${e.target.result}" class="w-full h-32 object-cover rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            // Kembalikan ke text awal jika batal memilih
            text.innerHTML = "Click to upload room imagery";
            info.innerHTML = "Professional JPG or PNG formats up to 2MB each.";
        }
    });
});
</script>
@endpush