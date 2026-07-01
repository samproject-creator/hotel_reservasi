@extends('layouts.app')

@section('title', 'Sektor Kamar & Peti')

@section('content')
<div class="space-y-6">
    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="door-closed" class="w-6 h-6 text-purple-400"></i> Sektor Kamar & Peti Istirahat
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola data hunian, lantai, status kelayakan, serta galeri fasilitas kamar.</p>
        </div>
        
        <button onclick="toggleModal('modal-tambah-kamar')" 
                class="px-4 py-2 bg-gradient-to-r from-purple-800 to-indigo-900 hover:from-purple-700 hover:to-indigo-800 text-purple-100 text-xs font-semibold rounded shadow-md border border-purple-700/50 flex items-center gap-2 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i> Bangun Kamar Baru
        </button>
    </div>

    {{-- TABEL DATA KAMAR DINAMIS --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">No. Kamar</th>
                        <th class="py-3 px-4">Tipe Kamar</th>
                        <th class="py-3 px-4">Lantai</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4">Galeri Foto</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($kamars as $kamar)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-4 px-4 font-bold text-purple-100 text-sm">{{ $kamar->nomor_kamar }}</td>
                        <td class="py-4 px-4 text-slate-400">{{ $kamar->tipeKamar->nama_tipe ?? 'Tanpa Tipe' }}</td>
                        <td class="py-4 px-4 font-mono">Lantai {{ $kamar->lantai }}</td>
                        <td class="py-4 px-4">
                            {!! $kamar->status_badge !!}
                        </td>
                        <td class="py-4 px-4 text-slate-400 truncate max-w-[150px]">{{ $kamar->keterangan ?? '-' }}</td>
                        <td class="py-4 px-4">
                            {{-- MENAMPILKAN GAMBAR YANG DI-UPLOAD --}}
                            @if(!empty($kamar->images) && count($kamar->images) > 0)
                                <div class="flex gap-1">
                                    @foreach(array_slice($kamar->images, 0, 2) as $foto)
                                        <div class="w-8 h-8 bg-slate-800 border border-purple-950 rounded overflow-hidden">
                                            <img src="{{ asset('storage/' . $foto) }}" class="w-full h-full object-cover" alt="Foto Fasilitas">
                                        </div>
                                    @endforeach
                                    @if(count($kamar->images) > 2)
                                        <div class="w-8 h-8 bg-purple-950/40 border border-purple-900/30 rounded flex items-center justify-center text-[10px] text-purple-400 font-bold">
                                            +{{ count($kamar->images) - 2 }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-600 text-[10px] italic">Tidak ada foto</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Edit (Memicu Modal Edit via JS) --}}
                                <button onclick="openEditModal({{ json_encode($kamar) }})" 
                                        class="p-1.5 text-slate-400 hover:text-purple-400 transition-colors" title="Edit Data">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                
                                {{-- FORM PROSES HAPUS DATA --}}
                                <form action="{{ route('kamar.destroy', $kamar->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin meruntuhkan kamar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 transition-colors" title="Runtuhkan Kamar">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500 italic">Belum ada sektor kamar yang dibangun di dalam kastil ini.</td>
                    </tr>
                    @endempty
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Link --}}
        <div class="mt-4">
            {{ $kamars->links() }}
        </div>
    </div>
</div>

{{-- ── MODAL POPUP: TAMBAH KAMAR BARU ── --}}
<div id="modal-tambah-kamar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleModal('modal-tambah-kamar')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-purple-900/50 rounded-xl shadow-2xl overflow-hidden z-10">
        <div class="px-6 py-4 border-b border-purple-950/60 bg-purple-950/10 flex items-center justify-between">
            <h3 class="font-serif text-base text-purple-200 font-semibold flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5 text-purple-400"></i> Form Pendataan Kamar Baru
            </h3>
            <button onclick="toggleModal('modal-tambah-kamar')" class="text-slate-400 hover:text-slate-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nomor/Kode Kamar</label>
                    <input type="text" name="nomor_kamar" required placeholder="Contoh: 101" class="castle-input">
                </div>
                <div>
                    <label class="form-label">Tipe Kamar Sektor</label>
                    <select name="tipe_kamar_id" required class="castle-select">
                        @foreach($tipeKamars as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Lantai</label>
                    <input type="number" name="lantai" required min="1" placeholder="Lantai Ke-" class="castle-input">
                </div>
                <div>
                    <label class="form-label">Status Awal</label>
                    <select name="status" class="castle-select">
                        <option value="tersedia">Tersedia</option>
                        <option value="ditempati">Ditempati</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="2" placeholder="Catatan sihir kamar..." class="castle-input"></textarea>
            </div>

            <div>
                <label class="form-label">Unggah Foto Fasilitas (Multi-upload)</label>
                <div class="border border-dashed border-purple-950 hover:border-purple-800/60 rounded p-4 text-center cursor-pointer relative bg-slate-950/50">
                    <input type="file" name="images[]" id="images-input-add" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="text-slate-400 text-xs">
                        <i data-lucide="upload-cloud" class="w-6 h-6 text-purple-500 mx-auto mb-1"></i>
                        <p>Klik/seret berkas foto fasilitas kamar ke sini</p>
                    </div>
                </div>
                <div id="preview-text-add" class="text-[10px] text-emerald-400 font-medium pt-1 hidden"></div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-tambah-kamar')" class="btn-ghost text-xs">Batalkan</button>
                <button type="submit" class="btn-pumpkin text-xs">Simpan Kamar</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL POPUP: EDIT DATA KAMAR ── --}}
