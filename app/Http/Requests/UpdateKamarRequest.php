<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKamarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nomor_kamar'   => 'required|string|max:10|unique:kamar,nomor_kamar,' . $this->kamar->id,
            'tipe_kamar_id' => 'required|exists:tipe_kamar,id',
            'lantai'        => 'required|integer|min:1',
            'status'        => 'required|in:tersedia,ditempati,maintenance',
            'keterangan'    => 'nullable|string',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
}
