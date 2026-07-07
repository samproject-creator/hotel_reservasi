@extends('layouts.app')

@section('title', 'Check-In Details - ' . $booking->kode_booking)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            <a href="{{ route('checkin.index') }}" class="p-3 bg-white dark:bg-slate-850 border border-gray-200 dark:border-slate-800 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm group">
                <i data-lucide="arrow-left" class="w-6 h-6 text-gray-400 group-hover:text-primary-600 transition-colors"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Access Verification</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Validation of authorized stay for reservation #{{ $booking->kode_booking }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <x-card title="Check-In Confirmation">
                <div class="flex items-center gap-6 mb-8 p-6 bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 rounded-3xl">
                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center text-green-600 shadow-sm border border-green-50 dark:border-green-900/20">
                        <i data-lucide="user-check" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-green-800 dark:text-green-400">Tamu Telah Check-In</p>
                        <p class="text-2xl font-black text-green-900 dark:text-green-300">{{ $booking->checkin->waktu_checkin->format('H:i') }} <span class="text-sm font-medium opacity-70">WIB</span></p>
                        <p class="text-xs text-green-700 dark:text-green-500 mt-1">{{ $booking->checkin->waktu_checkin->format('d F Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 px-2">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Guest Name</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $booking->tamu->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->tamu->nik }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Room Assignment</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($booking->kamar as $kamar)
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-[10px] font-bold rounded-lg border border-gray-200 dark:border-gray-600">Room {{ $kamar->nomor_kamar }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Check-Out Scheduled</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $booking->tanggal_checkout->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">12:00 PM (Default)</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Duty Officer</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $booking->checkin->user->name ?? 'System' }}</p>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center gap-4">
                <a href="{{ route('booking.show', $booking) }}" class="flex-1 px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-center">View Booking Details</a>
                <a href="{{ route('checkout.show', $booking) }}" class="flex-1 px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-colors text-center shadow-lg shadow-primary-500/20">Proceed to Check-Out</a>
            </div>
        </div>

        <div class="space-y-6">
            <x-card title="Quick Actions">
                <div class="space-y-3">
                    <button onclick="showQRCode()"
                        class="w-full p-3 flex items-center gap-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors border border-gray-100 dark:border-gray-700">
                        <i data-lucide="qr-code" class="w-4 h-4 text-gray-500"></i>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Show Booking QR
                        </span>
                    </button>
                </div>
            </x-card>

            <x-card title="Room Status">
                <div class="space-y-4">
                    @foreach($booking->kamar as $kamar)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Room {{ $kamar->nomor_kamar }}</span>
                        <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-[10px] font-bold rounded-full uppercase tracking-widest">Occupied</span>
                    </div>
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>
</div>

{{-- MODAL COMPONENT UNTUK QR CODE --}}
<div id="qrModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden" onclick="closeQRCode()">
    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl max-w-xs w-full text-center space-y-4 shadow-2xl border border-gray-100 dark:border-slate-700" onclick="event.stopPropagation()">
        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">Booking QR Code</h3>
        <p class="text-xs text-gray-400 font-medium">Scan to verify reservation metadata</p>
        
        <div class="bg-gray-50 p-4 rounded-2xl flex justify-center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $booking->kode_booking }}" alt="QR Code" class="w-48 h-48 rounded-lg shadow-sm">
        </div>
        
        <p class="text-sm font-bold text-primary-600 bg-primary-50 dark:bg-blue-950/40 dark:text-blue-400 py-1.5 rounded-xl uppercase tracking-widest">{{ $booking->kode_booking }}</p>
        
        <button onclick="closeQRCode()" class="w-full py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-gray-200 transition-colors">
            Close
        </button>
    </div>
</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
function showQRCode() {
    document.getElementById('qrModal').classList.remove('hidden');
}

function closeQRCode() {
    document.getElementById('qrModal').classList.add('hidden');
}

// Menutup modal otomatis jika menekan tombol Escape (ESC) pada keyboard
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeQRCode();
});
</script>
@endsection