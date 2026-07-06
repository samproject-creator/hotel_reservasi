<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\TipeKamar;
use App\Http\Requests\StoreKamarRequest;
use App\Http\Requests\UpdateKamarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Kamar::class, 'kamar');
    }

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

        $kamar     = $query->orderBy('nomor_kamar')->paginate(10)->withQueryString();
        $tipekamar = TipeKamar::orderBy('nama_tipe')->get();

        return view('kamar.index', compact('kamar', 'tipekamar'));
    }

    public function create()
    {
        $tipekamar = TipeKamar::orderBy('nama_tipe')->get();
        return view('kamar.create', compact('tipekamar'));
    }

    public function store(StoreKamarRequest $request)
    {
        $validated = $request->validated();
        
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
        $tipekamar = TipeKamar::orderBy('nama_tipe')->get();
        return view('kamar.edit', compact('kamar', 'tipekamar'));
    }

    public function update(UpdateKamarRequest $request, Kamar $kamar)
    {
        $validated = $request->validated();

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