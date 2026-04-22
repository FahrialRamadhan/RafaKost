<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $kamars = Kamar::where('status', 'tersedia')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('lantai', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->get();

        $totalKamar = Kamar::count();
        $totalKamarKosong = Kamar::where('status', 'tersedia')->count();
        $totalKamarTerisi = Kamar::where('status', 'terisi')->count();

        return view('welcome', compact(
            'kamars',
            'totalKamar',
            'totalKamarKosong',
            'totalKamarTerisi',
            'search'
        ));
    }

    public function dashboard()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $kamars = Kamar::where('status', 'tersedia')->latest()->get();
        $totalKamar = Kamar::count();
        $totalKamarKosong = Kamar::where('status', 'tersedia')->count();
        $totalKamarTerisi = Kamar::where('status', 'terisi')->count();

        return view('dashboard', compact(
            'kamars',
            'totalKamar',
            'totalKamarKosong',
            'totalKamarTerisi'
        ));
    }

    public function show(int $id)
    {
        $kamar = Kamar::findOrFail($id);
        return view('kamar.detail', compact('kamar'));
    }
}
