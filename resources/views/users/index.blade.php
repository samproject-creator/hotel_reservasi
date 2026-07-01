@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="users" class="w-6 h-6 text-purple-400"></i> Manajemen Pengguna & Hak Akses
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola kredensial login staf, nomor kontak, delegasi jabatan, serta status aktifasi user.</p>
        </div>
        
        <button onclick="openUserModal('add')" 
           class="px-4 py-2 bg-purple-950 hover:bg-purple-900 text-purple-200 text-xs font-semibold rounded border border-purple-800 flex items-center gap-2 transition-all self-start sm:self-auto shadow-[0_0_15px_rgba(147,51,234,0.1)]">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Karyawan Baru
        </button>
    </div>

    {{-- FILTER SEARCH --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 text-xs">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau Email Karyawan..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 transition-all">
            </div>

            <button type="submit" class="px-5 py-2 bg-purple-950/40 hover:bg-purple-900/40 text-purple-300 font-medium border border-purple-900/40 rounded transition-colors">
                Saring Data
            </button>
        </form>
    </div>

    {{-- TABEL USER --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400 font-serif">
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Alamat Email / HP</th>
                        <th class="py-3 px-4">Jabatan/Role</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/30 text-slate-300">
                    @forelse($users as $u)
                    <tr class="hover:bg-purple-950/10 transition-colors">
                        <td class="py-4 px-4 font-semibold text-purple-100 text-sm">
                            👤 {{ $u->name }} @if(auth()->id() === $u->id) <span class="text-[10px] text-purple-400 font-normal italic">(Anda)</span> @endif
                        </td>
                        <td class="py-4 px-4 text-slate-400 font-mono">
                            <div>{{ $u->email }}</div>
                            <div class="text-[10px] text-slate-500 mt-0.5">📞 {{ $u->no_hp ?? '-' }}</div>
                        </td>
                        <td class="py-4 px-4">
                            @if($u->role === 'admin')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-950 text-purple-300 border border-purple-800">Admin System</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-950 text-slate-400 border border-slate-800">Petugas / Frontdesk</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($u->is_active ?? true)
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-900 rounded">Aktif</span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-red-950 text-red-400 border border-red-900 rounded">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                        onclick="openUserModal('edit', '{{ $u->id }}', '{{ $u->name }}', '{{ $u->email }}', '{{ $u->role }}', '{{ $u->no_hp }}', '{{ $u->is_active ? 1 : 0 }}')" 
                                        class="p-1 text-slate-400 hover:text-purple-400 transition-colors" title="Ubah Hak Akses">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>

                                @if(auth()->id() !== $u->id)
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus permanen hak akses pengguna ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-500 hover:text-red-500 transition-colors" title="Cabut Akses">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500 italic">Tidak ada data pengguna lain terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- MODAL POP-UP USER --}}
<div id="userModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-purple-950 rounded-lg w-full max-w-md shadow-2xl overflow-hidden">
        <div class="px-5 py-4 bg-purple-950/20 border-b border-purple-950 flex items-center justify-between">
            <h3 id="modalUserTitle" class="font-serif text-lg text-purple-200 font-bold">Kredensial Pengguna</h3>
            <button onclick="closeUserModal()" class="text-slate-500 hover:text-slate-300">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="modalUserForm" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <input type="hidden" id="methodUserField" name="_method" value="POST">

            <div>
                <label class="block text-slate-400 font-medium mb-1">Nama Karyawan / Pengguna</label>
                <input type="text" id="input_name" name="name" placeholder="Nama Lengkap Staf" required max="100"
                       class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 font-medium mb-1">Alamat Email Login</label>
                    <input type="email" id="input_email" name="email" placeholder="staf@hotel.com" required
                           class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono">
                </div>
                <div>
                    <label class="block text-slate-400 font-medium mb-1">Nomor HP / WA</label>
                    <input type="text" id="input_no_hp" name="no_hp" placeholder="08123456xxx" max="20"
                           class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 font-medium mb-1">Password</label>
                    <input type="password" id="input_password" name="password" placeholder="••••••••"
                           class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono">
                </div>
                <div>
                    <label class="block text-slate-400 font-medium mb-1">Konfirmasi Password</label>
                    <input type="password" id="input_password_confirmation" name="password_confirmation" placeholder="••••••••"
                           class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono">
                </div>
            </div>
            <p id="passwordHelp" class="text-[10px] text-slate-500 mt-1 hidden">* Kosongkan password jika tidak ingin mengubahnya.</p>

            <div>
                <label class="block text-slate-400 font-medium mb-1">Otoritas Hak Akses (Role)</label>
                <select id="input_role" name="role" required
                        class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600">
                    <option value="petugas">Petugas (Front Desk Operasional)</option>
                    <option value="admin">Admin (Akses Penuh & Konfigurasi)</option>
                </select>
            </div>

            <div id="statusToggleBox" class="flex items-center gap-2 hidden">
                <input type="checkbox" id="input_is_active" name="is_active" value="1"
                       class="rounded border-purple-950 text-purple-600 focus:ring-purple-600 bg-slate-950">
                <label class="text-slate-400 font-medium">Izinkan pengguna ini login (User Aktif)</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-purple-950/40">
                <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-slate-950 text-slate-400 hover:text-slate-200 rounded">Batalkan</button>
                <button type="submit" id="submitUserButton" class="px-5 py-2 bg-purple-950 hover:bg-purple-900 text-purple-200 font-semibold border border-purple-800 rounded">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUserModal(mode, id = '', name = '', email = '', role = '', no_hp = '', is_active = 1) {
        const modal = document.getElementById('userModal');
        const form = document.getElementById('modalUserForm');
        const methodField = document.getElementById('methodUserField');
        const title = document.getElementById('modalUserTitle');
        const submitBtn = document.getElementById('submitUserButton');
        const passHelp = document.getElementById('passwordHelp');
        const passInput = document.getElementById('input_password');
        const passConfInput = document.getElementById('input_password_confirmation');
        const statusBox = document.getElementById('statusToggleBox');

        form.reset();

        if (mode === 'add') {
            title.innerText = 'Daftarkan Akun Staf Baru';
            submitBtn.innerText = 'Buat Akun';
            methodField.value = 'POST';
            passInput.required = true;
            passConfInput.required = true;
            passHelp.classList.add('hidden');
            statusBox.classList.add('hidden');
            form.action = "{{ route('users.store') }}";
        } else if (mode === 'edit') {
            title.innerText = 'Ubah Otoritas & Profil Staf';
            submitBtn.innerText = 'Simpan Perubahan';
            methodField.value = 'PUT';
            passInput.required = false;
            passConfInput.required = false;
            passHelp.classList.remove('hidden');
            statusBox.classList.remove('hidden');
            
            let url = "{{ route('users.update', ':id') }}";
            form.action = url.replace(':id', id);

            document.getElementById('input_name').value = name;
            document.getElementById('input_email').value = email;
            document.getElementById('input_role').value = role;
            document.getElementById('input_no_hp').value = no_hp;
            document.getElementById('input_is_active').checked = parseInt(is_active) === 1;
        }
        modal.classList.remove('hidden');
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }
</script>
@endsection