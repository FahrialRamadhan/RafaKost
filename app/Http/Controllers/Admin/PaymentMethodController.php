<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::orderBy('gateway_code')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('admin.payment-methods.index', compact('methods'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gateway_code' => ['required', 'in:cashify,tokopay'],
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'fee_fixed' => ['nullable', 'integer', 'min:0'],
            'fee_percent' => ['nullable', 'numeric', 'min:0'],
            'info' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['fee_fixed'] = $data['fee_fixed'] ?? 0;
        $data['fee_percent'] = $data['fee_percent'] ?? 0;
        $data['is_active'] = true;

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $data['logo'] = $this->uploadLogo($request);
        }

        PaymentMethod::create($data);

        return back()->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $data = $request->validate([
            'gateway_code' => ['required', 'in:cashify,tokopay'],
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'fee_fixed' => ['nullable', 'integer', 'min:0'],
            'fee_percent' => ['nullable', 'numeric', 'min:0'],
            'info' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['fee_fixed'] = $data['fee_fixed'] ?? 0;
        $data['fee_percent'] = $data['fee_percent'] ?? 0;

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $this->deleteLogo($paymentMethod->logo);
            $data['logo'] = $this->uploadLogo($request);
        }

        $paymentMethod->update($data);

        return back()->with('success', 'Metode pembayaran berhasil diupdate.');
    }

    public function toggle(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => ! $paymentMethod->is_active,
        ]);

        return back()->with('success', 'Status metode pembayaran berhasil diubah.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $this->deleteLogo($paymentMethod->logo);

        $paymentMethod->delete();

        return back()->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    private function uploadLogo(Request $request): string
    {
        $file = $request->file('logo');

        $uploadPath = base_path('../assets/payment');

        if (! File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $filename = strtolower(time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension());

        $file->move($uploadPath, $filename);

        return '/assets/payment/' . $filename;
    }

    private function deleteLogo(?string $path): void
    {
        if (! $path) {
            return;
        }

        $filePath = base_path('..' . $path);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}