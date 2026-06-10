<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessIdentityVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class IdentityApiController extends Controller
{
    public function upload(Request $request)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        $request->validate([
            'ktp_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'selfie_base64' => ['required', 'string'],
            'selfie_ktp_base64' => ['required', 'string'],
        ]);

        try {
            $baseUploadPath = base_path('../storage/identity-verifications/' . $user->id);

            if (! File::exists($baseUploadPath)) {
                File::makeDirectory($baseUploadPath, 0755, true);
            }

            if ($user->ktp_photo) {
                $this->deleteIdentityFile($user->ktp_photo);
            }

            if ($user->selfie_photo) {
                $this->deleteIdentityFile($user->selfie_photo);
            }

            if ($user->selfie_ktp_photo) {
                $this->deleteIdentityFile($user->selfie_ktp_photo);
            }

            $ktpFile = $request->file('ktp_photo');

            $ktpName = 'ktp_' . time() . '_' . uniqid() . '.' . $ktpFile->getClientOriginalExtension();

            $ktpFile->move($baseUploadPath, $ktpName);

            $selfieName = $this->saveBase64Image(
                $request->input('selfie_base64'),
                $baseUploadPath,
                'selfie'
            );

            $selfieKtpName = $this->saveBase64Image(
                $request->input('selfie_ktp_base64'),
                $baseUploadPath,
                'selfie_ktp'
            );

            $ktpPath = 'identity-verifications/' . $user->id . '/' . $ktpName;
            $selfiePath = 'identity-verifications/' . $user->id . '/' . $selfieName;
            $selfieKtpPath = 'identity-verifications/' . $user->id . '/' . $selfieKtpName;

            $user->forceFill([
                'identity_status' => 'pending',
                'ktp_photo' => $ktpPath,
                'selfie_photo' => $selfiePath,
                'selfie_ktp_photo' => $selfieKtpPath,
                'identity_rejection_reason' => null,
                'identity_submitted_at' => now(),
                'identity_verified_at' => null,
                'identity_verified_by' => null,
                'biometric_result' => null,
                'biometric_checked_at' => null,
            ])->save();

            ProcessIdentityVerification::dispatch($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Data verifikasi berhasil dikirim. Sistem sedang memproses verifikasi otomatis.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'identity_status' => $user->identity_status,
                    'identity_submitted_at' => $user->identity_submitted_at,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim data verifikasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function status(Request $request)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'identity_status' => $user->identity_status,
                'identity_rejection_reason' => $user->identity_rejection_reason,
                'identity_submitted_at' => $user->identity_submitted_at,
                'identity_verified_at' => $user->identity_verified_at,
                'biometric_result' => $user->biometric_result,
                'biometric_checked_at' => $user->biometric_checked_at,
            ],
        ]);
    }

    private function saveBase64Image(string $base64Image, string $folderPath, string $prefix): string
    {
        if (! str_contains($base64Image, 'base64,')) {
            throw new \Exception('Format foto live selfie tidak valid.');
        }

        $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);

        $decodedImage = base64_decode($base64Image);

        if ($decodedImage === false) {
            throw new \Exception('Foto live selfie gagal diproses.');
        }

        $fileName = $prefix . '_' . time() . '_' . uniqid() . '.jpg';

        File::put($folderPath . '/' . $fileName, $decodedImage);

        return $fileName;
    }

    private function deleteIdentityFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $filePath = base_path('../storage/' . $path);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    private function userFromToken(Request $request): ?User
    {
        $header = $request->header('Authorization');

        if (! $header || ! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        $plainToken = substr($header, 7);

        return User::where('api_token_hash', hash('sha256', $plainToken))->first();
    }
}