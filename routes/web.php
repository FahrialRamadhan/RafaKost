<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\MyRentalController;
use App\Http\Controllers\IdentityVerificationController;

use App\Http\Controllers\Admin\KamarController as AdminKamarController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\IdentityVerificationController as AdminIdentityVerificationController;
use App\Http\Controllers\Admin\NotificationSettingController;
use App\Http\Controllers\Cron\BookingReminderCronController;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\PaymentHistoryController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [KamarController::class, 'index'])->name('home');

Route::get('/kamar/{id}', [KamarController::class, 'show'])
    ->name('kamar.show');

Route::get('/maps', function () {
    return view('maps');
})->name('maps');

// TAMBAHKAN INI DI SINI
Route::get('/cek-ip', function (\Illuminate\Http\Request $request) {
    $ip = $request->header('CF-Connecting-IP')
        ?: $request->header('X-Forwarded-For')
        ?: $request->ip();

    if (str_contains($ip, ',')) {
        $ip = trim(explode(',', $ip)[0]);
    }

    return response()->json([
        'ip_untuk_whitelist' => trim($ip),
        'request_ip' => $request->ip(),
        'cf_connecting_ip' => $request->header('CF-Connecting-IP'),
        'x_forwarded_for' => $request->header('X-Forwarded-For'),
        'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
});


Route::post('/check-email', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    return response()->json([
        'exists' => User::where('email', strtolower($request->email))->exists(),
    ]);
})->name('check-email');


Route::post('/check-email', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    return response()->json([
        'exists' => User::where('email', strtolower($request->email))->exists(),
    ]);
})->name('check-email');

