@extends('layouts.app')

@section('title', 'New Booking')

@section('extra_css')
<link href="https://cdn.jsdelivr.net/npm/slim-select@2.8.2/dist/slimselect.css" rel="stylesheet" />
<style>
    .ss-main { padding: 0.5rem; border-radius: 0.75rem; border-color: #e5e7eb; }
    .dark .ss-main { background-color: #1f2937; border-color: #374151; color: white; }
    .dark .ss-content { background-color: #1f2937; color: white; }
    .dark .ss-list .ss-option:hover { background-color: #374151; }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Booking</h1>
            <p class="text-gray-500 dark:text-gray-400">Reserve rooms for your guests.</p>
        </div>
        <a href="{{ route('booking.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to List
        </a>
    </div>

    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Details -->
            <div class="lg:col-span-2 space-y-6">
                <x-card>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="tanggal_checkin" class="text-sm font-bold text-gray-700 dark:text-gray-300">Check-in Date</label>
                                <input type="date" name="tanggal_checkin" id="tanggal_checkin" value="{{ old('tanggal_checkin', date('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-primary-500" required>
                                @error('tanggal_checkin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="tanggal_checkout" class="text-sm font-bold text-gray-700 dark:text-gray-300">Check-out Date</label>
                                <input type="date" name="tanggal_checkout" id="tanggal_checkout" value="{{ old('tanggal_checkout', date('Y-m-d', strtotime('+1 day'))) }}"
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-primary-500" required>
                                @error('tanggal_checkout') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="tamu_id" class="text-sm font-bold text-gray-700 dark:text-gray-300">Guest</label>
                            <select name="tamu_id" id="tamu_select" required>
                                <option data-placeholder="true"></option>
                                @foreach($tamus as $tamu)
                                    <option value="{{ $tamu->id }}" {{ old('tamu_id') == $tamu->id ? 'selected' : '' }}>
                                        {{ $tamu->nama_lengkap }} ({{ $tamu->nik }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('tamu.create') }}" class="text-xs text-primary-600 hover:underline flex items-center gap-1">
                                    <i data-lucide="plus-circle" class="w-3 h-3"></i> Add New Guest
                                </a>
                            </div>
                            @error('tamu_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="kamar_ids" class="text-sm font-bold text-gray-700 dark:text-gray-300">Select Rooms</label>
                            <select name="kamar_ids[]" id="kamar_select" multiple required>
                                @foreach($kamars as $kamar)
                                    <option value="{{ $kamar->id }}" {{ collect(old('kamar_ids'))->contains($kamar->id) ? 'selected' : '' }}>
                                        Room {{ $kamar->nomor_kamar }} - {{ $kamar->tipeKamar->nama_tipe }} (Rp {{ number_format($kamar->tipeKamar->harga_per_malam) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kamar_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-card>

                <x-card title="Additional Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="jumlah_tamu" class="text-sm font-bold text-gray-700 dark:text-gray-300">Number of Guests</label>
                            <input type="number" name="jumlah_tamu" id="jumlah_tamu" value="{{ old('jumlah_tamu', 1) }}" min="1"
                                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-primary-500" required>
                            @error('jumlah_tamu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="uang_muka" class="text-sm font-bold text-gray-700 dark:text-gray-300">Down Payment (DP)</label>
                            <input type="number" name="uang_muka" id="uang_muka" value="{{ old('uang_muka', 0) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-primary-500">
                            @error('uang_muka') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-6 space-y-2">
                        <label for="catatan" class="text-sm font-bold text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-primary-500">{{ old('catatan') }}</textarea>
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    <x-card title="Booking Summary">
                        <div id="summaryContent" class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Duration</span>
                                <span id="summaryDuration" class="font-bold text-gray-900 dark:text-white">0 Nights</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Rooms</span>
                                <span id="summaryRoomsCount" class="font-bold text-gray-900 dark:text-white">0 Selected</span>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-900 dark:text-white font-bold">Total Price</span>
                                    <span id="summaryTotalPrice" class="text-xl font-bold text-primary-600">Rp 0</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-500/20">
                            Confirm Booking
                        </button>
                    </x-card>

                    <div id="roomPreviews" class="space-y-4">
                        <!-- Room previews will be injected here -->
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/slim-select@2.8.2/dist/slimselect.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tamuSelect = new SlimSelect({
            select: '#tamu_select',
            settings: { placeholderText: 'Search for guest name or NIK...' }
        });

        const kamarSelect = new SlimSelect({
            select: '#kamar_select',
            settings: { placeholderText: 'Search for room number or type...' },
            events: {
                afterChange: (newVal) => {
                    updateSummary();
                    updateRoomPreviews();
                }
            }
        });

        const checkinInput = document.getElementById('tanggal_checkin');
        const checkoutInput = document.getElementById('tanggal_checkout');

        [checkinInput, checkoutInput].forEach(el => el.addEventListener('change', () => {
            fetchAvailableRooms();
            updateSummary();
        }));

        document.getElementById('uang_muka').addEventListener('input', updateSummary);

        const kamarsData = @json($kamars);

        function fetchAvailableRooms() {
            const checkin = checkinInput.value;
            const checkout = checkoutInput.value;
            if (!checkin || !checkout) return;

            fetch(`{{ route('booking.create') }}?checkin=${checkin}&checkout=${checkout}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                const currentSelection = Array.from(document.getElementById('kamar_select').selectedOptions).map(o => o.value);
                const options = data.map(k => ({
                    text: `Room ${k.nomor_kamar} - ${k.tipe_kamar.nama_tipe} (Rp ${new Intl.NumberFormat().format(k.tipe_kamar.harga_per_malam)})`,
                    value: k.id.toString(),
                    selected: currentSelection.includes(k.id.toString())
                }));
                kamarSelect.setData(options);
                updateSummary();
            });
        }

        function updateSummary() {
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);

            // Set both to midnight to avoid DST issues
            checkin.setHours(0, 0, 0, 0);
            checkout.setHours(0, 0, 0, 0);

            const diffTime = checkout - checkin;
            const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));

            const duration = isNaN(diffDays) || diffDays <= 0 ? 0 : diffDays;
            document.getElementById('summaryDuration').textContent = `${duration} Nights`;

            const selectedOptions = Array.from(document.getElementById('kamar_select').selectedOptions);
            document.getElementById('summaryRoomsCount').textContent = `${selectedOptions.length} Selected`;

            let totalPrice = 0;
            selectedOptions.forEach(option => {
                const roomId = option.value;
                const room = kamarsData.find(k => k.id == roomId);
                if (room) {
                    totalPrice += parseFloat(room.tipe_kamar.harga_per_malam) * duration;
                }
            });

            document.getElementById('summaryTotalPrice').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;

            // Real-time validation for DP
            const dpInput = document.getElementById('uang_muka');
            const dp = parseFloat(dpInput.value) || 0;
            if (dp > totalPrice) {
                dpInput.classList.add('border-red-500', 'ring-red-500');
            } else {
                dpInput.classList.remove('border-red-500', 'ring-red-500');
            }
        }

        function updateRoomPreviews() {
            const selectedRoomIds = Array.from(document.getElementById('kamar_select').selectedOptions).map(o => o.value);
            const container = document.getElementById('roomPreviews');
            container.innerHTML = '';

            selectedRoomIds.forEach(id => {
                const room = kamarsData.find(k => k.id == id);
                if (room) {
                    const imageUrl = room.images && room.images.length > 0
                        ? `/storage/${room.images[0]}`
                        : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';

                    const card = `
                        <div class="flex gap-4 p-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm animate-fade-in">
                            <img src="${imageUrl}" class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Room ${room.nomor_kamar}</p>
                                <p class="text-[10px] text-gray-500">${room.tipe_kamar.nama_tipe} • Max ${room.tipe_kamar.kapasitas} Pax</p>
                                <p class="text-xs font-bold text-primary-600 mt-1">Rp ${new Intl.NumberFormat('id-ID').format(room.tipe_kamar.harga_per_malam)}/night</p>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', card);
                }
            });
        }

        updateSummary();
    });
</script>
@endsection
