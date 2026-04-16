<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::latest()->get();
        return view('admin.kamars.index', compact('kamars'));
    }

    public function create()
    {
        return view('admin.kamars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'harga' => 'required|string|max:255',
            'status' => 'required|in:tersedia,terisi',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('kamars', 'public');
        }

        Kamar::create([
            'nama' => $request->nama,
            'lantai' => $request->lantai,
            'harga' => $request->harga,
            'status' => $request->status,
            'image' => $path,
        ]);

        return redirect()->route('kamars.index')->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit(Kamar $kamar)
    {
        return view('admin.kamars.edit', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'harga' => 'required|string|max:255',
            'status' => 'required|in:tersedia,terisi',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'lantai' => $request->lantai,
            'harga' => $request->harga,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('kamars', 'public');
        }

        $kamar->update($data);

        return redirect()->route('kamars.index')->with('success', 'Kamar berhasil diupdate');
    }

    public function destroy(Kamar $kamar)
    {
        $kamar->delete();

        return redirect()->route('kamars.index')->with('success', 'Kamar berhasil dihapus');
    }
}
