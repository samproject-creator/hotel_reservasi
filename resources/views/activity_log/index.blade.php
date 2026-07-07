@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Audit Trail</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">A forensic timeline of all critical system operations.</p>
        </div>
        <form action="{{ route('activity-log.clear-old') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="hari" value="30">
            <button type="button" onclick="confirmClearLegacyLogs(event)" class="px-6 py-3 bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-400 font-black rounded-2xl hover:bg-red-100 dark:hover:bg-red-900/20 transition-all border border-red-200 dark:border-red-900/30 text-xs uppercase tracking-widest flex items-center gap-2 transform active:scale-95">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Clear Legacy History
            </button>
        </form>
    </div>

    <x-card>
        {{-- FILTER & EXPORT --}}
        <div class="mb-6 bg-gray-50/50 dark:bg-slate-850 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 space-y-4">
            <form action="{{ route('activity-log.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Filter Berdasarkan Aktivitas / Tindakan --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Operation Type</label>
                    <select name="action" class="w-full text-xs px-3 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 dark:text-white transition-all">
                        <option value="">All Operations</option>
                        <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>

                {{-- Pencarian Kata Kunci Deskripsi / Operator --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Search Trail</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search operator or description..." 
                            class="w-full text-xs pl-3 pr-8 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500 dark:text-white transition-all">
                        @if(request('search'))
                            <a href="{{ route('activity-log.index', request()->except('search')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-white text-xs">&times;</a>
                        @endif
                    </div>
                </div>

                {{-- Tombol Pemicu Filter --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-gray-900 dark:bg-slate-700 hover:bg-gray-800 text-white text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> Apply Filter
                    </button>
                    @if(request()->anyFilled(['action', 'search']))
                        <a href="{{ route('activity-log.index') }}" class="py-2.5 px-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-xl transition-colors" title="Clear Filters">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div class="pt-3 border-t border-gray-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    Forensic Reporting Tools
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    {{-- Export PDF --}}
                    <a href="{{ route('activity-log.pdf', request()->query()) }}" class="flex-1 sm:flex-initial px-4 py-2 bg-amber-50 dark:bg-amber-900/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-amber-100 dark:hover:bg-amber-900/20 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-accent-gold"></i> Export PDF
                    </a>                    
                    {{-- Export Excel --}}
                    <a href="{{ route('activity-log.excel', request()->query()) }}" class="flex-1 sm:flex-initial px-4 py-2 bg-emerald-50 dark:bg-emerald-900/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/30 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-emerald-100 dark:hover:bg-emerald-900/20 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="sheet" class="w-3.5 h-3.5"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- STRUKTUR UTAMA TABEL JURNAL AUDIT --}}
        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Timestamp</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Operator</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Operation</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Audit Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-5 text-xs font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $log->created_at->format('d M Y • H:i') }}
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">
                                {{ $log->user->name ?? 'System Process' }}
                            </p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border
                                {{ $log->action === 'created' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-900/30' : '' }}
                                {{ $log->action === 'updated' ? 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-900/30' : '' }}
                                {{ $log->action === 'deleted' ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30' : '' }}
                            ">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-600 dark:text-gray-400 italic">
                            "{{ $log->description }}"
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-400 dark:text-gray-500 font-medium italic">
                            No forensic timelines match your filter criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $logs->links() }}
        </div>
    </x-card>
</div>

{{-- SCRIPT POPUP MODERN SWEETALERT2 UNTUK AKSI DI TENGAH SCREEN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDarkMode = document.documentElement.classList.contains('dark');

    function confirmClearLegacyLogs(event) {
        event.preventDefault();
        const form = event.target.closest('form');

        Swal.fire({
            title: 'Purge Legacy Logs?',
            text: "Are you sure you want to completely archive and clear system history records older than 30 days?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: isDarkMode ? '#334155' : '#6b7280',
            confirmButtonText: 'Yes, Purge History',
            background: isDarkMode ? '#1e293b' : '#ffffff',
            color: isDarkMode ? '#ffffff' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 dark:border-slate-800 shadow-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection