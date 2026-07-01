<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_checkin'  => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_tamu'      => 'required|integer|min:1',
            'uang_muka'        => 'nullable|numeric|min:0',
            'catatan'          => 'nullable|string',
        ];
    }
}
