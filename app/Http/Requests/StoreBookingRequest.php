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
            'tanggal_checkout' => 'required|date|after_or_equal:tanggal_checkin',
            'jumlah_tamu'      => 'required|integer|min:1',
            'kamar_ids'        => 'required|array|min:1',
            'kamar_ids.*'      => 'exists:kamar,id',
            'uang_muka'        => 'nullable|numeric|min:0',
            'catatan'          => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $checkin = $this->input('tanggal_checkin');
            $checkout = $this->input('tanggal_checkout');
            $kamarIds = $this->input('kamar_ids');
            $dp = $this->input('uang_muka', 0);

            if ($checkin && $checkout && $kamarIds) {
                $days = max(1, \Carbon\Carbon::parse($checkin)->diffInDays(\Carbon\Carbon::parse($checkout)));
                $totalPrice = \App\Models\Kamar::whereIn('id', $kamarIds)
                    ->with('tipeKamar')
                    ->get()
                    ->sum(fn($k) => $k->tipeKamar->harga_per_malam * $days);

                if ($dp > $totalPrice) {
                    $validator->errors()->add('uang_muka', 'Uang muka tidak boleh melebihi total harga (Rp ' . number_format($totalPrice) . ').');
                }
            }
        });
    }
}
