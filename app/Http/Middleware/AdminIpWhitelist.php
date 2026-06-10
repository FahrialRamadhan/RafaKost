<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WhitelistedIp;

class AdminIpWhitelist
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Kalau bukan admin, jangan tampilkan 403.
        // Langsung lempar ke dashboard user.
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $clientIp = $request->ip();

        $isAllowed = WhitelistedIp::where('ip_address', $clientIp)->exists();

        // Kalau admin tapi IP tidak masuk whitelist,
        // logout lalu arahkan ke login dengan pesan error.
        if (! $isAllowed) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akses Ditolak',
                ]);
        }

        return $next($request);
    }
}