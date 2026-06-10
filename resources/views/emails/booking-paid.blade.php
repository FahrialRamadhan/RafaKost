<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Rafa Kost – {{ $booking->invoice }}</title>
    <!--[if mso]>
    <noscript>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    </noscript>
    <![endif]-->
    <style>
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                padding: 10px !important;
            }
            .content-pad {
                padding: 30px 20px !important;
            }
            .header-text {
                font-size: 18px !important;
            }
            .logo-img {
                height: 32px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F4F5F7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #111827; -webkit-font-smoothing: antialiased;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #F4F5F7; padding: 40px 10px;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" border="0" class="email-container" style="max-width: 600px; width: 100%;">
                
                <!-- MAIN CARD -->
                <tr>
                    <td style="background-color: #ffffff; border: 1px solid #E5E7EB; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                        
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="content-pad" style="padding: 40px;">
                                    
                                    <!-- Header: Logo & Title Sejajar -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 24px;">
                                        <tr>
                                            <td valign="middle" align="left">
                                                <a href="https://rafakost.biz.id" target="_blank" style="text-decoration: none; display: inline-block;">
                                                    <img src="https://rafakost.biz.id/images/logo.png" alt="Rafa Kost" height="40" class="logo-img" style="display: block; height: 40px; width: auto; border: 0; font-family: sans-serif; font-size: 18px; font-weight: 700; color: #111827;">
                                                </a>
                                            </td>
                                            <td valign="middle" align="right">
                                                <h1 class="header-text" style="margin: 0; font-size: 22px; font-weight: 600; color: #111827; letter-spacing: -0.5px;">
                                                    Pembayaran Berhasil
                                                </h1>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <p style="margin: 0 0 32px; font-size: 15px; color: #4B5563; line-height: 1.6;">
                                        Halo <strong>{{ $booking->customer_name ?? $booking->user->name ?? 'Pelanggan' }}</strong>,<br>
                                        Terima kasih. Pembayaran Anda telah kami terima dan pesanan kamar Anda sudah terkonfirmasi. Berikut adalah rinciannya.
                                    </p>

                                    <!-- Divider -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="border-top: 1px solid #E5E7EB; padding-bottom: 24px;"></td>
                                        </tr>
                                    </table>

                                    <!-- Details Label -->
                                    <p style="margin: 0 0 16px; font-size: 12px; font-weight: 700; color: #9CA3AF; letter-spacing: 1.5px; text-transform: uppercase;">
                                        Rincian Booking
                                    </p>

                                    <!-- Detail Rows -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; color: #6B7280;">
                                                No. Invoice
                                            </td>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; font-weight: 600; color: #111827; text-align: right;">
                                                {{ $booking->invoice }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; color: #6B7280;">
                                                Kamar
                                            </td>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; font-weight: 600; color: #111827; text-align: right;">
                                                {{ $booking->kamar->nama ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; color: #6B7280;">
                                                Tanggal Masuk
                                            </td>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; font-weight: 600; color: #111827; text-align: right;">
                                                {{ $booking->tanggal_masuk ? $booking->tanggal_masuk->format('d M Y') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; color: #6B7280;">
                                                Durasi Sewa
                                            </td>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; font-weight: 600; color: #111827; text-align: right;">
                                                {{ $booking->durasi }} Bulan
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; color: #6B7280;">
                                                Metode Pembayaran
                                            </td>
                                            <td style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; font-size: 14px; font-weight: 600; color: #111827; text-align: right;">
                                                {{ $booking->payment_method_name ?? strtoupper($booking->payment_gateway ?? '-') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 12px 0; font-size: 14px; color: #6B7280;">
                                                Status
                                            </td>
                                            <td style="padding: 12px 0; font-size: 14px; font-weight: 700; color: #059669; text-align: right; letter-spacing: 0.5px;">
                                                PAID
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Total Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 24px; background-color: #F9FAFB; border-radius: 6px;">
                                        <tr>
                                            <td style="padding: 20px;">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <span style="font-size: 13px; font-weight: 600; color: #4B5563;">Total Pembayaran</span>
                                                        </td>
                                                        <td style="text-align: right;">
                                                            <span style="font-size: 20px; font-weight: 700; color: #111827;">
                                                                Rp {{ number_format($booking->payment_total ?: $booking->total_harga, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Button Cetak Invoice -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 32px;">
                                        <tr>
                                            <td align="center">
                                                <a href="{{ route('booking.invoice', $booking->invoice) }}" target="_blank" style="display: inline-block; padding: 14px 28px; background-color: #0ea5e9; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; border-radius: 8px;">
                                                    Lihat & Cetak Invoice
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Important Info Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 32px;">
                                        <tr>
                                            <td style="padding: 16px; border-left: 3px solid #D1D5DB; background-color: #F9FAFB;">
                                                <p style="margin: 0; font-size: 13px; color: #4B5563; line-height: 1.6;">
                                                    <strong style="color: #111827;">Informasi Penting</strong><br>
                                                    Harap tunjukkan email ini atau nomor invoice kepada pengelola saat tiba di Rafa Kost. Simpan email ini sebagai bukti pembayaran yang sah.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Contact Info -->
                                    <p style="margin: 32px 0 0; font-size: 14px; color: #6B7280; line-height: 1.7;">
                                       Jika Anda memiliki pertanyaan, silakan hubungi kami melalui WhatsApp di 
										<a href="https://wa.me/6282241708491" 
										   target="_blank"
										   style="color: #111827; font-weight: 700; text-decoration: none;">
										    +62 822-4170-8491
										</a> 
										atau email ke 
										<a href="mailto:hello@rafakost.biz.id"
										   style="color: #111827; font-weight: 700; text-decoration: none;">
										    hello@rafakost.biz.id
										</a>.
                                    </p>

                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <!-- OUTER FOOTER -->
                <tr>
                    <td align="center" style="padding: 30px 20px;">
                        <p style="margin: 0; font-size: 12px; color: #9CA3AF; line-height: 1.6;">
                            &copy; {{ date('Y') }} Rafa Kost &middot; Purwokerto<br>
                            Email ini dikirim otomatis oleh sistem. Mohon tidak membalas pesan ini.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>