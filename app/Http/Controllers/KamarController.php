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
}
