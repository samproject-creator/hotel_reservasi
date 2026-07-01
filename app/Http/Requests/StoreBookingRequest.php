<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tamu_id'          => 'required|exists:tamu,id',
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_tamu'      => 'required|integer|min:1',
            'kamar_ids'        => 'required|array|min:1',
            'kamar_ids.*'      => 'exists:kamar,id',
            'uang_muka'        => 'nullable|numeric|min:0',
            'catatan'          => 'nullable|string',
        ];
    }
}
