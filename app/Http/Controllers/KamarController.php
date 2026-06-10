<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? $request->q;
        $lantai = $request->lantai;
        $kamarMandi = $request->kamar_mandi;

        $query = Kamar::query()
            ->where('status', 'tersedia');

        if ($search) {
            $keyword = strtolower(trim($search));

            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                    ->orWhere('lantai', 'like', '%' . $keyword . '%')
                    ->orWhere('harga', 'like', '%' . $keyword . '%');

                if (Schema::hasColumn('kamars', 'nomor')) {
                    $q->orWhere('nomor', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn('kamars', 'deskripsi')) {
                    $q->orWhere('deskripsi', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn('kamars', 'fasilitas')) {
                    $q->orWhere('fasilitas', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn('kamars', 'kamar_mandi')) {
                    if (
                        str_contains($keyword, 'kamar mandi dalam') ||
                        str_contains($keyword, 'km dalam') ||
                        str_contains($keyword, 'mandi dalam')
                    ) {
                        $q->orWhere('kamar_mandi', 'dalam');
                    }

                    if (
                        str_contains($keyword, 'kamar mandi luar') ||
                        str_contains($keyword, 'km luar') ||
                        str_contains($keyword, 'mandi luar')
                    ) {
                        $q->orWhere('kamar_mandi', 'luar');
                    }
                }

                if (str_contains($keyword, 'lantai 1')) {
                    $q->orWhere('lantai', 1);
                }

                if (str_contains($keyword, 'lantai 2')) {
                    $q->orWhere('lantai', 2);
                }

                if (str_contains($keyword, 'lantai 3')) {
                    $q->orWhere('lantai', 3);
                }
            });
        }

        if ($lantai) {
            $query->where('lantai', $lantai);
        }

        if ($kamarMandi && Schema::hasColumn('kamars', 'kamar_mandi')) {
            $query->where('kamar_mandi', $kamarMandi);
        }

        $kamars = $query->latest()->get();

        $totalKamar = Kamar::count();
        $totalKamarKosong = Kamar::where('status', 'tersedia')->count();
        $totalKamarTerisi = Kamar::where('status', 'terisi')->count();

        $testimonials = Testimonial::with('user')
            ->where('is_visible', true)
            ->latest()
            ->take(5)
            ->get();

        return view('welcome', compact(
            'kamars',
            'totalKamar',
            'totalKamarKosong',
            'totalKamarTerisi',
            'search',
            'lantai',
            'kamarMandi',
            'testimonials'
        ));
    }

    public function dashboard(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $search = $request->search ?? $request->q;
        $lantai = $request->lantai;
        $kamarMandi = $request->kamar_mandi;

        $query = Kamar::query()
            ->where('status', 'tersedia');

        if ($search) {
            $keyword = strtolower(trim($search));

            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                    ->orWhere('lantai', 'like', '%' . $keyword . '%')
                    ->orWhere('harga', 'like', '%' . $keyword . '%');

                if (Schema::hasColumn('kamars', 'nomor')) {
                    $q->orWhere('nomor', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn('kamars', 'deskripsi')) {
                    $q->orWhere('deskripsi', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn('kamars', 'fasilitas')) {
                    $q->orWhere('fasilitas', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn('kamars', 'kamar_mandi')) {
                    if (
                        str_contains($keyword, 'kamar mandi dalam') ||
                        str_contains($keyword, 'km dalam') ||
                        str_contains($keyword, 'mandi dalam')
                    ) {
                        $q->orWhere('kamar_mandi', 'dalam');
                    }

                    if (
                        str_contains($keyword, 'kamar mandi luar') ||
                        str_contains($keyword, 'km luar') ||
                        str_contains($keyword, 'mandi luar')
                    ) {
                        $q->orWhere('kamar_mandi', 'luar');
                    }
                }

                if (str_contains($keyword, 'lantai 1')) {
                    $q->orWhere('lantai', 1);
                }

                if (str_contains($keyword, 'lantai 2')) {
                    $q->orWhere('lantai', 2);
                }

                if (str_contains($keyword, 'lantai 3')) {
                    $q->orWhere('lantai', 3);
                }
            });
        }

        if ($lantai) {
            $query->where('lantai', $lantai);
        }

        if ($kamarMandi && Schema::hasColumn('kamars', 'kamar_mandi')) {
            $query->where('kamar_mandi', $kamarMandi);
        }

        $kamars = $query->latest()->get();

        $totalKamar = Kamar::count();
        $totalKamarKosong = Kamar::where('status', 'tersedia')->count();
        $totalKamarTerisi = Kamar::where('status', 'terisi')->count();

        $testimonials = Testimonial::with('user')
            ->where('is_visible', true)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'kamars',
            'totalKamar',
            'totalKamarKosong',
            'totalKamarTerisi',
            'search',
            'lantai',
            'kamarMandi',
            'testimonials'
        ));
    }

    public function show(int $id)
    {
        $kamar = Kamar::findOrFail($id);

        return view('kamar.detail', compact('kamar'));
    }
}