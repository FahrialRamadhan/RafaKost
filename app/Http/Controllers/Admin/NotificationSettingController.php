<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    public function index(SettingService $settings)
    {
        return view('admin.notification-settings.index', [
            'settings' => [
                'whatsapp_enabled' => $settings->get('notification.whatsapp_enabled', false),
                'email_enabled' => $settings->get('notification.email_enabled', false),
                'fonnte_token' => $settings->get('fonnte.token', ''),
                'fonnte_country_code' => $settings->get('fonnte.country_code', '62'),
                'fonnte_admin_phone' => $settings->get('fonnte.admin_phone', ''),
                'late_fee_enabled' => $settings->get('late_fee.enabled', false),
                'late_fee_amount_per_day' => $settings->get('late_fee.amount_per_day', 10000),
                'late_fee_grace_days' => $settings->get('late_fee.grace_days', 0),
                'rent_end_days' => implode(',', $settings->get('reminder.rent_end_days', [7, 3, 1, 0])),
                'template_rent_ending' => $settings->get('template.rent_ending', ''),
                'template_late_payment' => $settings->get('template.late_payment', ''),
                'template_empty_room' => $settings->get('template.empty_room', ''),
            ],
        ]);
    }

    public function update(Request $request, SettingService $settings)
    {
        $settings->set('notification.whatsapp_enabled', $request->boolean('whatsapp_enabled'), 'boolean');
        $settings->set('notification.email_enabled', $request->boolean('email_enabled'), 'boolean');

        if ($request->filled('fonnte_token')) {
            $settings->set('fonnte.token', $request->fonnte_token);
        }

        $settings->set('fonnte.country_code', $request->fonnte_country_code ?: '62');
        $settings->set('fonnte.admin_phone', $request->fonnte_admin_phone);

        $settings->set('late_fee.enabled', $request->boolean('late_fee_enabled'), 'boolean');
        $settings->set('late_fee.amount_per_day', (int) $request->late_fee_amount_per_day, 'integer');
        $settings->set('late_fee.grace_days', (int) $request->late_fee_grace_days, 'integer');

        $days = collect(explode(',', $request->rent_end_days))
            ->map(fn ($day) => (int) trim($day))
            ->filter(fn ($day) => $day >= 0)
            ->values()
            ->all();

        $settings->set('reminder.rent_end_days', $days, 'json');

        $settings->set('template.rent_ending', $request->template_rent_ending);
        $settings->set('template.late_payment', $request->template_late_payment);
        $settings->set('template.empty_room', $request->template_empty_room);

        return back()->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    public function testFonnte(Request $request, FonnteService $fonnte)
    {
        $request->validate([
            'test_phone' => ['required', 'string'],
        ]);

        $sent = $fonnte->send(
            $request->test_phone,
            'Test notifikasi dari Rafa Kost berhasil.'
        );

        return back()->with(
            $sent ? 'success' : 'error',
            $sent ? 'Pesan test berhasil dikirim.' : 'Pesan test gagal dikirim. Cek token Fonnte atau nomor tujuan.'
        );
    }
}