<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        // Prioritaskan setting dari database, fallback ke config/env
        $this->token = \App\Models\Setting::get('fonnte_token', config('services.fonnte.token', '')) ?? '';
    }

    /**
     * Mengirim pesan WhatsApp via Fonnte
     */
    public function sendMessage(string $target, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('Fonnte token is not set. WhatsApp message not sent.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl . '/send', [
                'target' => $target,
                'message' => $message,
                'delay' => '2',
                'countryCode' => '62', // Default Indonesia
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Fonnte API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }
}
