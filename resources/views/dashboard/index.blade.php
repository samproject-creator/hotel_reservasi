@extends('layouts.app')

@section('title', 'Dasbor Utama')
@section('page-title', 'Dasbor Kendali Sistem')

@section('content')
<div class="space-y-6">
    
    {{-- ── WELCOME BANNER ───────────────────────────────────── --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-purple-950/40 to-slate-900 border border-purple-900/40 p-6 rounded-lg shadow-lg">
        <div class="relative z-10">
            <h2 class="font-serif text-2xl text-purple-200 font-bold">Selamat Datang Kembali, {{ auth()->user()->name ?? 'Petugas' }}!</h2>
            <p class="text-sm text-slate-400 mt-1 max-w-2xl">
                Operasional hotel berjalan normal. Memantau ketersediaan kamar, riwayat check-in tamu, serta kurva finansial dari satu panel kendali.
            </p>
        </div>
    </div>

    {{-- ── 1. TOTAL DATA COUNTERS (METRICS SESUAI CONTROLLER) ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Total Tamu Terdaftar --}}
        <div class="bg-slate-900/60 border border-purple-950 p-5 rounded-lg flex items-center justify-between shadow-md hover:border-purple-800/40 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Tamu</p>
                <h3 class="text-2xl font-mono font-bold text-purple-300">{{ $totalTamu }}</h3>
                <p class="text-[11px] text-slate-500">Master entri pelanggan</p>
            </div>
            <div class="p-3 bg-purple-950/50 border border-purple-900/30 rounded text-purple-400">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Kamar Terisi (Ditempati) --}}
        <div class="bg-slate-900/60 border border-purple-950 p-5 rounded-lg flex items-center justify-between shadow-md hover:border-purple-800/40 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Kamar Ditempati</p>
                <h3 class="text-2xl font-mono font-bold text-emerald-400">
                    {{ $kamarDitempati }} <span class="text-xs text-slate-500 font-sans">/ {{ $totalKamar }} Kamar</span>
                </h3>
                <p class="text-[11px] text-emerald-500 font-medium">🏨 {{ $kamarTersedia }} Kamar Siap Huni</p>
            </div>
            <div class="p-3 bg-emerald-950/30 border border-emerald-900/20 rounded text-emerald-500">
                <i data-lucide="bed" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Booking Hari Ini & Status Active Checkin --}}
        <div class="bg-slate-900/60 border border-purple-950 p-5 rounded-lg flex items-center justify-between shadow-md hover:border-purple-800/40 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Booking Terkini</p>
                <h3 class="text-2xl font-mono font-bold text-amber-500">+{{ $bookingHariIni }} <span class="text-xs text-slate-500 font-sans">Hari Ini</span></h3>
                <p class="text-[11px] text-slate-400">🔥 {{ $bookingCheckin }} Reservasi Aktif (In-House)</p>
            </div>
            <div class="p-3 bg-amber-950/40 border border-amber-900/30 rounded text-amber-400">
                <i data-lucide="calendar-clock" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Pendapatan Bulan Ini dari Model Checkout --}}
        <div class="bg-slate-900/60 border border-purple-950 p-5 rounded-lg flex items-center justify-between shadow-md hover:border-purple-800/40 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Omzet Bulan Ini</p>
                <h3 class="text-xl font-mono font-bold text-yellow-500">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-500">Berdasarkan data checkout</p>
            </div>
            <div class="p-3 bg-yellow-950/30 border border-yellow-900/20 rounded text-yellow-500">
                <i data-lucide="coins" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    {{-- ── 2. DUA KANVAS GRAFIK (STATISTIK 6 BULAN TERAKHIR) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Grafik Tren Volume Booking --}}
        <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
            <h3 class="font-serif text-sm text-purple-200 font-semibold mb-4 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-purple-400"></i> Kuantitas Reservasi (6 Bulan Terakhir)
            </h3>
            <div class="w-full h-64">
                <canvas id="chartBooking"></canvas>
            </div>
        </div>

        {{-- Grafik Tren Finansial Pendapatan --}}
        <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
            <h3 class="font-serif text-sm text-purple-200 font-semibold mb-4 flex items-center gap-2">
                <i data-lucide="line-chart" class="w-4 h-4 text-fuchsia-400"></i> Kurva Pendapatan Checkout (Rupiah)
            </h3>
            <div class="w-full h-64">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </div>
    </div>

    {{-- ── 3. LIST BOOKING TERBARU ─────────────────────────────── --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-serif text-base text-purple-200 font-semibold flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4 text-purple-400"></i> 5 Aktivitas Transaksi Booking Terbaru
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-2.5 px-3">Kode Booking</th>
                        <th class="py-2.5 px-3">Nama Pelanggan</th>
                        <th class="py-2.5 px-3">Kamar / Sektor</th>
                        <th class="py-2.5 px-3 text-right">Total Tarif</th>
                        <th class="py-2.5 px-3 text-center">Status Hunian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($bookingTerbaru as $b)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-3 px-3 font-mono text-purple-400 font-bold">{{ $b->kode_booking ?? '#BK-'.$b->id }}</td>
                        <td class="py-3 px-3 font-medium text-purple-100">
                            👤 {{ $b->tamu->nama_lengkap ?? ($b->tamu->name ?? 'Umum') }}
                        </td>
                        <td class="py-3 px-3 text-slate-400">
                            @if($b->kamars && $b->kamars->isNotEmpty())
                                @foreach($b->kamars as $k)
                                    <span class="bg-slate-950 px-1.5 py-0.5 rounded border border-purple-950/40 text-[10px]">
                                        No.{{ $k->nomor_kamar }} ({{ $k->tipeKamar->nama_tipe ?? 'Tipe' }})
                                    </span>
                                @endforeach
                            @else
                                <span class="text-slate-600 italic">Sektor Kosong</span>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-right font-mono text-yellow-500 font-semibold">
                            Rp {{ number_format($b->total_harga ?? ($b->total_bayar ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-3 text-center">
                            @if(($b->status ?? '') === 'checkout')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-950/60 text-purple-300 border border-purple-900/50">CHECKOUT</span>
                            @elseif(($b->status ?? '') === 'checkin')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-950/60 text-emerald-400 border border-emerald-900/50">IN-HOUSE</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-950/60 text-amber-400 border border-amber-900/50">{{ strtoupper($b->status ?? 'PENDING') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500 italic">Belum ada mutasi log booking terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT RENDERING CHART ENGINE --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- 1. SET UP DATA DARI GRAFIK CONTROLLER ---
        const dataBookingRaw = {!! json_encode($grafikBooking) !!};
        const dataPendapatanRaw = {!! json_encode($grafikPendapatan) !!};

        // Memetakan label & nilai volume booking
        const labelsBooking = dataBookingRaw.map(item => item.bulan);
        const valuesBooking = dataBookingRaw.map(item => item.total);

        // Memetakan label & nilai omzet finansial
        const labelsPendapatan = dataPendapatanRaw.map(item => item.bulan);
        const valuesPendapatan = dataPendapatanRaw.map(item => item.total);

        // --- 2. RENDER CHART QUANTITY BOOKING ---
        const ctxBooking = document.getElementById('chartBooking').getContext('2d');
        new Chart(ctxBooking, {
            type: 'bar',
            data: {
                labels: labelsBooking.length ? labelsBooking : ['No Data'],
                datasets: [{
                    label: 'Total Transaksi Masuk',
                    data: valuesBooking.length ? valuesBooking : [0],
                    backgroundColor: 'rgba(147, 51, 234, 0.4)',
                    borderColor: '#a855f7',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#cbd5e1', font: { family: 'monospace' } } } },
                scales: {
                    x: { grid: { color: 'rgba(147, 51, 234, 0.05)' }, ticks: { color: '#94a3b8' } },
                    y: { grid: { color: 'rgba(147, 51, 234, 0.05)' }, ticks: { color: '#94a3b8', stepSize: 1 } }
                }
            }
        });

        // --- 3. RENDER CHART FINANSIAL PENDAPATAN ---
        const ctxPendapatan = document.getElementById('chartPendapatan').getContext('2d');
        new Chart(ctxPendapatan, {
            type: 'line',
            data: {
                labels: labelsPendapatan.length ? labelsPendapatan : ['No Data'],
                datasets: [{
                    label: 'Nominal Kas Masuk (Rp)',
                    data: valuesPendapatan.length ? valuesPendapatan : [0],
                    borderColor: '#d946ef',
                    backgroundColor: 'rgba(217, 70, 239, 0.08)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#f472b6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#cbd5e1', font: { family: 'monospace' } } } },
                scales: {
                    x: { grid: { color: 'rgba(217, 70, 239, 0.05)' }, ticks: { color: '#94a3b8' } },
                    y: { grid: { color: 'rgba(217, 70, 239, 0.05)' }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    });
</script>
@endsection