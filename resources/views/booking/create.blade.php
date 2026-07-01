@extends('layouts.app')

@section('title', 'Data Reservasi Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
            <i data-lucide="calendar-plus" class="w-6 h-6 text-purple-400"></i> Buat Manifes Reservasi Kamar
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">Daftarkan masa singgah entitas makhluk ke dalam bilik kastil yang tersedia.</p>
    </div>

    <div class="bg-slate-900/40 border border-purple-950 rounded-xl p-6 shadow-md">
        <form action="{{ route('booking.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- PILIH TAMU --}}
            <div>
                <label class="form-label block text-purple-300 font-medium mb-1">Pilih Makhluk / Tamu</label>
                <select name="tamu_id" required class="castle-select w-full">
                    <option value="">-- Cari Nama Tamu Berdarah Dingin --</option>
                    @foreach($tamus as $tamu)
                        <option value="{{ $tamu->id }}">{{ $tamu->nama_lengkap }} (NIK: {{ $tamu->nik }})</option>
                    @endforeach
                </select>
            </div>

            {{-- TANGGAL RENCANA --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label block text-purple-300 font-medium mb-1">Tanggal Check-in</label>
                    <input type="date" name="tanggal_checkin" required min="{{ date('Y-m-d') }}" class="castle-input w-full">
                </div>
                <div>
                    <label class="form-label block text-purple-300 font-medium mb-1">Tanggal Check-out</label>
                    <input type="date" name="tanggal_checkout" required class="castle-input w-full">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label block text-purple-300 font-medium mb-1">Jumlah Tamu</label>
                    <input type="number" name="jumlah_tamu" required min="1" value="1" class="castle-input w-full">
                </div>
                <div>
                    <label class="form-label block text-purple-300 font-medium mb-1">Uang Muka / DP (Optional)</label>
                    <input type="number" name="uang_muka" min="0" placeholder="Rp" class="castle-input w-full">
                </div>
            </div>

            {{-- PEMILIHAN KAMAR BERDASARKAN TIPE (USER FRIENDLY) --}}
            <div>
                <label class="form-label block text-purple-300 font-medium mb-2">Pilih Bilik Kamar Tersedia</label>
                
                <div class="space-y-4 max-h-72 overflow-y-auto border border-purple-950 bg-slate-950 p-4 rounded-lg">
                    @php
                        // Kelompokkan data kamar berdasarkan Nama Tipe Kamar
                        $groupedKamars = $kamars->groupBy(function($item) {
                            return $item->tipeKamar->nama_tipe . ' - Rp ' . number_format($item->tipeKamar->harga_per_malam, 0, ',', '.') . '/malam';
                        });
                    @endphp

                    @forelse($groupedKamars as $tipeLabel => $kamarList)
                        <div class="border-b border-purple-950/40 pb-2 last:border-none">
                            <span class="text-xs font-serif text-amber-400 font-bold block mb-1.5">
                                🦇 {{ $tipeLabel }}
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pl-2">
                                @foreach($kamarList as $kamar)
                                    <label class="flex items-center gap-2 p-2 bg-slate-900 border border-purple-950 hover:border-purple-600 rounded cursor-pointer transition-colors text-xs text-slate-300">
                                        <input type="checkbox" name="kamar_ids[]" value="{{ $kamar->id }}" class="rounded text-purple-600 focus:ring-purple-950 bg-slate-950 border-purple-950">
                                        <span>No. <strong>{{ $kamar->nomor_kamar }}</strong> (Lt.{{ $kamar->lantai }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 italic text-center py-4">Semua bilik kamar kastil sedang penuh/maintenance.</p>
                    @endforelse
                </div>
                <p class="text-[10px] text-slate-500 mt-1.5">* Anda dapat mencentang lebih dari satu kamar jika tamu memesan multi-kamar sekaligus.</p>
            </div>

            <div>
                <label class="form-label block text-purple-300 font-medium mb-1">Catatan Tambahan Khusus</label>
                <textarea name="catatan" rows="2" placeholder="Contoh: Kedap cahaya matahari penuh, siapkan kantong darah segar..." class="castle-input w-full"></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-purple-950/40">
                <a href="{{ route('booking.index') }}" class="btn-ghost text-xs">Kembali</a>
                <button type="submit" class="btn-pumpkin text-xs px-4 py-2 bg-purple-800 rounded hover:bg-purple-700 text-purple-100">Reservasi</button>
            </div>
        </form>
    </div>
</div>
@endsection