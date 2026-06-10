<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kamar;

class KamarApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Kamar::where('status', 'tersedia')
                ->latest()
                ->get()
                ->map(function ($kamar) {
                    return [
                        'id' => $kamar->id,
                        'nama' => $kamar->nama,
                        'lantai' => $kamar->lantai,
                        'kamar_mandi' => $kamar->kamar_mandi,
                        'harga' => $kamar->harga,
                        'harga_1_orang' => $kamar->harga_1_orang,
                        'harga_2_orang' => $kamar->harga_2_orang,
                        'description' => $kamar->description,
                        'status' => $kamar->status,
                        'image' => $kamar->image ? asset('storage/' . $kamar->image) : null,
                        'images' => $kamar->images ? json_decode($kamar->images, true) : [],
                    ];
                }),
        ]);
    }

    public function show(Kamar $kamar)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $kamar->id,
                'nama' => $kamar->nama,
                'lantai' => $kamar->lantai,
                'kamar_mandi' => $kamar->kamar_mandi,
                'harga' => $kamar->harga,
                'harga_1_orang' => $kamar->harga_1_orang,
                'harga_2_orang' => $kamar->harga_2_orang,
                'description' => $kamar->description,
                'status' => $kamar->status,
                'image' => $kamar->image ? asset('storage/' . $kamar->image) : null,
                'images' => $kamar->images ? json_decode($kamar->images, true) : [],
            ],
        ]);
    }
}