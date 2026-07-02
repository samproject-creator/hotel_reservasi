@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Audit Trail</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">A forensic timeline of all critical system operations.</p>
        </div>
        <form action="{{ route('activity-log.clear-old') }}" method="POST" onsubmit="return confirm('Archive logs older than 30 days?')">
            @csrf
            <button type="submit" class="px-6 py-3 bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-400 font-black rounded-2xl hover:bg-red-100 transition-all border border-red-200 dark:border-red-900/30 text-xs uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Clear Legacy History
            </button>
        </form>
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-8 -mb-8">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Timestamp</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Operator</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Operation</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Audit Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-5 text-xs font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $log->created_at->format('d M Y • H:i') }}</td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ $log->user->name ?? 'System Process' }}</p>
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
                        <td class="px-8 py-5 text-sm text-gray-600 dark:text-gray-400 italic">"{{ $log->description }}"</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $logs->links() }}
        </div>
    </x-card>
</div>
@endsection
