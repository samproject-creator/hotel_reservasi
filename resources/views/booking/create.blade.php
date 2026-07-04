@extends('layouts.app')

@section('title', 'New Reservation')

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
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">New Reservation</h1>
            <p class="text-gray-500 dark:text-gray-400">Create a premium booking experience for your guests.</p>
        </div>
        <a href="{{ route('booking.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span>Back to Bookings</span>
        </a>
    </div>

    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Form Section --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- 1. Date & Guest Selection --}}
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Guest Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Select Guest</label>
                            <select id="tamu_select" name="tamu_id" placeholder="Search by name, NIK, or phone..." required>
                                <option value=""></option>
                                @foreach($tamus as $tamu)
                                    <option value="{{ $tamu->id }}"
                                            data-nik="{{ $tamu->nik }}"
                                            data-phone="{{ $tamu->no_hp }}"
                                            data-email="{{ $tamu->email }}">
                                        {{ $tamu->nama_lengkap }} ({{ $tamu->nik }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex justify-end">
                                <a href="{{ route('tamu.create') }}" class="text-xs text-primary-600 hover:underline flex items-center gap-1 font-bold">
                                    <i data-lucide="plus-circle" class="w-3 h-3 text-accent-gold"></i> Add New Guest
                                </a>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Check-in</label>
                            <input type="text" name="tanggal_checkin" id="tanggal_checkin" value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all datepicker" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Check-out</label>
                            <input type="text" name="tanggal_checkout" id="tanggal_checkout" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all datepicker" required>
                        </div>
                    </div>
                </div>

                {{-- 2. Room Selection Cards --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600">
                                <i data-lucide="door-open" class="w-5 h-5"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Choose Your Rooms</h2>
                        </div>
                        <span id="roomsFound" class="text-sm text-gray-500 font-medium">0 rooms available</span>
                    </div>

                    <div id="roomContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Room cards will be rendered here by JS --}}
                    </div>

                    {{-- Hidden input for selected rooms --}}
                    <div id="selectedRoomsInputs"></div>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="lg:col-span-4">
                <div class="sticky top-8 space-y-6">
                    <div class="bg-gray-900 dark:bg-gray-800 text-white p-8 rounded-3xl shadow-2xl">
                        <h3 class="text-xl font-bold mb-8">Booking Summary</h3>

                        <div class="space-y-6">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Stay Duration</span>
                                <span id="sumDuration" class="font-bold">0 Nights</span>
                            </div>

                            <div class="space-y-3" id="sumRoomList">
                                {{-- Selected rooms list --}}
                            </div>

                            <div class="border-t border-gray-700 pt-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-400 text-sm">Subtotal</span>
                                    <span id="sumSubtotal" class="font-bold">Rp 0</span>
                                </div>
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Down Payment (DP)</label>
                                    <input type="number" name="uang_muka" id="uang_muka" value="0"
                                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 transition-all outline-none">
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-700">
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Total Price</p>
                                        <p id="sumTotal" class="text-3xl font-black text-primary-500">Rp 0</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between text-sm">
                                    <span class="text-gray-400">Remaining</span>
                                    <span id="sumRemaining" class="font-bold text-amber-500">Rp 0</span>
                                </div>
                            </div>

                            <div class="space-y-4 pt-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-400">Number of Guests</label>
                                    <input type="number" name="jumlah_tamu" value="2" min="1"
                                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-400">Additional Notes</label>
                                    <textarea name="catatan" rows="2" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-300 outline-none focus:ring-1 focus:ring-primary-500"></textarea>
                                </div>
                                <button type="submit" id="submitBtn" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-2xl transition-all transform active:scale-95 shadow-xl shadow-primary-500/20 flex items-center justify-center gap-3">
                                    <span>Confirm Reservation</span>
                                    <i data-lucide="chevron-right" class="w-5 h-5 text-accent-gold"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Help Card --}}
                    <div class="p-6 bg-primary-50 dark:bg-primary-900/10 border border-primary-100 dark:border-primary-900/30 rounded-3xl">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-primary-600 shrink-0">
                                <i data-lucide="info" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-primary-900 dark:text-primary-400">Need Help?</p>
                                <p class="text-xs text-primary-700 dark:text-primary-500/70 mt-1 leading-relaxed">Select your stay dates first to see live room availability and pricing.</p>
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
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
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

        // Initialize TomSelect for Guests with extended search
        new TomSelect('#tamu_select', {
            create: false,
            valueField: 'value',
            labelField: 'text',
            searchField: ['text', 'nik', 'phone', 'email'],
            options: Array.from(document.querySelectorAll('#tamu_select option')).map(opt => ({
                value: opt.value,
                text: opt.text,
                nik: opt.dataset.nik,
                phone: opt.dataset.phone,
                email: opt.dataset.email
            })),
            render: {
                option: function(data, escape) {
                    return `<div>
                        <div class="font-bold">${escape(data.text)}</div>
                        <div class="text-xs text-gray-400">${escape(data.nik)} • ${escape(data.phone)}</div>
                    </div>`;
                },
                item: function(data, escape) {
                    return `<div>${escape(data.text)}</div>`;
                }
            }
        });

        let allRooms = [];
        let selectedRoomIds = new Set();

        function fetchRooms() {
            const ci = checkinInput.value;
            const co = checkoutInput.value;
            if (!ci || !co) return;

            fetch(`{{ route('booking.create') }}?checkin=${ci}&checkout=${co}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                allRooms = data;
                renderRooms();
                updateSummary();
            });
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

                        <div class="flex flex-wrap gap-2">
                            <span class="flex items-center gap-1.5 px-2 py-1 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-[10px] font-bold text-gray-600 dark:text-gray-300">
                                <i data-lucide="users" class="w-3 h-3 text-accent-gold"></i> ${room.tipe_kamar.kapasitas} Pax
                            </span>
                            ${room.tipe_kamar.fasilitas ? room.tipe_kamar.fasilitas.slice(0, 2).map(f => `
                                <span class="flex items-center gap-1.5 px-2 py-1 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-[10px] font-bold text-gray-600 dark:text-gray-300">
                                    <i data-lucide="check" class="w-3 h-3 text-primary-500"></i> ${f}
                                </span>
                            `).join('') : ''}
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
                if (nights > 0) duration = nights;
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
                        <div class="flex justify-between text-xs animate-fade-in">
                            <span class="text-gray-400">Room ${room.nomor_kamar} x ${duration}</span>
                            <span class="text-white font-medium">Rp ${new Intl.NumberFormat('id-ID').format(price * duration)}</span>
                        </div>
                    `);
                }
            });

            const dp = parseFloat(dpInput.value) || 0;
            const remaining = subtotal - dp;

            document.getElementById('sumSubtotal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
            document.getElementById('sumTotal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
            document.getElementById('sumRemaining').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(remaining < 0 ? 0 : remaining)}`;

            // Validation visuals
            if (dp > subtotal && subtotal > 0) {
                dpInput.classList.add('border-red-500', 'text-red-500');
            } else {
                dpInput.classList.remove('border-red-500', 'text-red-500');
            }

            document.getElementById('submitBtn').disabled = selectedRoomIds.size === 0 || (dp > subtotal && subtotal > 0);
            document.getElementById('submitBtn').style.opacity = (selectedRoomIds.size === 0) ? '0.5' : '1';
        }

        checkinInput.addEventListener('change', fetchRooms);
        checkoutInput.addEventListener('change', fetchRooms);
        dpInput.addEventListener('input', updateSummary);

        fetchRooms();
    });
</script>
@endpush
