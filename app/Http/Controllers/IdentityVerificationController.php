<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessIdentityVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class IdentityVerificationController extends Controller
{
    public function create(Request $request)
    {
        return view('identity-verification.create', [
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ktp_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'selfie_base64' => ['required', 'string'],
            'selfie_ktp_base64' => ['required', 'string'],
        ]);

        $user = $request->user();

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

        return Redirect::route('identity-verification.create')
            ->with('success', 'Data verifikasi berhasil dikirim. Sistem sedang memproses verifikasi otomatis. Silakan refresh beberapa saat lagi.');
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
}