@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Activity Log</h1>
        <form action="{{ route('activity-log.clear-old') }}" method="POST" onsubmit="return confirm('Clear logs older than 30 days?')">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-50 text-red-700 font-bold rounded-xl hover:bg-red-100 transition-colors border border-red-200 text-sm flex items-center gap-2">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Clear History
            </button>
        </form>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase border
                                {{ $log->action === 'created' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                {{ $log->action === 'updated' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                {{ $log->action === 'deleted' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                            ">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $log->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </x-card>
</div>
@endsection
