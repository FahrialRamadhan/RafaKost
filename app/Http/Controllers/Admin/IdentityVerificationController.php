<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class IdentityVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', '!=', 'admin')
            ->latest();

        if ($request->filled('status')) {
            $query->where('identity_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::where('role', '!=', 'admin')->count(),
            'unverified' => User::where('role', '!=', 'admin')->where('identity_status', 'unverified')->count(),
            'pending' => User::where('role', '!=', 'admin')->where('identity_status', 'pending')->count(),
            'manual_review' => User::where('role', '!=', 'admin')->where('identity_status', 'manual_review')->count(),
            'approved' => User::where('role', '!=', 'admin')->where('identity_status', 'approved')->count(),
            'rejected' => User::where('role', '!=', 'admin')->where('identity_status', 'rejected')->count(),
        ];

        return view('admin.identity-verifications.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        if ($user->role === 'admin') {
            abort(404);
        }

        $biometricResult = null;

        if ($user->biometric_result) {
            $biometricResult = json_decode($user->biometric_result, true);
        }

        return view('admin.identity-verifications.show', compact('user', 'biometricResult'));
    }

    public function approve(User $user)
    {
        if ($user->role === 'admin') {
            abort(404);
        }

        if (! $user->ktp_photo || ! $user->selfie_photo) {
            return back()->with('error', 'User belum upload foto KTP dan live selfie.');
        }

        $user->update([
            'identity_status' => 'approved',
            'identity_rejection_reason' => null,
            'identity_verified_at' => now(),
            'identity_verified_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.identity-verifications.show', $user)
            ->with('success', 'Identitas user berhasil disetujui.');
    }

    public function reject(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(404);
        }

        $request->validate([
            'identity_rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->update([
            'identity_status' => 'rejected',
            'identity_rejection_reason' => $request->identity_rejection_reason,
            'identity_verified_at' => null,
            'identity_verified_by' => null,
        ]);

        return redirect()
            ->route('admin.identity-verifications.show', $user)
            ->with('success', 'Identitas user berhasil ditolak.');
    }

    public function reset(User $user)
    {
        if ($user->role === 'admin') {
            abort(404);
        }

        $user->update([
            'identity_status' => 'unverified',
            'identity_rejection_reason' => null,
            'identity_verified_at' => null,
            'identity_verified_by' => null,
        ]);

        return redirect()
            ->route('admin.identity-verifications.show', $user)
            ->with('success', 'Status verifikasi user berhasil direset.');
    }

    public function file(User $user, string $type)
    {
        if ($user->role === 'admin') {
            abort(404);
        }

        if (! in_array($type, ['ktp', 'selfie', 'selfie_ktp'], true)) {
            abort(404);
        }

        $path = match ($type) {
            'ktp' => $user->ktp_photo,
            'selfie' => $user->selfie_photo,
            'selfie_ktp' => $user->selfie_ktp_photo,
        };

        if (! $path) {
            abort(404);
        }

        $filePath = base_path('../storage/' . $path);

        if (! File::exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }
}