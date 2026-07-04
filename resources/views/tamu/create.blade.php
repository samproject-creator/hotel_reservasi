@extends('layouts.app')

@section('title', 'Add New Guest')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Onboard New Client</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Create a new guest record in the LuxeHotel system.</p>
        </div>
        <a href="{{ route('tamu.index') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 font-bold transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to Registry
        </a>
    </div>

    <x-card>
        <form action="{{ route('tamu.store') }}" method="POST" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="nama_lengkap" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Full Legal Name</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('nama_lengkap') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="nik" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">National ID (NIK)</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('nik') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
                    @error('email') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="no_hp" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">WhatsApp / Phone</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                        placeholder="08123456789"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('no_hp') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="jenis_kelamin" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Gender</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                        <option value="">Select Gender</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (Male)</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (Female)</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="tanggal_lahir" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Date of Birth</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('tanggal_lahir') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="pekerjaan" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Occupation</label>
                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
                    @error('pekerjaan') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="kewarganegaraan" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nationality</label>
                    <input type="text" name="kewarganegaraan" id="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('kewarganegaraan') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="alamat" class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Residential Address</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-8 border-t border-gray-50 dark:border-slate-800">
                <button type="submit" class="w-full py-4 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 transform active:scale-95 flex items-center justify-center gap-3">
                    <i data-lucide="save" class="w-5 h-5 text-accent-gold"></i>
                    Initialize Client Record
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
