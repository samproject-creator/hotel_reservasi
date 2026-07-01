@extends('layouts.app')

@section('title', 'Manifes Makhluk (Tamu)')

@section('content')
<div class="space-y-6">
    {{-- HEADER HALAMAN ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="users" class="w-6 h-6 text-purple-400"></i> Manifes Tamu & Makhluk Kastil
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola identitas, ras kewarganegaraan, kontak sihir, dan data pendaftaran tamu hotel.</p>
        </div>
        
        <button onclick="toggleModal('modal-tambah-tamu')" 
                class="px-4 py-2 bg-gradient-to-r from-purple-800 to-indigo-900 hover:from-purple-700 hover:to-indigo-800 text-purple-100 text-xs font-semibold rounded shadow-md border border-purple-700/50 flex items-center gap-2 transition-all">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Daftarkan Tamu Baru
        </button>
    </div>

    {{-- BAR PENCARIAN ── --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('tamu.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama makhluk, NIK, atau nomor kontak sihir..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 text-xs focus:outline-none focus:border-purple-600 transition-all">
            </div>
            <button type="submit" class="px-4 py-2 bg-purple-950/60 hover:bg-purple-900/60 text-purple-300 text-xs font-medium border border-purple-900/40 rounded transition-colors">
                Saring Pencarian
            </button>
        </form>
    </div>

    {{-- TABEL DATA ASLI ── --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">NIK (Identitas)</th>
                        <th class="py-3 px-4">Gender</th>
                        <th class="py-3 px-4">Kontak / HP</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Kewarganegaraan / Ras</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($tamus as $tamu)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-4 px-4 font-bold text-purple-100 text-sm">{{ $tamu->nama_lengkap }}</td>
                        <td class="py-4 px-4 font-mono text-slate-400">{{ $tamu->nik }}</td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $tamu->jenis_kelamin === 'L' ? 'bg-indigo-950 text-indigo-400 border border-indigo-900/50' : 'bg-fuchsia-950 text-fuchsia-400 border border-fuchsia-900/50' }}">
                                {{ $tamu->jenis_kelamin_label }}
                            </span>
                        </td>
                        <td class="py-4 px-4 font-mono text-slate-400">{{ $tamu->no_hp }}</td>
                        <td class="py-4 px-4 text-slate-400">{{ $tamu->email ?? '-' }}</td>
                        <td class="py-4 px-4 text-purple-300">{{ $tamu->kewarganegaraan ?? '-' }}</td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Edit mengoper objek data asli --}}
                                <button onclick="openEditTamuModal({{ json_encode($tamu) }})" 
                                        class="p-1.5 text-slate-400 hover:text-purple-400 transition-colors" title="Ubah Data">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                
                                {{-- Tombol Hapus Terkoneksi ke Route --}}
                                <form action="{{ route('tamu.destroy', $tamu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data makhluk ini dari manifes?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 transition-colors" title="Hapus Tamu">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500 italic">Tidak ada manifes data tamu yang ditemukan.</td>
                    </tr>
                    @endempty
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Link --}}
        <div class="mt-4">
            {{ $tamus->links() }}
        </div>
    </div>
</div>

{{-- ── MODAL POPUP: TAMBAH TAMU BARU ── --}}
<div id="modal-tambah-tamu" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleModal('modal-tambah-tamu')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-purple-900/50 rounded-xl shadow-2xl overflow-hidden z-10">
        <div class="px-6 py-4 border-b border-purple-950/60 bg-purple-950/10 flex items-center justify-between">
            <h3 class="font-serif text-base text-purple-200 font-semibold flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-purple-400"></i> Daftarkan Entitas Tamu Baru
            </h3>
            <button onclick="toggleModal('modal-tambah-tamu')" class="text-slate-400 hover:text-slate-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form action="{{ route('tamu.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Wayne Werewolf" class="castle-input">
                </div>
                <div>
                    <label class="form-label">NIK / KTP Identitas (16 Digit)</label>
                    <input type="text" name="nik" required maxlength="16" placeholder="Masukkan 16 digit angka" class="castle-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nomor HP / Kontak</label>
                    <input type="text" name="no_hp" required placeholder="Contoh: 08123456789" class="castle-input">
                </div>
                <div>
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" placeholder="nama@domain.com" class="castle-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="castle-select">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="castle-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="pekerjaan" placeholder="Contoh: Musisi, Atlet" class="castle-input">
                </div>
                <div>
                    <label class="form-label">Kewarganegaraan / Ras</label>
                    <input type="text" name="kewarganegaraan" placeholder="Contoh: Transylvania" class="castle-input">
                </div>
            </div>

            <div>
                <label class="form-label">Alamat Tinggal tempat Asal</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap..." class="castle-input"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-tambah-tamu')" class="btn-ghost text-xs">Batalkan</button>
                <button type="submit" class="btn-pumpkin text-xs">Simpan Tamu</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL POPUP: EDIT DATA TAMU ── --}}
<div id="modal-edit-tamu" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleModal('modal-edit-tamu')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-purple-900/50 rounded-xl shadow-2xl overflow-hidden z-10">
        <div class="px-6 py-4 border-b border-purple-950/60 bg-purple-950/10 flex items-center justify-between">
            <h3 class="font-serif text-base text-purple-200 font-semibold flex items-center gap-2">
                <i data-lucide="edit" class="w-5 h-5 text-purple-400"></i> Mutasi Data Manifes Tamu
            </h3>
            <button onclick="toggleModal('modal-edit-tamu')" class="text-slate-400 hover:text-slate-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form id="form-edit-tamu" action="" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="edit-nama" required class="castle-input">
                </div>
                <div>
                    <label class="form-label">NIK / KTP Identitas (16 Digit)</label>
                    <input type="text" name="nik" id="edit-nik" required maxlength="16" class="castle-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" id="edit-nohp" required class="castle-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit-email" class="castle-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="edit-gender" required class="castle-select">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="edit-tgllahir" class="castle-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="pekerjaan" id="edit-pekerjaan" class="castle-input">
                </div>
                <div>
                    <label class="form-label">Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" id="edit-warga" class="castle-input">
                </div>
            </div>

            <div>
                <label class="form-label">Alamat</label>
                <textarea name="alamat" id="edit-alamat" rows="2" class="castle-input"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-edit-tamu')" class="btn-ghost text-xs">Batalkan</button>
                <button type="submit" class="btn-pumpkin text-xs">Perbarui Manifes</button>
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

    // Fungsi Pengisi Data Otomatis ke Form Edit saat tombol Klik
    function openEditTamuModal(tamu) {
        document.getElementById('form-edit-tamu').action = `/tamu/${tamu.id}`;
        document.getElementById('edit-nama').value = tamu.nama_lengkap;
        document.getElementById('edit-nik').value = tamu.nik;
        document.getElementById('edit-nohp').value = tamu.no_hp;
        document.getElementById('edit-email').value = tamu.email || '';
        document.getElementById('edit-gender').value = tamu.jenis_kelamin;
        document.getElementById('edit-pekerjaan').value = tamu.pekerjaan || '';
        document.getElementById('edit-warga').value = tamu.kewarganegaraan || '';
        document.getElementById('edit-alamat').value = tamu.alamat || '';
        
        // Atur Tanggal Lahir jika ada (Format yyyy-MM-dd)
        if(tamu.tanggal_lahir) {
            let dateStr = tamu.tanggal_lahir.split('T')[0];
            document.getElementById('edit-tgllahir').value = dateStr;
        } else {
            document.getElementById('edit-tgllahir').value = '';
        }

        toggleModal('modal-edit-tamu');
    }
</script>
@endpush