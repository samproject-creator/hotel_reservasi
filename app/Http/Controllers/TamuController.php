<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Http\Requests\StoreTamuRequest;
use App\Http\Requests\UpdateTamuRequest;
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
        $tamu->load('bookings.kamar.tipeKamar');
        return view('tamu.show', compact('tamu'));
    }

    public function edit(Tamu $tamu)
    {
        return view('tamu.edit', compact('tamu'));
    }

    public function update(UpdateTamuRequest $request, Tamu $tamu)
    {
        $tamu->update($request->validated());

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
