<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKamar = Kamar::count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();

        $persentaseHunian = $totalKamar > 0
            ? round(($kamarTerisi / $totalKamar) * 100)
            : 0;

        $totalNilaiKamar = Kamar::sum('harga');

        $kamarsTerbaru = Kamar::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalKamar',
            'kamarTerisi',
            'kamarTersedia',
            'persentaseHunian',
            'totalNilaiKamar',
            'kamarsTerbaru'
        ));
    }
}
