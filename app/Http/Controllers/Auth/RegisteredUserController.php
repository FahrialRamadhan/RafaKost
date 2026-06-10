<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
	public function store(Request $request): RedirectResponse
	{
	    $request->validate([
	        'name' => ['required', 'string', 'max:255'],
	        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
	        'password' => [
	            'required',
	            'confirmed',
	            Rules\Password::min(8)->mixedCase()->numbers(),
	        ],
	    ], [
	        'email.unique' => 'Email sudah terdaftar.',
	        'password.min' => 'Password minimal 8 karakter.',
	        'password.mixed' => 'Password harus mengandung huruf besar dan huruf kecil.',
	        'password.numbers' => 'Password harus mengandung angka.',
	        'password.confirmed' => 'Konfirmasi password tidak sama.',
	    ]);
	
	    $user = User::create([
	        'name' => $request->name,
	        'email' => strtolower($request->email),
	        'password' => Hash::make($request->password),
	    ]);
	
	    event(new Registered($user));
	
	    Auth::login($user);
	
	    return redirect()->route('verification.notice');
	}
}