/*
|--------------------------------------------------------------------------
| Authenticated + Email Verified Pages
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [KamarController::class, 'dashboard'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Identity Verification User
    |--------------------------------------------------------------------------
    | Halaman ini boleh diakses user yang belum approve KTP/selfie.
    | Karena halaman ini dipakai untuk upload KTP dan selfie.
    */
    Route::get('/identity-verification', [IdentityVerificationController::class, 'create'])
        ->name('identity-verification.create');

    Route::post('/identity-verification', [IdentityVerificationController::class, 'store'])
        ->name('identity-verification.store');

    /*
    |--------------------------------------------------------------------------
    | Booking Payment User
    |--------------------------------------------------------------------------
    | User harus sudah verifikasi identitas sebelum bisa booking.
    */
    Route::middleware(['identity.verified'])->group(function () {
        Route::get('/booking/{kamar}', [BookingPaymentController::class, 'create'])
            ->name('booking.create');

        Route::get('/booking/invoice/{invoice}', [BookingPaymentController::class, 'invoice'])
            ->name('booking.invoice');

        Route::get('/booking/methods/{invoice}', [BookingPaymentController::class, 'methods'])
            ->name('booking.methods');

        Route::post('/booking/methods/{invoice}', [BookingPaymentController::class, 'chooseMethod'])
            ->name('booking.methods.choose');

        Route::get('/booking/pay/{invoice}', [BookingPaymentController::class, 'pay'])
            ->name('booking.pay');
    });

    /*
    |--------------------------------------------------------------------------
    | My Rentals / Sewa Saya
    |--------------------------------------------------------------------------
    */
	Route::get('/my-rentals', [MyRentalController::class, 'index'])
	    ->name('my-rentals.index');
	
	Route::post('/my-rentals/{booking}/renew', [MyRentalController::class, 'renew'])
	    ->name('my-rentals.renew');
	
	Route::get('/my-rentals/history-data', [MyRentalController::class, 'historyData'])
	    ->name('my-rentals.history-data');

		/*
	|--------------------------------------------------------------------------
	| Payment History / Riwayat Pembayaran
	|--------------------------------------------------------------------------
	*/
	Route::get('/riwayat-pembayaran', [PaymentHistoryController::class, 'index'])
	    ->name('payment-history.index');
	
	Route::post('/riwayat-pembayaran/{booking}/cancel', [PaymentHistoryController::class, 'cancel'])
	    ->name('payment-history.cancel');


	/*
	|--------------------------------------------------------------------------
	| Testimonials
	|--------------------------------------------------------------------------
	*/
	Route::post('/testimonials', [TestimonialController::class, 'store'])
	    ->name('testimonials.store');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    | Route admin saja yang memakai middleware admin.ip.
    | Middleware ini akan cek IP hanya kalau user role-nya admin.
    */
    Route::middleware(['admin.ip'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | Admin Kamar
        |--------------------------------------------------------------------------
        */
        Route::resource('/admin/kamars', AdminKamarController::class);

        /*
        |--------------------------------------------------------------------------
        | Admin Payment Gateway ON/OFF
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/payment-gateways', [PaymentGatewayController::class, 'index'])
            ->name('admin.payment-gateways.index');

        Route::put('/admin/payment-gateways/{paymentGateway}', [PaymentGatewayController::class, 'update'])
            ->name('admin.payment-gateways.update');

        Route::post('/admin/payment-gateways/{paymentGateway}/activate', [PaymentGatewayController::class, 'activate'])
            ->name('admin.payment-gateways.activate');

        Route::post('/admin/payment-gateways/{paymentGateway}/deactivate', [PaymentGatewayController::class, 'deactivate'])
            ->name('admin.payment-gateways.deactivate');

        /*
        |--------------------------------------------------------------------------
        | Admin Payment Methods
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/payment-methods', [PaymentMethodController::class, 'index'])
            ->name('admin.payment-methods.index');

        Route::post('/admin/payment-methods', [PaymentMethodController::class, 'store'])
            ->name('admin.payment-methods.store');

        Route::put('/admin/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])
            ->name('admin.payment-methods.update');

        Route::post('/admin/payment-methods/{paymentMethod}/toggle', [PaymentMethodController::class, 'toggle'])
            ->name('admin.payment-methods.toggle');

        Route::delete('/admin/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])
            ->name('admin.payment-methods.destroy');


				/*
		|--------------------------------------------------------------------------
		| Admin Notification Settings
		|--------------------------------------------------------------------------
		*/
		Route::get('/admin/notification-settings', [NotificationSettingController::class, 'index'])
		    ->name('admin.notification-settings.index');
		
		Route::put('/admin/notification-settings', [NotificationSettingController::class, 'update'])
		    ->name('admin.notification-settings.update');
		
		Route::post('/admin/notification-settings/test-fonnte', [NotificationSettingController::class, 'testFonnte'])
		    ->name('admin.notification-settings.test-fonnte');

        /*
        |--------------------------------------------------------------------------
        | Admin Bookings / Status Order
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/bookings', [AdminBookingController::class, 'index'])
            ->name('admin.bookings.index');

        Route::get('/admin/bookings/{booking}', [AdminBookingController::class, 'show'])
            ->name('admin.bookings.show');

        Route::patch('/admin/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
            ->name('admin.bookings.update-status');

        Route::post('/admin/bookings/{booking}/mark-paid', [AdminBookingController::class, 'markPaid'])
            ->name('admin.bookings.mark-paid');

        Route::delete('/admin/bookings/{booking}', [AdminBookingController::class, 'destroy'])
            ->name('admin.bookings.destroy');

        /*
        |--------------------------------------------------------------------------
        | Admin Tenants / Data Penyewa
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/tenants', [TenantController::class, 'index'])
            ->name('admin.tenants.index');
		Route::get('/admin/tenants/{booking}/edit', [TenantController::class, 'edit'])
		    ->name('admin.tenants.edit');
		
		Route::put('/admin/tenants/{booking}', [TenantController::class, 'update'])
		    ->name('admin.tenants.update');

        /*
        |--------------------------------------------------------------------------
        | Admin Identity Verification / KTP Selfie
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/identity-verifications', [AdminIdentityVerificationController::class, 'index'])
            ->name('admin.identity-verifications.index');

        Route::get('/admin/identity-verifications/{user}', [AdminIdentityVerificationController::class, 'show'])
            ->name('admin.identity-verifications.show');

        Route::post('/admin/identity-verifications/{user}/approve', [AdminIdentityVerificationController::class, 'approve'])
            ->name('admin.identity-verifications.approve');

        Route::post('/admin/identity-verifications/{user}/reject', [AdminIdentityVerificationController::class, 'reject'])
            ->name('admin.identity-verifications.reject');

        Route::post('/admin/identity-verifications/{user}/reset', [AdminIdentityVerificationController::class, 'reset'])
            ->name('admin.identity-verifications.reset');

        Route::get('/admin/identity-verifications/{user}/file/{type}', [AdminIdentityVerificationController::class, 'file'])
            ->name('admin.identity-verifications.file');
    });
});

/*
|--------------------------------------------------------------------------
| Payment Callback
|--------------------------------------------------------------------------
| Jangan taruh di dalam auth, karena gateway perlu akses tanpa login.
*/
Route::post('/payment/callback/cashify', [BookingPaymentController::class, 'cashifyCallback'])
    ->name('payment.callback.cashify');

Route::post('/payment/callback/tokopay', [BookingPaymentController::class, 'tokopayCallback'])
    ->name('payment.callback.tokopay');

Route::get('/cron/check-booking-reminders', [BookingReminderCronController::class, 'handle'])
    ->name('cron.check-booking-reminders');
require __DIR__ . '/auth.php';



