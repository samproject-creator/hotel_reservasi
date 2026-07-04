<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Http\Requests\StoreTamuRequest;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tamu::class, 'tamu');
    }

    public function index(Request $request)
    {
        $query = Tamu::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        $tamus = $query->latest()->paginate(10)->withQueryString();
        return view('tamu.index', compact('tamus'));
    }

    public function create()
    {
        return view('tamu.create');
    }

    public function store(StoreTamuRequest $request)
    {
        Tamu::create($request->validated());

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil ditambahkan.');
    }

    public function show(Tamu $tamu)
    {
        $tamu->load('bookings.kamars.tipeKamar');
        return view('tamu.show', compact('tamu'));
    }

    public function edit(Tamu $tamu)
    {
        return view('tamu.edit', compact('tamu'));
    }

    public function update(Request $request, Tamu $tamu)
    {
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:100',
            'nik'             => 'required|string|size:16|unique:tamu,nik,' . $tamu->id,
            'email'           => 'nullable|email|max:100',
            'no_hp'           => 'required|string|max:20',
            'alamat'          => 'nullable|string',
            'jenis_kelamin'   => 'required|in:L,P',
            'tanggal_lahir'   => 'nullable|date|before:today',
            'pekerjaan'       => 'nullable|string|max:100',
            'kewarganegaraan' => 'nullable|string|max:50',
        ]);

        $tamu->update($validated);

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Tamu $tamu)
    {
        if ($tamu->bookings()->whereIn('status', ['confirmed', 'checkin'])->exists()) {
            return back()->with('error', 'Tamu masih memiliki booking aktif.');
        }

        $tamu->delete();
        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil dihapus.');
    }
}
