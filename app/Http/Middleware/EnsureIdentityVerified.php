<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIdentityVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->identity_status !== 'approved') {
            return redirect()
                ->route('identity-verification.create')
                ->with('error', 'Akun kamu harus verifikasi KTP dan selfie terlebih dahulu sebelum booking.');
        }

        return $next($request);
    }
}