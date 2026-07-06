@extends('layouts.app')

@section('title', 'Edit Reservation')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css" id="flatpickr-dark-theme" disabled>
<style>
    .ts-control { border-radius: 0.75rem !important; padding: 0.6rem 1rem !important; }
    .dark .ts-control { background-color: #1f2937 !important; color: white !important; border-color: #374151 !important; }
    .dark .ts-dropdown { background-color: #1f2937 !important; color: white !important; border-color: #374151 !important; }
    .dark .ts-dropdown .option,
    .dark .ts-dropdown .item,
    .dark .ts-dropdown .optgroup-header,
    .dark .ts-dropdown .no-results,
    .dark .ts-dropdown .create {
        background-color: #1f2937 !important;
        color: white !important;
    }
    .dark .ts-dropdown .active { background-color: #374151 !important; color: white !important; }
    .room-card.selected { border-color: #0ea5e9; ring: 4px; ring-color: #0ea5e9/20; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Edit Booking #{{ $booking->kode_booking }}</h1>
            <p class="text-gray-500 dark:text-gray-400">Modify reservation details and room assignments.</p>
        </div>
        <a href="{{ route('booking.show', $booking) }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span>Back to Details</span>
        </a>
    </div>

    <form action="{{ route('booking.update', $booking) }}" method="POST" id="bookingForm">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Form Section --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Reservation Settings</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Check-in</label>
                            <input type="text" name="tanggal_checkin" id="tanggal_checkin" value="{{ $booking->tanggal_checkin->format('Y-m-d') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all datepicker" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Check-out</label>
                            <input type="text" name="tanggal_checkout" id="tanggal_checkout" value="{{ $booking->tanggal_checkout->format('Y-m-d') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all datepicker" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Booking Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Guest Count</label>
                            <input type="number" name="jumlah_tamu" value="{{ $booking->jumlah_tamu }}" min="1"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none" required>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600">
                                <i data-lucide="door-open" class="w-5 h-5"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Adjust Room Assignment</h2>
                        </div>
                        <span id="roomsFound" class="text-sm text-gray-500 font-medium">Loading rooms...</span>
                    </div>

                    <div id="roomContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
                    <div id="selectedRoomsInputs"></div>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="lg:col-span-4">
                <div class="sticky top-8 space-y-6">
                    <div class="bg-gray-900 dark:bg-gray-800 text-white p-8 rounded-3xl shadow-2xl">
                        <h3 class="text-xl font-bold mb-8">Summary of Changes</h3>

                        <div class="space-y-6">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Stay Duration</span>
                                <span id="sumDuration" class="font-bold">0 Nights</span>
                            </div>

                            <div class="space-y-3" id="sumRoomList"></div>

                            <div class="border-t border-gray-700 pt-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-400 text-sm">New Subtotal</span>
                                    <span id="sumSubtotal" class="font-bold">Rp 0</span>
                                </div>
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Down Payment (DP)</label>
                                    <input type="number" name="uang_muka" id="uang_muka" value="{{ round($booking->uang_muka) }}"
                                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 outline-none">
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-700">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Total Price</p>
                                <p id="sumTotal" class="text-3xl font-black text-primary-500">Rp 0</p>
                                <div class="mt-4 flex justify-between text-sm">
                                    <span class="text-gray-400">Remaining</span>
                                    <span id="sumRemaining" class="font-bold text-amber-500">Rp 0</span>
                                </div>
                            </div>

                            <div class="space-y-4 pt-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-400">Notes</label>
                                    <textarea name="catatan" rows="2" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-300 outline-none focus:ring-1 focus:ring-primary-500">{{ $booking->catatan }}</textarea>
                                </div>
                                <button type="submit" id="submitBtn" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-primary-500/20 flex items-center justify-center gap-3">
                                    <span>Save Changes</span>
                                    <i data-lucide="save" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkinInput = document.getElementById('tanggal_checkin');
        const checkoutInput = document.getElementById('tanggal_checkout');
        const dpInput = document.getElementById('uang_muka');

        // Initialize Flatpickr for ID format
        const fpConfig = {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "id",
            onChange: function() {
                fetchRooms();
                updateSummary();
            }
        };
        flatpickr("#tanggal_checkin", fpConfig);
        flatpickr("#tanggal_checkout", fpConfig);

        let allRooms = [];
        let selectedRoomIds = new Set(@json($booking->kamar->pluck('id')->map(fn($id) => (string)$id)->toArray()));

        function fetchRooms() {
            const ci = checkinInput.value;
            const co = checkoutInput.value;
            if (!ci || !co) return;

            fetch(`{{ route('booking.create') }}?checkin=${ci}&checkout=${co}&exclude_booking_id={{ $booking->id }}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                allRooms = data;
                renderRooms();
                updateSummary();
            })
            .catch(err => console.error('Fetch error:', err));
        }

        function renderRooms() {
            const container = document.getElementById('roomContainer');
            const roomsFound = document.getElementById('roomsFound');
            container.innerHTML = '';
            roomsFound.textContent = `${allRooms.length} rooms available`;

            allRooms.forEach(room => {
                const isSelected = selectedRoomIds.has(String(room.id));

                const card = `
                    <div class="room-card group cursor-pointer bg-white dark:bg-gray-800 border-2 rounded-2xl p-5 transition-all hover:shadow-lg ${isSelected ? 'border-primary-500 ring-4 ring-primary-500/10' : 'border-gray-100 dark:border-gray-700'}"
                         onclick="toggleRoom('${room.id}')">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white">Room ${room.nomor_kamar}</h3>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-widest">${room.tipe_kamar.nama_tipe} • Floor ${room.lantai}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-primary-600 font-bold">Rp ${new Intl.NumberFormat('id-ID').format(room.tipe_kamar.harga_per_malam)}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">per night</p>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', card);
            });
            lucide.createIcons();
        }

        window.toggleRoom = function(id) {
            id = String(id);
            if (selectedRoomIds.has(id)) {
                selectedRoomIds.delete(id);
            } else {
                selectedRoomIds.add(id);
            }
            renderRooms();
            updateSummary();
            updateHiddenInputs();
        };

        function updateHiddenInputs() {
            const container = document.getElementById('selectedRoomsInputs');
            container.innerHTML = '';
            selectedRoomIds.forEach(id => {
                container.insertAdjacentHTML('beforeend', `<input type="hidden" name="kamar_ids[]" value="${id}">`);
            });
        }

        function updateSummary() {
            const ciValue = checkinInput.value;
            const coValue = checkoutInput.value;

            let duration = 1;
            if (ciValue && coValue) {
                const ci = new Date(ciValue);
                const co = new Date(coValue);
                ci.setHours(0,0,0,0);
                co.setHours(0,0,0,0);

                const diff = co - ci;
                let nights = Math.round(diff / (1000 * 60 * 60 * 24));
                duration = Math.max(1, nights);
            }

            document.getElementById('sumDuration').textContent = `${duration} Nights`;

            const sumRoomList = document.getElementById('sumRoomList');
            sumRoomList.innerHTML = '';
            let subtotal = 0;
            selectedRoomIds.forEach(id => {
                const room = allRooms.find(r => String(r.id) === id);
                if (room) {
                    const price = parseFloat(room.tipe_kamar.harga_per_malam);
                    subtotal += price * duration;
                    sumRoomList.insertAdjacentHTML('beforeend', `
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">Room ${room.nomor_kamar} x ${duration}</span>
                            <span class="text-white">Rp ${new Intl.NumberFormat('id-ID').format(price * duration)}</span>
                        </div>
                    `);
                }
            });

            const dp = parseFloat(dpInput.value) || 0;
            document.getElementById('sumSubtotal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
            document.getElementById('sumTotal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
            document.getElementById('sumRemaining').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(Math.max(0, subtotal - dp))}`;

            document.getElementById('submitBtn').disabled = selectedRoomIds.size === 0;
            document.getElementById('submitBtn').style.opacity = (selectedRoomIds.size === 0) ? '0.5' : '1';
        }

        checkinInput.addEventListener('change', fetchRooms);
        checkoutInput.addEventListener('change', fetchRooms);
        dpInput.addEventListener('input', updateSummary);

        fetchRooms();
        updateSummary();
        updateHiddenInputs();
    });
</script>
@endpush
