<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function send(string $phone, string $message): bool
    {
        $settings = app(SettingService::class);

        if (! $settings->get('notification.whatsapp_enabled', false)) {
            return false;
        }

        $token = $settings->get('fonnte.token');

        if (! $token) {
            return false;
        }

        $target = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($target, '0')) {
            $target = '62' . substr($target, 1);
        }

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => $settings->get('fonnte.country_code', '62'),
                ]);

            Log::info('Fonnte response', [
                'target' => $target,
                'body' => $response->body(),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Fonnte error: ' . $e->getMessage());
            return false;
        }
    }
}