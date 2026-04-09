<?php

namespace App\Http\Controllers;
use App\Models\Kamar;

use Illuminate\Http\Request;

class KamarController extends Controller
{
   public function index()
{
    $kamars = Kamar::all();

    return view('welcome', compact('kamars'));
}
}
