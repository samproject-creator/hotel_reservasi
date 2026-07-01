@extends('layouts.app')

@section('title', 'Manajemen Tipe Kamar')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="layers" class="w-6 h-6 text-purple-400"></i> Kategori & Tipe Kamar
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Konfigurasi kelas hunian, spesifikasi, beserta tarif dasar per malam.</p>
        </div>
        
        <button onclick="openModal('add')" 
           class="px-4 py-2 bg-purple-950 hover:bg-purple-900 text-purple-200 text-xs font-semibold rounded border border-purple-800 flex items-center gap-2 transition-all self-start sm:self-auto shadow-[0_0_15px_rgba(147,51,234,0.1)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Tipe Baru
        </button>
    </div>

    {{-- FILTER SEARCH --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('tipe-kamar.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Tipe Kamar..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 text-xs focus:outline-none focus:border-purple-600 transition-all">
            </div>
            <button type="submit" class="px-5 py-2 bg-purple-950/40 hover:bg-purple-900/40 text-purple-300 text-xs font-medium border border-purple-900/40 rounded transition-colors">
                Saring Data
            </button>
        </form>
    </div>

    {{-- TABEL DATA TIPE KAMAR --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">Nama Tipe Class</th>
                        <th class="py-3 px-4">Tarif Kamar</th>
                        <th class="py-3 px-4">Kapasitas</th>
                        <th class="py-3 px-4">Deskripsi & Fasilitas</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($tipeKamars as $tipe)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-4 px-4 font-semibold text-purple-100 text-sm">
                            {{ $tipe->nama_tipe }}
                        </td>
                        <td class="py-4 px-4 font-mono text-amber-400 font-bold text-sm">
                            Rp {{ number_format($tipe->harga_per_malam, 0, ',', '.') }}<span class="text-[10px] text-slate-500 font-normal">/malam</span>
                        </td>
                        <td class="py-4 px-4 font-medium text-slate-300">
                            {{ $tipe->kapasitas }} Orang
                        </td>
                        <td class="py-4 px-4 text-slate-400 max-w-xs">
                            <div class="font-normal text-slate-300">{{ $tipe->deskripsi ?? '-' }}</div>
                            <div class="text-[10px] text-purple-400 mt-1">
                                🛠️ {{ is_array($tipe->fasilitas) ? implode(', ', $tipe->fasilitas) : $tipe->fasilitas }}
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                        onclick="openModal('edit', '{{ $tipe->id }}', '{{ $tipe->nama_tipe }}', '{{ intval($tipe->harga_per_malam) }}', '{{ $tipe->kapasitas }}', '{{ $tipe->deskripsi }}', '{{ is_array($tipe->fasilitas) ? implode(', ', $tipe->fasilitas) : $tipe->fasilitas }}')" 
                                        class="p-1 text-slate-400 hover:text-purple-400 transition-colors">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>

                                <form action="{{ route('tipe-kamar.destroy', $tipe->id) }}" method="POST" onsubmit="return confirm('Hapus tipe kamar ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-500 hover:text-red-500 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500 italic">Data blueprint tipe kamar belum terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $tipeKamars->links() }}
        </div>
    </div>
</div>

{{-- MODAL POP-UP --}}
<div id="tipeKamarModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-purple-950 rounded-lg w-full max-w-md shadow-2xl overflow-hidden">
        <div class="px-5 py-4 bg-purple-950/20 border-b border-purple-950 flex items-center justify-between">
            <h3 id="modalTitle" class="font-serif text-lg text-purple-200 font-bold">Molding Tipe Kamar</h3>
            <button onclick="closeModal()" class="text-slate-500 hover:text-slate-300">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="modalForm" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">

            <div>
                <label class="block text-slate-400 font-medium mb-1">Nama Tipe / Kelas Kamar</label>
                <input type="text" id="input_nama_tipe" name="nama_tipe" placeholder="Contoh: Standard, Deluxe" required
                       class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 font-medium mb-1">Tarif Per Malam (Rp)</label>
                    <input type="number" id="input_harga_per_malam" name="harga_per_malam" placeholder="350000" required min="0"
                           class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono">
                </div>
                <div>
                    <label class="block text-slate-400 font-medium mb-1">Kapasitas (Orang)</label>
                    <input type="number" id="input_kapasitas" name="kapasitas" placeholder="2" required min="1"
                           class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-slate-400 font-medium mb-1">Deskripsi Ringkas</label>
                <input type="text" id="input_deskripsi" name="deskripsi" placeholder="Kamar standar nyaman."
                       class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600">
            </div>

            <div>
                <label class="block text-slate-400 font-medium mb-1">Fasilitas (Pisahkan dengan koma)</label>
                <input type="text" id="input_fasilitas" name="fasilitas" placeholder="AC, TV, WiFi, Kamar Mandi"
                       class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-purple-950/40">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-950 text-slate-400 hover:text-slate-200 rounded">Batalkan</button>
                <button type="submit" id="submitButton" class="px-5 py-2 bg-purple-950 hover:bg-purple-900 text-purple-200 font-semibold border border-purple-800 rounded">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(mode, id = '', nama = '', harga = '', kapasitas = '', deskripsi = '', fasilitas = '') {
        const modal = document.getElementById('tipeKamarModal');
        const form = document.getElementById('modalForm');
        const methodField = document.getElementById('methodField');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitButton');

        form.reset();

        if (mode === 'add') {
            title.innerText = 'Molding Tipe Kelas Baru';
            submitBtn.innerText = 'Simpan Tipe Kamar';
            methodField.value = 'POST';
            form.action = "{{ route('tipe-kamar.store') }}";
        } else if (mode === 'edit') {
            title.innerText = 'Ubah Blueprint Kelas';
            submitBtn.innerText = 'Terapkan Perubahan';
            methodField.value = 'PUT';
            
            let url = "{{ route('tipe-kamar.update', ':id') }}";
            form.action = url.replace(':id', id);

            document.getElementById('input_nama_tipe').value = nama;
            document.getElementById('input_harga_per_malam').value = harga;
            document.getElementById('input_kapasitas').value = kapasitas;
            document.getElementById('input_deskripsi').value = deskripsi;
            document.getElementById('input_fasilitas').value = fasilitas;
        }
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('tipeKamarModal').classList.add('hidden');
    }
</script>
@endsection