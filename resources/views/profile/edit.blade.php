@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Identity Management</h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Customize your administrative persona and security parameters.</p>
    </div>

    <x-card title="Personal Portfolio">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-8 pt-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Legal Identity</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Electronic Mail</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Mobile Contact</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" placeholder="+62...">
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3.5 bg-gray-900 dark:bg-slate-700 text-white font-black rounded-2xl hover:bg-black dark:hover:bg-slate-600 transition-all shadow-xl shadow-gray-900/10 uppercase text-xs tracking-widest">Update Portfolio</button>
            </div>
        </form>
    </x-card>

    <x-card title="Security Hardening">
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-8 pt-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Current Keyphrase</label>
                    <input type="password" name="current_password" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">New Keyphrase</label>
                    <input type="password" name="password" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Verify New Keyphrase</label>
                    <input type="password" name="password_confirmation" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none" required>
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3.5 bg-gray-900 dark:bg-slate-700 text-white font-black rounded-2xl hover:bg-black dark:hover:bg-slate-600 transition-all shadow-xl shadow-gray-900/10 uppercase text-xs tracking-widest">Execute Hardening</button>
            </div>
        </form>
    </x-card>
</div>
@endsection