<div id="modal-edit-kamar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleModal('modal-edit-kamar')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-purple-900/50 rounded-xl shadow-2xl overflow-hidden z-10">
        <div class="px-6 py-4 border-b border-purple-950/60 bg-purple-950/10 flex items-center justify-between">
            <h3 class="font-serif text-base text-purple-200 font-semibold flex items-center gap-2">
                <i data-lucide="edit" class="w-5 h-5 text-purple-400"></i> Perbarui Mantra Sektor Kamar
            </h3>
            <button onclick="toggleModal('modal-edit-kamar')" class="text-slate-400 hover:text-slate-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form id="form-edit-kamar" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nomor/Kode Kamar</label>
                    <input type="text" name="nomor_kamar" id="edit-nomor" required class="castle-input">
                </div>
                <div>
                    <label class="form-label">Tipe Kamar Sektor</label>
                    <select name="tipe_kamar_id" id="edit-tipe" required class="castle-select">
                        @foreach($tipeKamars as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Lantai</label>
                    <input type="number" name="lantai" id="edit-lantai" required min="1" class="castle-input">
                </div>
                <div>
                    <label class="form-label">Status Kamar</label>
                    <select name="status" id="edit-status" class="castle-select">
                        <option value="tersedia">Tersedia</option>
                        <option value="ditempati">Ditempati</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" id="edit-keterangan" rows="2" class="castle-input"></textarea>
            </div>

            <div>
                <label class="form-label">Ganti/Perbarui Foto Fasilitas</label>
                <div class="border border-dashed border-purple-950 hover:border-purple-800/60 rounded p-4 text-center cursor-pointer relative bg-slate-950/50">
                    <input type="file" name="images[]" id="images-input-edit" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="text-slate-400 text-xs">
                        <i data-lucide="upload-cloud" class="w-6 h-6 text-purple-500 mx-auto mb-1"></i>
                        <p>Unggah foto baru untuk menimpa foto lama</p>
                    </div>
                </div>
                <div id="preview-text-edit" class="text-[10px] text-emerald-400 font-medium pt-1 hidden"></div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-edit-kamar')" class="btn-ghost text-xs">Batalkan</button>
                <button type="submit" class="btn-pumpkin text-xs">Perbarui Kamar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    // Fungsi Pengisi Data Otomatis saat klik tombol Edit
    function openEditModal(kamar) {
        document.getElementById('form-edit-kamar').action = `/kamar/${kamar.id}`;
        document.getElementById('edit-nomor').value = kamar.nomor_kamar;
        document.getElementById('edit-tipe').value = kamar.tipe_kamar_id;
        document.getElementById('edit-lantai').value = kamar.lantai;
        document.getElementById('edit-status').value = kamar.status;
        document.getElementById('edit-keterangan').value = kamar.keterangan || '';
        
        toggleModal('modal-edit-kamar');
    }

    // Deteksi Jumlah Gambar Terpilih (Tambah Form)
    document.getElementById('images-input-add').addEventListener('change', function() {
        const preview = document.getElementById('preview-text-add');
        if(this.files.length > 0) {
            preview.textContent = `✔ ${this.files.length} foto siap dimasukkan ke database kastil!`;
            preview.classList.remove('hidden');
        }
    });

    // Deteksi Jumlah Gambar Terpilih (Edit Form)
    document.getElementById('images-input-edit').addEventListener('change', function() {
        const preview = document.getElementById('preview-text-edit');
        if(this.files.length > 0) {
            preview.textContent = `✔ ${this.files.length} foto baru siap menggantikan berkas lama!`;
            preview.classList.remove('hidden');
        }
    });
</script>
@endpush