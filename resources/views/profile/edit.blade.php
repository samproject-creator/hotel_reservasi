@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Personal Information</h2>
            <p class="text-sm text-gray-500">Update your personal details and contact information.</p>
        </div>
        <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">Save Changes</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Change Password</h2>
            <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        <form action="{{ route('profile.password') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-sm" required>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-colors">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
