<?php

namespace App\Http\Controllers;

use App\Models\TipeKamar;
use Illuminate\Http\Request;

class TipeKamarController extends Controller
{
    public function index(Request $request)
    {
        $query = TipeKamar::query();

        if ($request->filled('search')) {
            $query->where('nama_tipe', 'like', '%' . $request->search . '%');
        }

        $tipeKamars = $query->orderBy('nama_tipe')->paginate(10)->withQueryString();
        
        return view('tipe_kamar.index', compact('tipeKamars')); 
    }

    public function create()
    {
        return view('tipe_kamar.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tipe'       => 'required|string|max:50|unique:tipe_kamar,nama_tipe',
            'harga_per_malam' => 'required|numeric|min:0',
            'kapasitas'       => 'required|integer|min:1',
            'deskripsi'       => 'nullable|string',
            'fasilitas'       => 'nullable|string',
        ]);

        // Jika di database kolom fasilitas bertipe json/array, 
        // kita bisa mengubah string koma menjadi array sebelum disimpan:
        if ($request->filled('fasilitas')) {
            // Mengubah "AC, TV" menjadi ["AC", "TV"]
            $validated['fasilitas'] = array_map('trim', explode(',', $request->fasilitas));
        }

        TipeKamar::create($validated);

        return redirect()->route('tipe-kamar.index')->with('success', 'Tipe kamar baru berhasil ditempa.');
    }

    public function update(Request $request, TipeKamar $tipeKamar)
    {
        $validated = $request->validate([
            'nama_tipe'       => 'required|string|max:50|unique:tipe_kamar,nama_tipe,' . $tipeKamar->id,
            'harga_per_malam' => 'required|numeric|min:0',
            'kapasitas'       => 'required|integer|min:1',
            'deskripsi'       => 'nullable|string',
            'fasilitas'       => 'nullable|string',
        ]);

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
        if ($tipeKamar->kamars()->count() > 0) {
            return back()->with('error', 'Gagal memusnahkan! Tipe ini masih terikat dengan beberapa entitas nomor kamar.');
        }

        $tipeKamar->delete();
        return redirect()->route('tipe-kamar.index')->with('success', 'Tipe kamar berhasil dihapus.');
    }
}