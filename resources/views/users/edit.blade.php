@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Modify Operator</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Updating authorization parameters for {{ $user->name }}.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-400 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-all border border-gray-200 dark:border-slate-700 flex items-center gap-2 text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Return
        </a>
    </div>

    <x-card>
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            {{-- SEKSI UTAMA: PROFIL OPERATOR --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nama Lengkap Staf  --}}
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Legal Identity</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('name') <p class="text-rose-500 text-[10px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email sebagai Identifikasi Utama Akses --}}
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Electronic Mail (Access Identifier)</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('email') <p class="text-rose-500 text-[10px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Otorisasi Hak Akses --}}
                <div class="space-y-2">
                    <label for="role" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Access Authorization</label>
                    <select name="role" id="role"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                        <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Operational Staff (Petugas)</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>System Administrator</option>
                    </select>
                    @error('role') <p class="text-rose-500 text-[10px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- SEKSI KEDUA: SECURITY KEYPHRASE (Dipisah agar Password & Verify Selalu Sejajar Berdampingan) --}}
            <div class="pt-6 border-t border-gray-100 dark:border-slate-800 grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Password Baru (Opsional) --}}
                <div class="space-y-2">
                    <label for="password" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Security Keyphrase <span class="lowercase font-medium opacity-50">(Optional)</span></label>
                    <input type="password" name="password" id="password"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="••••••••">
                    @error('password') <p class="text-rose-500 text-[10px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Verify Keyphrase</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="••••••••">
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-8 border-t border-gray-100 dark:border-slate-700">
                <button type="submit" class="w-full px-6 py-4 bg-gray-900 dark:bg-slate-700 text-white font-black rounded-2xl hover:bg-black dark:hover:bg-slate-600 transition-all shadow-xl shadow-gray-900/10 uppercase text-xs tracking-widest">
                    Synchronize Operator Profile
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection