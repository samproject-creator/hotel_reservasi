@extends('layouts.app')

@section('title', 'Room Details - ' . $kamar->nomor_kamar)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('kamar.index') }}" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-500"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Room Details</h1>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('kamar.edit', $kamar) }}" class="px-4 py-2 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 font-bold rounded-xl hover:bg-amber-100 transition-colors border border-amber-200 dark:border-amber-900/30 text-sm">Edit Room</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Room Info Card --}}
            <x-card>
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/2">
                        @if($kamar->images && count($kamar->images) > 0)
                            <div class="grid grid-cols-1 gap-2">
                                <img src="{{ Storage::url($kamar->images[0]) }}" class="w-full h-64 object-cover rounded-2xl border border-gray-100 dark:border-gray-700">
                                <div class="flex gap-2 overflow-x-auto pb-2">
                                    @foreach($kamar->images as $img)
                                        <img src="{{ Storage::url($img) }}" class="w-20 h-20 object-cover rounded-lg border border-gray-100 dark:border-gray-700 cursor-pointer hover:opacity-80 transition-opacity">
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="w-full h-64 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center border border-gray-200 dark:border-gray-700">
                                <i data-lucide="image" class="w-12 h-12 text-gray-300"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Room {{ $kamar->nomor_kamar }}</h2>
                            <p class="text-lg text-primary-600 font-bold">{{ $kamar->tipeKamar->nama_tipe }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                                <p class="text-[10px] uppercase font-bold text-gray-400">Floor</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-200">Level {{ $kamar->lantai }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                                <p class="text-[10px] uppercase font-bold text-gray-400">Capacity</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ $kamar->tipeKamar->kapasitas }} Persons</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                                <p class="text-[10px] uppercase font-bold text-gray-400">Rate</p>
                                <p class="text-sm font-bold text-primary-600">Rp {{ number_format($kamar->tipeKamar->harga_per_malam) }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                                <p class="text-[10px] uppercase font-bold text-gray-400">Status</p>
                                <div class="mt-1">{!! $kamar->status_badge !!}</div>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-2">Description</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $kamar->keterangan ?? 'No additional description available.' }}</p>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Recent History Card --}}
            <x-card title="Recent Booking History">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guest</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Period</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($kamar->bookings()->latest()->take(5)->get() as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $booking->tamu->nama_lengkap }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $booking->kode_booking }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-700 dark:text-gray-300">{{ $booking->tanggal_checkin->format('d M') }} - {{ $booking->tanggal_checkout->format('d M Y') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    {!! $booking->status_badge !!}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('booking.show', $booking) }}" class="text-primary-600 hover:text-primary-700"><i data-lucide="external-link" class="w-4 h-4 ml-auto"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No booking history available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <div class="space-y-6">
            {{-- Amenities Card --}}
            <x-card title="Amenities">
                <div class="grid grid-cols-1 gap-3">
                    @foreach($kamar->tipeKamar->fasilitas ?? [] as $fas)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 flex items-center justify-center text-primary-600 shadow-sm">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $fas }}</span>
                    </div>
                    @endforeach
                </div>
            </x-card>

            <x-card title="Maintenance Info">
                <div class="p-4 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl">
                    <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed font-medium">
                        Kamar ini selalu dibersihkan dan disterilisasi setiap tamu melakukan check-out sesuai standar LuxeHotel.
                    </p>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
