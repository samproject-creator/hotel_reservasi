@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Identity Management</h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">Customize your administrative persona and security parameters.</p>
    </div>

    {{-- CARD 1: PERSONAL PORTFOLIO --}}
    <x-card title="Personal Portfolio">
        <form action="{{ route('profile.update') }}" method="POST" class="pt-2">
            @csrf
            @method('PUT')
            
            {{-- Menggunakan gap-x-8 untuk jarak horizontal, gap-y-4 untuk jarak vertikal antar baris input --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                
                {{-- space-y-1 membuat jarak label ke input box menjadi sangat rapat --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Legal Identity</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('name') <p class="text-rose-500 text-[10px] font-bold uppercase mt-0.5">{{ $message }}</p> @enderror
                </div>
                
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Electronic Mail</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('email') <p class="text-rose-500 text-[10px] font-bold uppercase mt-0.5">{{ $message }}</p> @enderror
                </div>
                
                <div class="space-y-1 md:col-span-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Mobile Contact</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="+62...">
                    @error('no_hp') <p class="text-rose-500 text-[10px] font-bold uppercase mt-0.5">{{ $message }}</p> @enderror
                </div>
            </div>
            
            {{-- mt-6 memberikan jarak tombol yang proporsional ke atas --}}
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-8 py-3.5 bg-gray-900 dark:bg-slate-700 text-white font-black rounded-2xl hover:bg-black dark:hover:bg-slate-600 transition-all shadow-xl shadow-gray-900/10 uppercase text-xs tracking-widest">Update Portfolio</button>
            </div>
        </form>
    </x-card>

    {{-- CARD 2: SECURITY HARDENING (UBAH PASSWORD) --}}
    <x-card title="Security Hardening">
        <form action="{{ route('profile.password') }}" method="POST" class="pt-2">
            @csrf
            @method('PUT')
            
            {{-- Menggunakan susunan grid rapat gap-y-4 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                
                {{-- Current Keyphrase --}}
                <div class="space-y-1 md:col-span-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Current Keyphrase</label>
                    <input type="password" name="current_password" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('current_password') <p class="text-rose-500 text-[10px] font-bold uppercase mt-0.5">{{ $message }}</p> @enderror
                </div>
                
                {{-- New Keyphrase --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">New Keyphrase</label>
                    <input type="password" name="password" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                    @error('password') <p class="text-rose-500 text-[10px] font-bold uppercase mt-0.5">{{ $message }}</p> @enderror
                </div>
                
                {{-- Verify New Keyphrase --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Verify New Keyphrase</label>
                    <input type="password" name="password_confirmation" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-8 py-3.5 bg-gray-900 dark:bg-slate-700 text-white font-black rounded-2xl hover:bg-black dark:hover:bg-slate-600 transition-all shadow-xl shadow-gray-900/10 uppercase text-xs tracking-widest">Execute Hardening</button>
            </div>
        </form>
    </x-card>
</div>
@endsection