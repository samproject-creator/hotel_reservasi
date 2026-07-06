@extends('layouts.app')

@section('title', 'Room Types')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Suite Classifications</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Categorize and price your luxury accommodations.</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('tipe-kamar.create') }}" class="px-6 py-3 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/20 flex items-center gap-2 transform active:scale-95">
            <i data-lucide="layers" class="w-5 h-5 text-accent-gold"></i>
            Define New Class
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($tipekamar as $tipe)
        <x-card class="flex flex-col group">
            <div class="flex-1">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">{{ $tipe->nama_tipe }}</h3>
                    <div class="bg-primary-50 dark:bg-blue-900/20 p-2 rounded-xl text-primary-600">
                        <i data-lucide="hotel" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-8 leading-relaxed italic">"{{ $tipe->deskripsi }}"</p>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Max Occupancy</p>
                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ $tipe->kapasitas }} Persons</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl text-right">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Daily Rate</p>
                        <p class="text-sm font-black text-primary-600">Rp {{ number_format($tipe->harga_per_malam, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">In-Suite Amenities</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($tipe->fasilitas ?? [] as $fasilitas)
                        <span class="px-3 py-1 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 text-gray-600 dark:text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-sm">{{ $fasilitas }}</span>
                        @empty
                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">No standard amenities defined.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="mt-8 pt-8 border-t border-gray-50 dark:border-slate-800 flex items-center gap-3">
                <a href="{{ route('tipe-kamar.edit', $tipe) }}" class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors text-center border border-gray-200 dark:border-slate-700">
                    Edit Class
                </a>
                <form action="{{ route('tipe-kamar.destroy', $tipe) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmDeleteClassification(this, '{{ $tipe->nama_tipe }}')" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors border border-transparent hover:border-red-100 dark:hover:border-red-900/30">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
            @endif
        </x-card>
        @endforeach
    </div>
</div>

{{-- SCRIPT POPUP MODERN SWEETALERT2 UNTUK AKSI DI TENGAH SCREEN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDarkMode = document.documentElement.classList.contains('dark');

    function confirmDeleteClassification(button, className) {
        Swal.fire({
            title: 'Decommission Suite Class?',
            text: `Are you sure you want to permanently remove the "${className}" tier classification from active inventory?`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Merah mewah
            cancelButtonColor: isDarkMode ? '#334155' : '#6b7280',
            confirmButtonText: 'Yes, Decommission Tier',
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