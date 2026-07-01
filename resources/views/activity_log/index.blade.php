@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="space-y-6">
    {{-- HEADER & MAINTENACE BUTTON --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl text-purple-200 font-bold flex items-center gap-2">
                <i data-lucide="terminal" class="w-6 h-6 text-purple-400"></i> Audit Trail & Log Aktivitas
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Rekam jejak digital operasional, tindakan user, dan mutasi data pada sistem hotel.</p>
        </div>
        
        {{-- Tombol Maintenance Clear Log (Hanya Admin) --}}
        <form action="{{ route('activity-log.clear-old') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log usang?')" class="flex items-center gap-2 text-xs">
            @csrf
            <select name="hari" required class="px-2 py-1.5 bg-slate-950 border border-red-950 rounded text-red-300 focus:outline-none">
                <option value="30">Lebih dari 30 Hari</option>
                <option value="90">Lebih dari 90 Hari</option>
                <option value="180">Lebih dari 180 Hari</option>
            </select>
            <button type="submit" class="px-3 py-1.5 bg-red-950/40 hover:bg-red-900/40 text-red-300 border border-red-900/40 font-semibold rounded transition-colors">
                Bersihkan Log
            </button>
        </form>
    </div>

    {{-- ADVANCED FILTER SESUAI CONTROLLER --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-4 shadow-sm">
        <form action="{{ route('activity-log.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs">
            {{-- Search Bar --}}
            <div class="relative sm:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi aktivitas..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 transition-all">
            </div>

            {{-- Filter User --}}
            <div>
                <select name="user_id" class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-300 focus:outline-none focus:border-purple-600">
                    <option value="">-- Semua Operator --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Module --}}
            <div>
                <select name="module" class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-300 focus:outline-none focus:border-purple-600">
                    <option value="">-- Semua Modul --</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ strtoupper($mod) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Action --}}
            <div>
                <select name="action" class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-300 focus:outline-none focus:border-purple-600">
                    <option value="">-- Semua Aksi --</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ strtoupper($act) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date Range --}}
            <div class="sm:col-span-2 flex items-center gap-2">
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-300 focus:outline-none focus:border-purple-600">
                <span class="text-slate-500">s/d</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-300 focus:outline-none focus:border-purple-600">
            </div>

            {{-- Submit & Export Buttons --}}
            <div class="sm:col-span-3 flex items-center justify-end gap-2">
                <button type="submit" class="px-4 py-2 bg-purple-950/60 hover:bg-purple-900/60 text-purple-300 font-medium border border-purple-900/40 rounded transition-colors">
                    Terapkan Filter
                </button>
                <a href="{{ route('activity-log.excel', request()->all()) }}" class="px-3 py-2 bg-emerald-950/40 hover:bg-emerald-900/40 text-emerald-300 border border-emerald-900/40 font-medium rounded flex items-center gap-1.5 transition-colors">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Excel
                </a>
                <a href="{{ route('activity-log.pdf', request()->all()) }}" class="px-3 py-2 bg-red-950/40 hover:bg-red-900/40 text-red-300 border border-red-900/40 font-medium rounded flex items-center gap-1.5 transition-colors">
                    <i data-lucide="file-text" class="w-4 h-4"></i> PDF
                </a>
            </div>
        </form>
    </div>

    {{-- KONSOL LOG TABLE --}}
    <div class="bg-slate-900/40 border border-purple-950 rounded-lg p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse font-mono">
                <thead>
                    <tr class="border-b border-purple-950 text-slate-400">
                        <th class="py-3 px-4">Waktu Sistem</th>
                        <th class="py-3 px-4">Operator</th>
                        <th class="py-3 px-4">Modul</th>
                        <th class="py-3 px-4">Aksi Event</th>
                        <th class="py-3 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3 px-4 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-950/20 text-slate-300 text-[11px]">
                    @forelse($logs as $log)
                    <tr class="hover:bg-purple-950/5 transition-colors">
                        <td class="py-3 px-4 text-slate-500">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="py-3 px-4 font-bold text-purple-300">
                            {{ $log->user->name ?? 'System Automated' }}
                        </td>
                        <td class="py-3 px-4 text-slate-400 font-semibold">
                            <span class="bg-slate-950 px-1.5 py-0.5 rounded border border-purple-950/50 text-[10px]">
                                {{ strtoupper($log->module) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if(in_array(strtolower($log->action), ['created', 'store', 'insert', 'add']))
                                <span class="text-emerald-400 font-bold">+ {{ strtoupper($log->action) }}</span>
                            @elseif(in_array(strtolower($log->action), ['updated', 'update', 'edit']))
                                <span class="text-sky-400 font-bold">~ {{ strtoupper($log->action) }}</span>
                            @else
                                <span class="text-red-400 font-bold">- {{ strtoupper($log->action) }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-200 truncate max-w-xs">
                            {{ $log->description }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            {{-- Mengarah ke public function show() controller kamu --}}
                            <a href="{{ route('activity-log.show', $log->id) }}" class="text-purple-400 hover:text-purple-300 underline text-[10px]">
                                Inspect
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 italic">Tidak ada rekaman aktivitas yang cocok dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 font-sans">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection