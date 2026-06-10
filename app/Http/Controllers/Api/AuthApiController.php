<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $plainToken = Str::random(80);

        $user->forceFill([
            'api_token_hash' => hash('sha256', $plainToken),
            'api_token_created_at' => now(),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $plainToken,
            'user' => $this->userData($user),
        ]);
    }

    public function profile(Request $request)
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
            'user' => $this->userData($user),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $this->userFromToken($request);

        if ($user) {
            $user->forceFill([
                'api_token_hash' => null,
                'api_token_created_at' => null,
            ])->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
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

    private function userData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
            'identity_status' => $user->identity_status,
            'notify_empty_room_email' => (bool) $user->notify_empty_room_email,
            'notify_empty_room_whatsapp' => (bool) $user->notify_empty_room_whatsapp,
        ];
    }
}