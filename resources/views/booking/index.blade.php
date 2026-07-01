@extends('layouts.app')

@section('title', 'Manajemen Transaksi Reservasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-6 h-6 text-purple-400"></i> Manifes Reservasi & Booking
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Pantau status pemesanan bilik tidur, kelola tanggal check-in makhluk, serta cetak manifestasi QR Code.</p>
        </div>
        
        <a href="{{ route('booking.create') }}" 
           class="px-4 py-2 bg-gradient-to-r from-purple-800 to-indigo-900 hover:from-purple-700 hover:to-indigo-800 text-purple-100 text-xs font-semibold rounded shadow-md border border-purple-700/50 flex items-center gap-2 transition-all self-start sm:self-auto">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i> Buat Reservasi Baru
        </a>
    </div>

    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('booking.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Booking / Nama Tamu..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 text-xs focus:outline-none focus:border-purple-600 transition-all">
            </div>
            <div>
                <select name="status" class="castle-select !py-2">
                    <option value="">-- Semua Status Transaksi --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="checkin" {{ request('status') == 'checkin' ? 'selected' : '' }}>Check-In</option>
                    <option value="checkout" {{ request('status') == 'checkout' ? 'selected' : '' }}>Check-Out</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-purple-950/60 hover:bg-purple-900/60 text-purple-300 text-xs font-medium border border-purple-900/40 rounded transition-colors">
                Saring Pencarian
            </button>
        </form>
    </div>

    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">Kode Booking</th>
                        <th class="py-3 px-4">Entitas Tamu</th>
                        <th class="py-3 px-4">Kamar</th>
                        <th class="py-3 px-4">Tanggal Rencana</th>
                        <th class="py-3 px-4">Total Tarif</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Kunci QR</th>
                        <th class="py-3 px-4 text-center">Aksi Operasional</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-4 px-4 font-mono font-bold text-amber-500 text-sm">
                            <a href="{{ route('booking.show', $booking->id) }}" class="hover:underline">
                                {{ $booking->kode_booking }}
                            </a>
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-semibold text-purple-100 text-sm">{{ $booking->tamu->nama_lengkap ?? 'Tanpa Nama' }}</div>
                            <span class="text-[10px] text-slate-500">// NIK: {{ $booking->tamu->nik ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-4">
                            @foreach($booking->kamars as $kamar)
                                <span class="px-1.5 py-0.5 bg-slate-950 border border-purple-950 rounded text-purple-300 font-mono text-[11px] inline-block mr-1">
                                    {{ $kamar->nomor_kamar }}
                                </span>
                            @endforeach
                        </td>
                        <td class="py-4 px-4 text-slate-400">
                            <div>{{ $booking->tanggal_checkin->format('d M Y') }} - {{ $booking->tanggal_checkout->format('d M Y') }}</div>
                            <span class="text-[10px] text-purple-400 font-medium">{{ $booking->jumlah_malam }} Malam</span>
                        </td>
                        <td class="py-4 px-4 font-mono text-purple-300 font-bold">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-4">
                            {!! $booking->status_badge !!}
                        </td>
                        <td class="py-4 px-4 text-center">
                            <button onclick="openQrModal('{{ $booking->kode_booking }}', '{{ $booking->tamu->nama_lengkap ?? 'Tamu' }}')" 
                                    class="p-1 bg-slate-950 border border-purple-950 rounded hover:border-purple-600 transition-colors inline-flex items-center justify-center">
                                <i data-lucide="qr-code" class="w-5 h-5 text-purple-400"></i>
                            </button>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                    <form action="{{ route('booking.toggleStatus', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 bg-purple-950 hover:bg-purple-900 text-purple-300 border border-purple-800 rounded text-[10px] font-semibold flex items-center gap-1">
                                            <i data-lucide="refresh-cw" class="w-3 h-3"></i> 
                                            {{ $booking->status === 'pending' ? 'Confirm' : 'Unconfirm' }}
                                        </button>
                                    </form>

                                    @if($booking->status === 'confirmed')
                                        <form action="/checkin/{{ $booking->id }}" method="POST" onsubmit="return confirm('Proses Check-in untuk tamu ini?')" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 bg-emerald-950 hover:bg-emerald-900 text-emerald-400 border border-emerald-900 rounded text-[10px] font-semibold flex items-center gap-1">
                                                <i data-lucide="log-in" class="w-3 h-3"></i> Check-in
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Batalkan pemesanan kamar ini?')" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1 text-slate-400 hover:text-red-400 transition-colors">
                                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($booking->status === 'checkin')
                                    @php 
                                        $sisa = $booking->total_harga - ($booking->uang_muka ?? 0);
                                    @endphp
                                    <button type="button" 
                                            onclick="openCheckoutModal('{{ $booking->id }}', '{{ $booking->kode_booking }}', '{{ $sisa }}')"
                                            class="px-2 py-1 bg-red-950 hover:bg-red-900 text-red-400 border border-red-900 rounded text-[10px] font-semibold flex items-center gap-1">
                                        <i data-lucide="log-out" class="w-3 h-3"></i> Check-out
                                    </button>
                                @endif

                                @if($booking->status === 'cancelled')
                                    <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus permanen riwayat data booking ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-500 hover:text-red-500 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-500 italic">Tidak ada manifes data reservasi yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

<div id="modal-qr-kunci" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" onclick="closeQrModal()"></div>
    <div class="relative w-full max-w-sm bg-slate-900 border border-purple-900/50 rounded-xl shadow-2xl p-6 text-center z-10">
        <div class="flex justify-between items-center border-b border-purple-950/50 pb-3 mb-4">
            <h4 class="font-serif text-sm text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="key-round" class="w-4 h-4 text-purple-400"></i> Kunci Portal Sihir QR
            </h4>
            <button onclick="closeQrModal()" class="text-slate-500 hover:text-slate-300"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <p class="text-xs text-slate-400 mb-1">Kode Portal: <span id="qr-code-txt" class="font-mono text-amber-500 font-bold"></span></p>
        <p class="text-[11px] text-slate-500 mb-4">Pemegang Hak Akses: <span id="qr-tamu-txt" class="text-purple-300"></span></p>
        <div class="bg-white p-4 rounded-lg inline-block">
            <img id="qr-image-src" src="" alt="QR Portal" class="w-40 h-40">
        </div>
        <p class="text-[10px] text-purple-400/70 italic mt-4">🦇 Dekatkan segel QR ini pada sensor kamar!</p>
    </div>
</div>

<div id="modal-checkout-pembayaran" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" onclick="closeCheckoutModal()"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-purple-900/50 rounded-xl shadow-2xl p-6 z-10">
        <div class="flex justify-between items-center border-b border-purple-950/50 pb-3 mb-4">
            <h4 class="font-serif text-sm text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="coins" class="w-4 h-4 text-purple-400"></i> Formulir Pelunasan Check-out
            </h4>
            <button type="button" onclick="closeCheckoutModal()" class="text-slate-500 hover:text-slate-300">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <form id="form-checkout-aksi" method="POST" class="space-y-4">
            @csrf
            <div>
                <p class="text-xs text-slate-400">Kode Transaksi: <span id="txt-co-kode" class="font-mono text-amber-500 font-bold"></span></p>
            </div>

            <div class="p-3 bg-purple-950/20 border border-purple-900/30 rounded text-xs">
                <span class="text-slate-400">Total Sisa Tagihan Kamar:</span>
                <span id="txt-co-sisa" class="text-yellow-500 font-mono font-bold block text-base mt-0.5"></span>
            </div>

            <div class="text-xs">
                <label class="block text-slate-400 font-medium mb-1">Jumlah Uang Diterima (Rp)</label>
                <input type="number" id="input-co-bayar" name="total_bayar" required
                       class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono text-sm">
            </div>

            <div class="text-xs">
                <label class="block text-slate-400 font-medium mb-1">Metode Pembayaran</label>
                <select name="metode_pembayaran" required class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600">
                    <option value="cash">Tunai / Cash</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="kartu_kredit">Kartu Kredit</option>
                    <option value="debit">Debit</option>
                </select>
            </div>

            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-purple-800 to-indigo-900 hover:from-purple-700 hover:to-indigo-800 text-purple-100 text-xs font-semibold border border-purple-700/50 rounded shadow transition-all">
                Selesaikan Pembayaran & Check-out
            </button>
        </form>
    </div>
</div>

<script>
    function openCheckoutModal(id, kode, sisa) {
        const form = document.getElementById('form-checkout-aksi');
        form.action = `/checkout/${id}`;
        
        document.getElementById('txt-co-kode').innerText = kode;
        document.getElementById('txt-co-sisa').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisa);
        
        const inputBayar = document.getElementById('input-co-bayar');
        inputBayar.min = sisa;
        inputBayar.value = sisa;
        
        document.getElementById('modal-checkout-pembayaran').classList.remove('hidden');
    }

    function closeCheckoutModal() {
        document.getElementById('modal-checkout-pembayaran').classList.add('hidden');
    }
</script>
@endsection