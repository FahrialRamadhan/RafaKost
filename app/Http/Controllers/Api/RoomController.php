<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kamar;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Kamar::all()->map(function ($room) {
            return [
                'id' => $room->id,
                'nama' => $room->nama,
                'lantai' => $room->lantai,
                'kamar_mandi' => $room->kamar_mandi,
                'harga' => $room->harga,
                'image' => $room->image,
                'status' => $room->status,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $rooms
        ]);
    }

    public function show($id)
{
    $room = Kamar::find($id);

    if (!$room) {
        return response()->json([
            'success' => false,
            'message' => 'Kamar tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $room->id,
            'nama' => $room->nama,
            'lantai' => $room->lantai,
            'kamar_mandi' => $room->kamar_mandi,
            'harga' => $room->harga,
            'harga_1_orang' => $room->harga_1_orang,
            'harga_2_orang' => $room->harga_2_orang,
            'image' => $room->image,
            'status' => $room->status,
        ]
    ]);
}
}