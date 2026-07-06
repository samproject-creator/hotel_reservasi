<?php

namespace App\Http\Controllers;

use App\Models\TipeKamar;
use App\Http\Requests\StoreTipeKamarRequest;
use App\Http\Requests\UpdateTipeKamarRequest;
use Illuminate\Http\Request;

class TipeKamarController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TipeKamar::class, 'tipe_kamar');
    }

    public function index(Request $request)
    {
        $query = TipeKamar::query();

        if ($request->filled('search')) {
            $query->where('nama_tipe', 'like', '%' . $request->search . '%');
        }

        $tipekamar = $query->orderBy('nama_tipe')->paginate(10)->withQueryString();
        
        return view('tipe_kamar.index', compact('tipekamar')); 
    }

    public function create()
    {
        return view('tipe_kamar.create');
    }
    public function store(StoreTipeKamarRequest $request)
    {
        $validated = $request->validated();

        if ($request->filled('fasilitas')) {
            $validated['fasilitas'] = array_map('trim', explode(',', $request->fasilitas));
        }

        TipeKamar::create($validated);

        return redirect()->route('tipe-kamar.index')->with('success', 'Tipe kamar baru berhasil ditambahkan.');
    }

    public function update(UpdateTipeKamarRequest $request, TipeKamar $tipeKamar)
    {
        $validated = $request->validated();

        if ($request->filled('fasilitas')) {
            $validated['fasilitas'] = array_map('trim', explode(',', $request->fasilitas));
        } else {
            $validated['fasilitas'] = [];
        }

        $tipeKamar->update($validated);

        return redirect()->route('tipe-kamar.index')->with('success', 'Data tipe kamar berhasil diperbarui.');
    }

    public function edit(TipeKamar $tipeKamar)
    {
        return view('tipe_kamar.edit', compact('tipeKamar'));
    }

    public function destroy(TipeKamar $tipeKamar)
    {
        if ($tipeKamar->kamar()->count() > 0) {
            return back()->with('error', 'Gagal memusnahkan! Tipe ini masih terikat dengan beberapa entitas nomor kamar.');
        }

        $tipeKamar->delete();
        return redirect()->route('tipe-kamar.index')->with('success', 'Tipe kamar berhasil dihapus.');
    }
}