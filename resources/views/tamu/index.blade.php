@extends('layouts.app')

@section('title', 'Guests')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Guest Registry</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Maintain a complete record of your premium clientele.</p>
        </div>
        <a href="{{ route('tamu.create') }}" class="px-6 py-3 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 flex items-center gap-2 transform active:scale-95">
            <i data-lucide="user-plus" class="w-5 h-5 text-accent-gold"></i>
            Add New Guest
        </a>
    </div>

    <x-card>
        {{-- FILTER & SEARCH BAR YANG LEBIH CANTIK --}}
        <form action="{{ route('tamu.index') }}" method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-50/50 dark:bg-slate-850 p-4 rounded-2xl border border-gray-100 dark:border-slate-800">
            <div class="sm:col-span-2 space-y-1">
                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Search Client</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, NIK, or phone..."
                           class="w-full pl-11 pr-8 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 dark:text-white text-xs transition-all">
                    @if(request('search'))
                        <a href="{{ route('tamu.index') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-white text-xs">&times;</a>
                    @endif
                </div>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-gray-900 dark:bg-slate-700 hover:bg-gray-800 text-white text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Find Guest
                </button>
            </div>
        </form>

        {{-- STRUKTUR UTAMA TABEL --}}
        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Client Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Identification (NIK)</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Contact Details</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @foreach($tamus as $tamu)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $tamu->nama_lengkap }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold mt-0.5 tracking-wider uppercase">{{ $tamu->kewarganegaraan }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <code class="px-2 py-1 bg-gray-100 dark:bg-slate-800 rounded text-xs font-mono text-gray-600 dark:text-gray-400">{{ $tamu->nik }}</code>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300 leading-tight">{{ $tamu->no_hp }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $tamu->email }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('tamu.show', $tamu) }}" class="p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-blue-900/20 rounded-xl transition-colors" title="View Profile"><i data-lucide="user-circle" class="w-5 h-5"></i></a>
                                <a href="{{ route('tamu.edit', $tamu) }}" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-colors" title="Edit Data"><i data-lucide="edit-3" class="w-5 h-5"></i></a>
                                
                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('tamu.destroy', $tamu) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteGuest(this)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Delete Records">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $tamus->links() }}
        </div>
    </x-card>
</div>

{{-- SCRIPT POPUP MODERN SWEETALERT2 UNTUK AKSI DI TENGAH SCREEN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDarkMode = document.documentElement.classList.contains('dark');

    function confirmDeleteGuest(button) {
        Swal.fire({
            title: 'Archive Client Record?',
            text: "Are you sure you want to purge this premium client profile from the active registry?",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Merah mewah destruktif
            cancelButtonColor: isDarkMode ? '#334155' : '#6b7280',
            confirmButtonText: 'Yes, Purge Client',
            background: isDarkMode ? '#1e293b' : '#ffffff',
            color: isDarkMode ? '#ffffff' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 dark:border-slate-800 shadow-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection