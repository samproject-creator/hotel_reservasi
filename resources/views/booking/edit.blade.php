@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Booking</h1>
            <p class="text-gray-500">Update reservation details for #{{ $booking->kode_booking }}.</p>
        </div>
        <a href="{{ route('booking.show', $booking) }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to Details
        </a>
    </div>

    <x-card>
        <form action="{{ route('booking.update', $booking) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="status" class="text-sm font-bold text-gray-700">Booking Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                            <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="checkin" {{ old('status', $booking->status) == 'checkin' ? 'selected' : '' }}>Check-In</option>
                            <option value="checkout" {{ old('status', $booking->status) == 'checkout' ? 'selected' : '' }}>Check-Out</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="jumlah_tamu" class="text-sm font-bold text-gray-700">Number of Guests</label>
                        <input type="number" name="jumlah_tamu" id="jumlah_tamu" value="{{ old('jumlah_tamu', $booking->jumlah_tamu) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        @error('jumlah_tamu') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="uang_muka" class="text-sm font-bold text-gray-700">Down Payment (Rp)</label>
                        <input type="number" name="uang_muka" id="uang_muka" value="{{ old('uang_muka', $booking->uang_muka) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        @error('uang_muka') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="catatan" class="text-sm font-bold text-gray-700">Notes</label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('catatan', $booking->catatan) }}</textarea>
                    @error('catatan') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50">
                <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors">
                    Update Booking
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
