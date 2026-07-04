@extends('layouts.app')

@section('title', 'Dashboard')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxBooking = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctxBooking, {
            type: 'line',
            data: {
                labels: {!! json_encode($grafikBooking->pluck('bulan')) !!},
                datasets: [{
                    label: 'Bookings',
                    data: {!! json_encode($grafikBooking->pluck('total')) !!},
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: {!! json_encode($grafikPendapatan->pluck('bulan')) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($grafikPendapatan->pluck('total')) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });
</script>
@endpush

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Dashboard Overview</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Welcome back to LuxeHotel Management System.</p>
        </div>
        <div class="px-4 py-2 bg-white dark:bg-slate-850 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 text-sm font-bold text-gray-600 dark:text-gray-400 flex items-center gap-2">
            <i data-lucide="calendar" class="w-4 h-4 text-accent-gold"></i>
            {{ now()->format('l, d F Y') }}
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-850 p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-slate-800 flex flex-col gap-4 relative overflow-hidden group">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-2xl text-blue-600 w-fit group-hover:scale-110 transition-transform">
                <i data-lucide="door-open" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Available Rooms</p>
                <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ $kamarTersedia }} <span class="text-lg text-gray-400 font-bold">/ {{ $totalKamar }}</span></p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i data-lucide="door-open" class="w-32 h-32"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-850 p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-slate-800 flex flex-col gap-4 relative overflow-hidden group">
            <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-2xl text-orange-600 w-fit group-hover:scale-110 transition-transform">
                <i data-lucide="calendar-days" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">New Bookings</p>
                <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ $bookingHariIni }}</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i data-lucide="calendar-days" class="w-32 h-32"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-850 p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-slate-800 flex flex-col gap-4 relative overflow-hidden group">
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-2xl text-green-600 w-fit group-hover:scale-110 transition-transform">
                <i data-lucide="wallet" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Monthly Revenue</p>
                <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i data-lucide="wallet" class="w-32 h-32"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-850 p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-slate-800 flex flex-col gap-4 relative overflow-hidden group">
            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-2xl text-purple-600 w-fit group-hover:scale-110 transition-transform">
                <i data-lucide="users" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total Guests</p>
                <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ $totalTamu }}</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i data-lucide="users" class="w-32 h-32"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Charts --}}
        <x-card title="Booking Trends">
            <canvas id="bookingChart" height="200"></canvas>
        </x-card>
        <x-card title="Revenue Growth">
            <canvas id="revenueChart" height="200"></canvas>
        </x-card>

        {{-- Recent Bookings --}}
        <x-card title="Recent Bookings">
            <x-slot name="action">
                <a href="{{ route('booking.index') }}" class="text-xs font-black text-primary-600 uppercase tracking-widest hover:underline">View All</a>
            </x-slot>
            <div class="overflow-x-auto -mx-8 -mb-8">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Guest</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Date</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @foreach($bookingTerbaru as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-8 py-5">
                                <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $booking->tamu->nama_lengkap }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold mt-0.5 tracking-wider uppercase">{{ $booking->kode_booking }}</p>
                            </td>
                            <td class="px-8 py-5 text-xs font-bold text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M') }}
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    {{ $booking->status === 'confirmed' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : '' }}
                                    {{ $booking->status === 'checkin' ? 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400' : '' }}
                                    {{ $booking->status === 'checkout' ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-500' : '' }}
                                ">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        {{-- Quick Actions --}}
        <x-card title="Management Hub">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('booking.create') }}" class="group p-6 bg-primary-50 dark:bg-blue-900/10 rounded-3xl flex flex-col items-center gap-3 text-primary-700 dark:text-blue-400 hover:bg-primary-600 hover:text-white transition-all">
                    <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl group-hover:bg-primary-500 group-hover:text-white transition-colors shadow-sm">
                        <i data-lucide="plus-circle" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest">New Booking</span>
                </a>
                <a href="{{ route('tamu.create') }}" class="group p-6 bg-green-50 dark:bg-green-900/10 rounded-3xl flex flex-col items-center gap-3 text-green-700 dark:text-green-400 hover:bg-green-600 hover:text-white transition-all">
                    <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl group-hover:bg-green-500 group-hover:text-white transition-colors shadow-sm">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest">Add Guest</span>
                </a>
                <a href="{{ route('checkin.index') }}" class="group p-6 bg-orange-50 dark:bg-orange-900/10 rounded-3xl flex flex-col items-center gap-3 text-orange-700 dark:text-orange-400 hover:bg-orange-600 hover:text-white transition-all">
                    <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl group-hover:bg-orange-500 group-hover:text-white transition-colors shadow-sm">
                        <i data-lucide="log-in" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest">Check-In</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="group p-6 bg-purple-50 dark:bg-purple-900/10 rounded-3xl flex flex-col items-center gap-3 text-purple-700 dark:text-purple-400 hover:bg-purple-600 hover:text-white transition-all">
                    <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl group-hover:bg-purple-500 group-hover:text-white transition-colors shadow-sm">
                        <i data-lucide="file-bar-chart" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest">Reports</span>
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection
