<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTipeKamarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_tipe'       => 'required|string|max:50|unique:tipe_kamar,nama_tipe,' . $this->tipe_kamar->id,
            'harga_per_malam' => 'required|numeric|min:0',
            'kapasitas'       => 'required|integer|min:1',
            'deskripsi'       => 'nullable|string',
            'fasilitas'       => 'nullable|string',
        ];
    }
}
