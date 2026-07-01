@extends('layouts.app')

@section('title', 'Edit Guest')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Guest</h1>
            <p class="text-gray-500">Update details for {{ $tamu->nama_lengkap }}.</p>
        </div>
        <a href="{{ route('tamu.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    <x-card>
        <form action="{{ route('tamu.update', $tamu) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nama_lengkap" class="text-sm font-bold text-gray-700">Full Name</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $tamu->nama_lengkap) }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    @error('nama_lengkap') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="nik" class="text-sm font-bold text-gray-700">NIK (ID Number)</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik', $tamu->nik) }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    @error('nik') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $tamu->email) }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="no_hp" class="text-sm font-bold text-gray-700">Phone Number (WhatsApp)</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $tamu->no_hp) }}"
                        placeholder="08123456789"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                    @error('no_hp') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="alamat" class="text-sm font-bold text-gray-700">Address</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>{{ old('alamat', $tamu->alamat) }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50">
                <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">
                    Update Guest
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
