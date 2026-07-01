@extends('layouts.app')

@section('title', 'Guests')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Guests</h1>
        <a href="{{ route('tamu.create') }}" class="px-4 py-2 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-colors flex items-center gap-2">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            Add Guest
        </a>
    </div>

    <x-card>
        <form action="{{ route('tamu.index') }}" method="GET" class="mb-6">
            <div class="relative max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, NIK, or phone..." class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 dark:text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($tamus as $tamu)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tamu->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tamu->kewarganegaraan }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $tamu->nik }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $tamu->no_hp }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tamu->email }}</p>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <a href="{{ route('tamu.show', $tamu) }}" class="text-primary-600 hover:text-primary-700" title="View"><i data-lucide="eye" class="w-5 h-5"></i></a>
                            <a href="{{ route('tamu.edit', $tamu) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><i data-lucide="edit-3" class="w-5 h-5"></i></a>
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('tamu.destroy', $tamu) }}" method="POST" onsubmit="return confirm('Delete this guest?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-700" title="Delete"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $tamus->links() }}
        </div>
    </x-card>
</div>
@endsection
