@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Core Configurations</h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Fine-tune LuxeHotel's operational parameters and integration hooks.</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-8">
        @csrf

        <x-card title="Corporate Identity">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Establishment Name</label>
                    <input type="text" name="settings[hotel_name]" value="{{ \App\Models\Setting::get('hotel_name', 'LuxeHotel Premium') }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Official Correspondence Email</label>
                    <input type="email" name="settings[hotel_email]" value="{{ \App\Models\Setting::get('hotel_email', 'contact@luxehotel.com') }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Primary Contact Line</label>
                    <input type="text" name="settings[hotel_phone]" value="{{ \App\Models\Setting::get('hotel_phone', '+62 812 3456 7890') }}" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Physical Domicile Address</label>
                    <textarea name="settings[hotel_address]" rows="3" class="w-full px-5 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none">{{ \App\Models\Setting::get('hotel_address', 'Jl. Kemewahan No. 1, Jakarta') }}</textarea>
                </div>
            </div>
        </x-card>
        <x-card title="External Integration Hooks">
            <div class="pt-4 space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">Fonnte API Authorization Token (WhatsApp)</label>
                    <div class="relative group">
                        <input type="password" name="settings[fonnte_token]" value="{{ \App\Models\Setting::get('fonnte_token') }}" class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white transition-all outline-none pr-12 font-mono" placeholder="********************************">
                        <div class="absolute inset-y-0 right-4 flex items-center text-gray-400">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 font-medium">Used for automated booking confirmations and check-in alerts via WhatsApp.</p>
                </div>
            </div>
        </x-card>

        <div class="flex items-center justify-between p-8 bg-gray-900 dark:bg-slate-700 rounded-3xl shadow-xl shadow-gray-900/10">
            <div>
                <p class="text-white font-black uppercase text-xs tracking-widest">Commit Changes</p>
                <p class="text-gray-400 text-xs font-medium">Apply all configurations to the production environment.</p>
            </div>
            <button type="submit" class="px-8 py-4 bg-accent-gold text-gray-900 font-black rounded-2xl hover:bg-white transition-all uppercase text-xs tracking-widest shadow-lg">
                Synchronize Core
            </button>
        </div>
    </form>
</div>
@endsection
