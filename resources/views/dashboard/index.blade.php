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
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <div class="text-sm text-gray-500">{{ now()->format('l, d F Y') }}</div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                <i data-lucide="door-open" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Available Rooms</p>
                <p class="text-2xl font-bold text-gray-900">{{ $kamarTersedia }} / {{ $totalKamar }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="bg-orange-50 p-3 rounded-xl text-orange-600">
                <i data-lucide="calendar-days" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">New Bookings</p>
                <p class="text-2xl font-bold text-gray-900">{{ $bookingHariIni }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="bg-green-50 p-3 rounded-xl text-green-600">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Monthly Revenue</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="bg-purple-50 p-3 rounded-xl text-purple-600">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Guests</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalTamu }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Charts --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-gray-900 mb-4">Booking Trends</h2>
            <canvas id="bookingChart" height="200"></canvas>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-gray-900 mb-4">Revenue Growth</h2>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        {{-- Recent Bookings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Recent Bookings</h2>
                <a href="{{ route('booking.index') }}" class="text-sm text-primary-600 font-semibold hover:text-primary-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Guest</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookingTerbaru as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $booking->tamu->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->kode_booking }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                    {{ $booking->status === 'confirmed' ? 'bg-blue-50 text-blue-700' : '' }}
                                    {{ $booking->status === 'checkin' ? 'bg-orange-50 text-orange-700' : '' }}
                                    {{ $booking->status === 'checkout' ? 'bg-green-50 text-green-700' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-50 text-red-700' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
                                ">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions / Status Room --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('booking.create') }}" class="p-4 bg-primary-50 rounded-xl flex flex-col items-center gap-2 text-primary-700 hover:bg-primary-100 transition-colors">
                    <i data-lucide="plus-circle" class="w-8 h-8"></i>
                    <span class="text-sm font-bold">New Booking</span>
                </a>
                <a href="{{ route('tamu.create') }}" class="p-4 bg-green-50 rounded-xl flex flex-col items-center gap-2 text-green-700 hover:bg-green-100 transition-colors">
                    <i data-lucide="user-plus" class="w-8 h-8"></i>
                    <span class="text-sm font-bold">Add Guest</span>
                </a>
                <a href="{{ route('checkin.index') }}" class="p-4 bg-orange-50 rounded-xl flex flex-col items-center gap-2 text-orange-700 hover:bg-orange-100 transition-colors">
                    <i data-lucide="log-in" class="w-8 h-8"></i>
                    <span class="text-sm font-bold">Check-In</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="p-4 bg-purple-50 rounded-xl flex flex-col items-center gap-2 text-purple-700 hover:bg-purple-100 transition-colors">
                    <i data-lucide="file-bar-chart" class="w-8 h-8"></i>
                    <span class="text-sm font-bold">Reports</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
