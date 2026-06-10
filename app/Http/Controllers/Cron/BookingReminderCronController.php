<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\NotificationLog;
use App\Services\FonnteService;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingReminderCronController extends Controller
{
    public function handle(Request $request, SettingService $settings, FonnteService $fonnte)
    {
        $secretKey = 'rafakost-reminder-2026';

        if ($request->query('key') !== $secretKey) {
            abort(403, 'Invalid cron key');
        }

        $today = now()->startOfDay();

        $rentEndingCount = 0;
        $latePaymentCount = 0;

        $bookings = Booking::with('kamar')
            ->whereIn('payment_status', ['pending', 'paid'])
            ->get();

        foreach ($bookings as $booking) {
            if (! $booking->tanggal_masuk) {
                continue;
            }

			$tanggalMasuk = Carbon::parse($booking->tanggal_masuk)->startOfDay();
			$durasi = (int) ($booking->durasi ?? 1);
			
			$tanggalHabis = $booking->tanggal_habis_custom
			    ? Carbon::parse($booking->tanggal_habis_custom)->startOfDay()
			    : $tanggalMasuk->copy()->addMonths($durasi);

            /*
            |--------------------------------------------------------------------------
            | 1. Reminder sewa mau habis
            |--------------------------------------------------------------------------
            */
			if (strtolower($booking->payment_status) === 'paid') {
			    $rentEndDays = $settings->get('reminder.rent_end_days', [7, 3, 1, 0]);
			
			    if (! is_array($rentEndDays)) {
			        $rentEndDays = [7, 3, 1, 0];
			    }
			
			    $rentEndDays = collect($rentEndDays)
			        ->map(fn ($day) => (int) $day)
			        ->values()
			        ->all();
			
			    $sisaHari = (int) $today->diffInDays($tanggalHabis, false);
			
			    if (in_array($sisaHari, $rentEndDays, true)) {
			        $message = $this->makeRentEndingMessage($booking, $tanggalHabis, $sisaHari, $settings);
			
			        $sent = $this->sendNotification(
			            booking: $booking,
			            type: 'rent_ending',
			            message: $message,
			            fonnte: $fonnte,
			            settings: $settings
			        );
			
			        if ($sent) {
			            $rentEndingCount++;
			        }
			    }
			}
            /*
            |--------------------------------------------------------------------------
            | 2. Reminder telat bayar + hitung denda
            |--------------------------------------------------------------------------
            */
            if (strtolower($booking->payment_status) === 'pending') {
                $lateFeeEnabled = $settings->get('late_fee.enabled', true);

                if (! $lateFeeEnabled) {
                    continue;
                }

                $dueDate = $booking->due_date
                    ? Carbon::parse($booking->due_date)->startOfDay()
                    : $tanggalMasuk->copy();

                $graceDays = (int) $settings->get('late_fee.grace_days', 0);
                $effectiveDueDate = $dueDate->copy()->addDays($graceDays);

                $lateDays = $effectiveDueDate->diffInDays($today, false);

                if ($lateDays > 0) {
                    $lateFeePerDay = (int) $settings->get('late_fee.amount_per_day', 10000);
                    $lateFee = $lateDays * $lateFeePerDay;

                    $totalHarga = (int) ($booking->total_harga ?? 0);
                    $paymentFee = (int) ($booking->payment_fee ?? 0);

                    $booking->forceFill([
                        'due_date' => $dueDate->toDateString(),
                        'late_days' => $lateDays,
                        'late_fee' => $lateFee,
                        'payment_total' => $totalHarga + $paymentFee + $lateFee,
                        'last_reminder_sent_at' => now(),
                    ])->save();

                    $message = $this->makeLatePaymentMessage($booking, $lateDays, $lateFee, $settings);

                    $sent = $this->sendNotification(
                        booking: $booking,
                        type: 'late_payment',
                        message: $message,
                        fonnte: $fonnte,
                        settings: $settings
                    );

                    if ($sent) {
                        $latePaymentCount++;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Cron reminder selesai dijalankan.',
            'rent_ending_sent' => $rentEndingCount,
            'late_payment_sent' => $latePaymentCount,
            'time' => now()->toDateTimeString(),
        ]);
    }

    private function sendNotification(
        Booking $booking,
        string $type,
        string $message,
        FonnteService $fonnte,
        SettingService $settings
    ): bool {
        $sentForDate = today()->toDateString();
        $sentAny = false;

        /*
        |--------------------------------------------------------------------------
        | Cegah spam WhatsApp di hari yang sama
        |--------------------------------------------------------------------------
        */
        if (
            $settings->get('notification.whatsapp_enabled', false)
            && $booking->customer_phone
            && ! $this->alreadySent($booking, 'whatsapp', $type, $sentForDate)
        ) {
            $status = $fonnte->send($booking->customer_phone, $message);

            NotificationLog::create([
                'booking_id' => $booking->id,
                'channel' => 'whatsapp',
                'type' => $type,
                'target' => $booking->customer_phone,
                'message' => $message,
                'status' => $status ? 'success' : 'failed',
                'response' => null,
                'sent_for_date' => $sentForDate,
                'sent_at' => now(),
            ]);

            if ($status) {
                $sentAny = true;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cegah spam Email di hari yang sama
        |--------------------------------------------------------------------------
        */
        if (
            $settings->get('notification.email_enabled', false)
            && $booking->customer_email
            && ! $this->alreadySent($booking, 'email', $type, $sentForDate)
        ) {
            try {
                $subject = $type === 'late_payment'
                    ? 'Pengingat Keterlambatan Pembayaran Rafa Kost'
                    : 'Pengingat Masa Sewa Rafa Kost';

                Mail::raw($message, function ($mail) use ($booking, $subject) {
                    $mail->to($booking->customer_email)
                        ->subject($subject);
                });

                NotificationLog::create([
                    'booking_id' => $booking->id,
                    'channel' => 'email',
                    'type' => $type,
                    'target' => $booking->customer_email,
                    'message' => $message,
                    'status' => 'success',
                    'response' => null,
                    'sent_for_date' => $sentForDate,
                    'sent_at' => now(),
                ]);

                $sentAny = true;
            } catch (\Throwable $e) {
                NotificationLog::create([
                    'booking_id' => $booking->id,
                    'channel' => 'email',
                    'type' => $type,
                    'target' => $booking->customer_email,
                    'message' => $message,
                    'status' => 'failed',
                    'response' => $e->getMessage(),
                    'sent_for_date' => $sentForDate,
                    'sent_at' => now(),
                ]);
            }
        }

        return $sentAny;
    }

    private function alreadySent(Booking $booking, string $channel, string $type, string $sentForDate): bool
    {
        return NotificationLog::where('booking_id', $booking->id)
            ->where('channel', $channel)
            ->where('type', $type)
            ->whereDate('sent_for_date', $sentForDate)
            ->exists();
    }

    private function makeRentEndingMessage(Booking $booking, Carbon $tanggalHabis, int $sisaHari, SettingService $settings): string
    {
        $template = $settings->get(
            'template.rent_ending',
            'Halo {nama}, masa sewa kamar {kamar} akan berakhir pada {tanggal_habis}.'
        );

        return $this->replaceTemplate($template, $booking, [
            'tanggal_habis' => $tanggalHabis->format('d/m/Y'),
            'sisa_hari' => $sisaHari,
        ]);
    }

    private function makeLatePaymentMessage(Booking $booking, int $lateDays, int $lateFee, SettingService $settings): string
    {
        $template = $settings->get(
            'template.late_payment',
            'Halo {nama}, pembayaran invoice {invoice} terlambat {telat_hari} hari. Denda saat ini {denda}. Total tagihan {total}.'
        );

        return $this->replaceTemplate($template, $booking, [
            'telat_hari' => $lateDays,
            'denda' => 'Rp ' . number_format($lateFee, 0, ',', '.'),
            'total' => 'Rp ' . number_format((int) $booking->payment_total, 0, ',', '.'),
        ]);
    }

    private function replaceTemplate(string $template, Booking $booking, array $extra = []): string
    {
        $kamarNama = optional($booking->kamar)->nama ?? 'Kamar';

        $data = array_merge([
            'nama' => $booking->customer_name ?? 'Penyewa',
            'kamar' => $kamarNama,
            'invoice' => $booking->invoice ?? '-',
            'tanggal_habis' => '-',
            'telat_hari' => '-',
            'denda' => '-',
            'total' => '-',
            'sisa_hari' => '-',
        ], $extra);

        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }
}