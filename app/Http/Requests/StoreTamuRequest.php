<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTamuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:tamu,nik',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'nullable|string|max:255',
            'kewarganegaraan' => 'required|string|max:100',
        ];
    }
}
