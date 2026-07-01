@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">General Settings</h2>
                <p class="text-sm text-gray-500">Configure basic hotel information.</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hotel Name</label>
                        <input type="text" name="settings[hotel_name]" value="{{ \App\Models\Setting::get('hotel_name', 'LuxeHotel Premium') }}" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                        <input type="email" name="settings[hotel_email]" value="{{ \App\Models\Setting::get('hotel_email', 'contact@luxehotel.com') }}" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="settings[hotel_phone]" value="{{ \App\Models\Setting::get('hotel_phone', '+62 812 3456 7890') }}" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="settings[hotel_address]" rows="3" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm">{{ \App\Models\Setting::get('hotel_address', 'Jl. Kemewahan No. 1, Jakarta') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Integrations</h2>
                <p class="text-sm text-gray-500">Configure third-party services.</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fonnte API Token (WhatsApp)</label>
                    <input type="password" name="settings[fonnte_token]" value="{{ \App\Models\Setting::get('fonnte_token') }}" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm" placeholder="Paste your Fonnte token here">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">
                Save All Settings
            </button>
        </div>
    </form>
</div>
@endsection
