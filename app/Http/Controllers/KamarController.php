<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $query = Kamar::with('tipeKamar');

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipe_kamar_id')) {
            $query->where('tipe_kamar_id', $request->tipe_kamar_id);
        }

        $kamars     = $query->orderBy('nomor_kamar')->paginate(10)->withQueryString();
        $tipeKamars = TipeKamar::orderBy('nama_tipe')->get();

        return view('kamar.index', compact('kamars', 'tipeKamars'));
    }

    public function create()
    {
        $tipeKamars = TipeKamar::orderBy('nama_tipe')->get();
        return view('kamar.create', compact('tipeKamars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_kamar'   => 'required|string|max:10|unique:kamar',
            'tipe_kamar_id' => 'required|exists:tipe_kamar,id',
            'lantai'        => 'required|integer|min:1',
            'status'        => 'required|in:tersedia,ditempati,maintenance',
            'keterangan'    => 'nullable|string',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048' 
        ]);
        
        $imageNames = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('kamar', 'public');
                $imageNames[] = $path;
            }
        }

        $validated['images'] = $imageNames;
        Kamar::create($validated);

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(Kamar $kamar)
    {
        $kamar->load('tipeKamar', 'bookings.tamu');
        return view('kamar.show', compact('kamar'));
    }

    public function edit(Kamar $kamar)
    {
        $tipeKamars = TipeKamar::orderBy('nama_tipe')->get();
        return view('kamar.edit', compact('kamar', 'tipeKamars'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'nomor_kamar'   => 'required|string|max:10|unique:kamar,nomor_kamar,' . $kamar->id,
            'tipe_kamar_id' => 'required|exists:tipe_kamar,id',
            'lantai'        => 'required|integer|min:1',
            'status'        => 'required|in:tersedia,ditempati,maintenance',
            'keterangan'    => 'nullable|string',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('images')) {
            if (!empty($kamar->images)) {
                foreach ($kamar->images as $oldFoto) {
                    Storage::disk('public')->delete($oldFoto);
                }
            }

            $imageNames = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('kamar', 'public');
                $imageNames[] = $path;
            }
            $validated['images'] = $imageNames;
        }

        $kamar->update($validated);

        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        if ($kamar->status === 'ditempati') {
            return back()->with('error', 'Kamar sedang ditempati, tidak dapat dihapus.');
        }

        if (!empty($kamar->images)) {
            foreach ($kamar->images as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $kamar->delete();
        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}