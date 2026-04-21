<?php

namespace App\Http\Controllers;

use App\Models\Kamar;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::where('status', 'tersedia')->get();

        return view('welcome', compact('kamars'));
    }

    public function dashboard()
{
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $kamars = Kamar::where('status', 'tersedia')->get();

    return view('dashboard', compact('kamars'));
}

public function show(int $id)
{
    $kamar = Kamar::findOrFail($id);
    return view('kamar.detail', compact('kamar'));
}

}
