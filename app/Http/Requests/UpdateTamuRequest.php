<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTamuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'required|string|size:16|unique:tamu,nik,' . $this->tamu->id,
            'email'           => 'nullable|email|max:255',
            'no_hp'           => 'required|string|max:20',
            'alamat'          => 'required|string',
            'jenis_kelamin'   => 'required|in:L,P',
            'tanggal_lahir'   => 'required|date|before:today',
            'pekerjaan'       => 'nullable|string|max:255',
            'kewarganegaraan' => 'required|string|max:100',
        ];
    }
}
