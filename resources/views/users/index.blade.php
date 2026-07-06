@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Access Control</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Manage LuxeHotel operators and administrative privileges.</p>
        </div>
        <a href="{{ route('users.create') }}" class="px-6 py-3 bg-gray-900 dark:bg-slate-700 text-white font-black rounded-2xl hover:bg-black dark:hover:bg-slate-600 transition-all shadow-xl shadow-gray-900/10 flex items-center gap-3 uppercase text-xs tracking-widest transform active:scale-95">
            <i data-lucide="user-plus" class="w-5 h-5 text-accent-gold"></i> Provision User
        </a>
    </div>

    <x-card title="System Operators Ledger">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-700">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Identity</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Access Privilege</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Operational Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Administrative</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-900 dark:bg-slate-700 flex items-center justify-center text-sm font-black text-accent-gold uppercase shadow-lg shadow-gray-900/10">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @php
                                $roleClass = $user->role === 'admin'
                                    ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $roleClass }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            @if($user->is_active)
                                <span class="flex items-center gap-2 text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Authorized
                                </span>
                            @else
                                <span class="flex items-center gap-2 text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Suspended
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            {{-- REMOVED OPACITY-0: TOMBOL AKSI SEKARANG SELALU MUNCUL SECARA PERMANEN --}}
                            <div class="flex items-center justify-end gap-2 transition-all">
                                <a href="{{ route('users.edit', $user) }}" class="p-2 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors" title="Edit Operator">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteOperator(this, '{{ $user->name }}')" class="p-2 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-colors" title="Revoke Privilege">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 dark:text-slate-600 px-2 italic">You</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </x-card>
</div>

{{-- SCRIPT POPUP MODERN SWEETALERT2 UNTUK AKSI DI TENGAH SCREEN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDarkMode = document.documentElement.classList.contains('dark');

    function confirmDeleteOperator(button, operatorName) {
        Swal.fire({
            title: 'Revoke Access Privilege?',
            text: `Are you sure you want to permanently purge "${operatorName}" from the system operator registry? This cannot be undone.`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Merah mewah destruktif
            cancelButtonColor: isDarkMode ? '#334155' : '#6b7280',
            confirmButtonText: 'Yes, Revoke Privilege',
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