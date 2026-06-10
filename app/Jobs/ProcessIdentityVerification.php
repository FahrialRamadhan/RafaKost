<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ProcessIdentityVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries = 2;

    public function __construct(
        public int $userId
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        if (! $user->ktp_photo || ! $user->selfie_photo) {
            $user->update([
                'identity_status' => 'manual_review',
                'identity_rejection_reason' => 'File KTP atau live selfie tidak ditemukan.',
                'biometric_checked_at' => now(),
            ]);

            return;
        }

        $ktpFullPath = base_path('../storage/' . $user->ktp_photo);
        $selfieFullPath = base_path('../storage/' . $user->selfie_photo);

        if (! File::exists($ktpFullPath) || ! File::exists($selfieFullPath)) {
            $user->update([
                'identity_status' => 'manual_review',
                'identity_rejection_reason' => 'File verifikasi tidak ditemukan di storage.',
                'biometric_checked_at' => now(),
            ]);

            return;
        }

        try {
            $biometricUrl = rtrim((string) config('services.biometric.url'), '/') . '/api/verify';

            $response = Http::timeout(300)
                ->withHeaders([
                    'X-API-SECRET' => config('services.biometric.secret'),
                ])
                ->attach(
                    'ktp_photo',
                    File::get($ktpFullPath),
                    basename($user->ktp_photo)
                )
                ->attach(
                    'selfie_photo',
                    File::get($selfieFullPath),
                    basename($user->selfie_photo)
                )
                ->post($biometricUrl);

            $data = $response->json();

            if (! $response->ok() || ! is_array($data) || ! isset($data['status'])) {
                $user->update([
                    'identity_status' => 'manual_review',
                    'identity_rejection_reason' => 'Response biometric tidak valid atau gagal.',
                    'biometric_result' => json_encode([
                        'http_status' => $response->status(),
                        'url' => $biometricUrl,
                        'raw_response' => $response->body(),
                    ]),
                    'biometric_checked_at' => now(),
                ]);

                return;
            }

            $final = $this->decideFinalStatus($data);

            $user->update([
                'identity_status' => $final['status'],
                'identity_rejection_reason' => $final['reason'],
                'identity_verified_at' => $final['status'] === 'approved' ? now() : null,
                'identity_verified_by' => null,
                'biometric_result' => json_encode($data),
                'biometric_checked_at' => now(),
            ]);

        } catch (\Throwable $e) {
            $user->update([
                'identity_status' => 'manual_review',
                'identity_rejection_reason' => 'Verifikasi otomatis gagal, perlu dicek admin.',
                'biometric_result' => json_encode([
                    'error' => $e->getMessage(),
                ]),
                'biometric_checked_at' => now(),
            ]);
        }
    }

    private function decideFinalStatus(?array $data): array
    {
        if (! $data || ! isset($data['status'])) {
            return [
                'status' => 'manual_review',
                'reason' => 'Response biometric tidak valid atau gagal.',
            ];
        }

        $mlStatus = $data['status'];

        $allowedStatuses = [
            'approved',
            'approved_with_notes',
            'rejected',
            'manual_review',
        ];

        if (! in_array($mlStatus, $allowedStatuses, true)) {
            return [
                'status' => 'manual_review',
                'reason' => 'Status biometric tidak dikenal: ' . $mlStatus,
            ];
        }

        if ($mlStatus === 'rejected') {
            return [
                'status' => 'rejected',
                'reason' => $data['message']
                    ?? $data['reason']
                    ?? 'Verifikasi biometric ditolak otomatis.',
            ];
        }

        if ($mlStatus === 'manual_review') {
            return [
                'status' => 'manual_review',
                'reason' => $data['message']
                    ?? 'Hasil verifikasi wajah perlu dicek manual oleh admin.',
            ];
        }

        $ktpGender = $data['ktp_attributes']['gender'] ?? null;
        $selfieGender = $data['selfie_attributes']['gender'] ?? null;

        $ktpMaleScore = $data['ktp_attributes']['gender_scores']['male'] ?? 0;
        $selfieMaleScore = $data['selfie_attributes']['gender_scores']['male'] ?? 0;

        $ktpFemaleScore = $data['ktp_attributes']['gender_scores']['female'] ?? 0;
        $selfieFemaleScore = $data['selfie_attributes']['gender_scores']['female'] ?? 0;

        $isMaleHighConfidence =
            ($ktpGender === 'male' && $ktpMaleScore >= 80) ||
            ($selfieGender === 'male' && $selfieMaleScore >= 80);

        $isFemaleHighConfidence =
            ($ktpGender === 'female' && $ktpFemaleScore >= 65) ||
            ($selfieGender === 'female' && $selfieFemaleScore >= 65);

        if ($isMaleHighConfidence) {
            return [
                'status' => 'rejected',
                'reason' => 'Verifikasi ditolak karena Rafakost khusus perempuan.',
            ];
        }

        if (! $isFemaleHighConfidence) {
            return [
                'status' => 'manual_review',
                'reason' => 'Gender tidak terdeteksi dengan yakin, perlu review admin.',
            ];
        }

        if ($mlStatus === 'approved') {
            return [
                'status' => 'approved',
                'reason' => null,
            ];
        }

        if ($mlStatus === 'approved_with_notes') {
            return [
                'status' => 'manual_review',
                'reason' => $data['message']
                    ?? 'Wajah cocok, namun ada catatan. Perlu review admin.',
            ];
        }

        return [
            'status' => 'manual_review',
            'reason' => 'Hasil biometric perlu dicek admin.',
        ];
    }
}