<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::orderBy('id')->get();

        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $data = $request->validate([
            'cashify_license_key' => ['nullable', 'string'],
            'cashify_qr_id' => ['nullable', 'string'],

            'tokopay_merchant_id' => ['nullable', 'string'],
            'tokopay_secret_key' => ['nullable', 'string'],
            'tokopay_channel' => ['nullable', 'string'],
        ]);

        $paymentGateway->update($data);

        return back()->with('success', 'Credential ' . $paymentGateway->name . ' berhasil disimpan.');
    }

    public function activate(PaymentGateway $paymentGateway)
    {
        PaymentGateway::query()->update([
            'is_active' => false,
        ]);

        $paymentGateway->update([
            'is_active' => true,
        ]);

        return back()->with('success', $paymentGateway->name . ' berhasil diaktifkan. Gateway lain otomatis OFF.');
    }

    public function deactivate(PaymentGateway $paymentGateway)
    {
        $paymentGateway->update([
            'is_active' => false,
        ]);

        return back()->with('success', $paymentGateway->name . ' berhasil dinonaktifkan.');
    }
}