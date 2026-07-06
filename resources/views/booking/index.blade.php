@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Reservations</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Coordinate guest stays and manage booking lifecycles.</p>
        </div>
        <a href="{{ route('booking.create') }}" class="px-6 py-3 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 flex items-center gap-2 transform active:scale-95">
            <i data-lucide="calendar-plus" class="w-5 h-5 text-accent-gold"></i>
            New Reservation
        </a>
    </div>

    <x-card>
        {{-- FILTER BAR LUXEHOTEL --}}
        <form action="{{ route('booking.index') }}" method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-gray-50/50 dark:bg-slate-850 p-4 rounded-2xl border border-gray-100 dark:border-slate-800">
            {{-- Pencarian Kata Kunci --}}
            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Code or guest name..." 
                        class="w-full text-xs pl-3 pr-8 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 dark:text-white transition-all">
                    @if(request('search'))
                        <a href="{{ route('booking.index', request()->except('search')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-white text-xs">&times;</a>
                    @endif
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status</label>
                <select name="status" class="w-full text-xs px-3 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 dark:text-white transition-all">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="checkin" {{ request('status') === 'checkin' ? 'selected' : '' }}>Check In</option>
                    <option value="checkout" {{ request('status') === 'checkout' ? 'selected' : '' }}>Check Out</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Filter Rentang Tanggal Check-In Mulai --}}
            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Stay From</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="w-full text-xs px-3 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 dark:text-white transition-all">
            </div>

            {{-- Filter Rentang Tanggal Check-In Sampai --}}
            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Stay To</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="w-full text-xs px-3 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 dark:text-white transition-all">
            </div>

            {{-- Tombol Aksi Kendali --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-gray-900 dark:bg-slate-700 hover:bg-gray-800 text-white text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('booking.index') }}" class="py-2.5 px-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-xl transition-colors" title="Reset Filters">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Struktur Utama Tabel --}}
        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Patron</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Itinerary</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $booking->kode_booking }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $booking->tamu->nama_lengkap }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold mt-0.5 tracking-wider">{{ $booking->tamu->nik }}</p>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-700">
                                <span class="text-[10px] font-black text-gray-600 dark:text-gray-300">{{ $booking->tanggal_checkin->format('d M') }}</span>
                                <i data-lucide="arrow-right" class="w-3 h-3 text-gray-300"></i>
                                <span class="text-[10px] font-black text-gray-600 dark:text-gray-300">{{ $booking->tanggal_checkout->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $booking->status === 'checkin' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : '' }}
                                {{ $booking->status === 'checkout' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                {{ $booking->status === 'pending' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' : '' }}
                            ">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right relative z-10">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('booking.show', $booking) }}" class="p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-blue-900/20 rounded-xl transition-colors" title="Audit Reservation"><i data-lucide="file-search" class="w-5 h-5"></i></a>
                                
                                @can('cancel', $booking)
                                <form action="{{ route('booking.cancel', $booking) }}" method="POST" class="inline form-cancel">
                                    @csrf
                                    <button type="button" onclick="confirmCancel(this)" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-colors" title="Cancel Booking">
                                        <i data-lucide="calendar-x" class="w-5 h-5"></i>
                                    </button>
                                </form>
                                @endcan

                                @if($booking->status === 'cancelled' && auth()->user()->isAdmin())
                                <form action="{{ route('booking.destroy', $booking) }}" method="POST" class="inline form-delete">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Delete Permanent">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-sm text-gray-400 dark:text-gray-500 font-medium italic">
                            No reservations found matching the filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    </x-card>
</div>

{{-- SCRIPT POPUP PREMIUM (SWEETALERT2) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfigurasi dasar deteksi mode gelap agar popup otomatis menyesuaikan warna
    const isDarkMode = document.documentElement.classList.contains('dark');
    
    function confirmCancel(button) {
        Swal.fire({
            title: 'Cancel Reservation?',
            text: "Are you sure you want to cancel this booking? This action will release the assigned rooms.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d97706', // Warna amber premium
            cancelButtonColor: isDarkMode ? '#334155' : '#6b7280',
            confirmButtonText: 'Yes, Cancel It',
            background: isDarkMode ? '#1e293b' : '#ffffff',
            color: isDarkMode ? '#ffffff' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 dark:border-slate-800 shadow-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    function confirmDelete(button) {
        Swal.fire({
            title: 'Delete Record?',
            text: "This will permanently delete the reservation history. You cannot undo this action!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Warna merah destruktif
            cancelButtonColor: isDarkMode ? '#334155' : '#6b7280',
            confirmButtonText: 'Yes, Delete Permanently',
            background: isDarkMode ? '#1e293b' : '#ffffff',
            color: isDarkMode ? '#ffffff' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 dark:border-slate-800 shadow-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection