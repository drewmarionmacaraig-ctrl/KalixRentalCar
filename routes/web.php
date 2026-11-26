<?php

use App\Http\Controllers\Admin\AddonController as AdminAddonController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\PaymentSettingController as AdminPaymentSettingController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

// Public Customer Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vehicles', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/vehicles/{vehicle:slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry.create');
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');

// 4-Step Booking Flow & Contract PDF
Route::get('/book/{vehicle:slug}', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/book/{vehicle:slug}', [BookingController::class, 'storeCheckout'])->name('booking.checkout.store');
Route::get('/booking/{booking:booking_code}/contract', [BookingController::class, 'contract'])->name('booking.contract');
Route::post('/booking/{booking:booking_code}/contract', [BookingController::class, 'acceptContract'])->name('booking.contract.accept');
Route::get('/booking/{booking:booking_code}/payment', [BookingController::class, 'payment'])->name('booking.payment');
Route::post('/booking/{booking:booking_code}/payment', [BookingController::class, 'submitPayment'])->name('booking.payment.submit');
Route::get('/booking/{booking:booking_code}/confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/{booking:booking_code}/contract.pdf', [BookingController::class, 'downloadContract'])->name('booking.contract.pdf');

// Admin Authentication
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('vehicles', AdminVehicleController::class)->except(['show']);

        Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::post('bookings/{booking}/payments/{payment}/approve', [AdminBookingController::class, 'approvePayment'])->name('bookings.payments.approve');
        Route::post('bookings/{booking}/payments/{payment}/reject', [AdminBookingController::class, 'rejectPayment'])->name('bookings.payments.reject');
        Route::post('bookings/{booking}/rented', [AdminBookingController::class, 'markRented'])->name('bookings.rented');
        Route::post('bookings/{booking}/completed', [AdminBookingController::class, 'markCompleted'])->name('bookings.completed');
        Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('bookings/{booking}/extend', [AdminBookingController::class, 'extendForm'])->name('bookings.extend');
        Route::post('bookings/{booking}/extend', [AdminBookingController::class, 'extendStore'])->name('bookings.extend.store');

        Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::put('inquiries/{inquiry}', [AdminInquiryController::class, 'update'])->name('inquiries.update');

        Route::resource('addons', AdminAddonController::class)->except(['show', 'destroy']);
        Route::post('addons/{addon}/toggle', [AdminAddonController::class, 'toggle'])->name('addons.toggle');

        Route::get('payment-settings', [AdminPaymentSettingController::class, 'edit'])->name('payment-settings.edit');
        Route::put('payment-settings', [AdminPaymentSettingController::class, 'update'])->name('payment-settings.update');
        Route::post('payment-settings/methods', [AdminPaymentSettingController::class, 'storeMethod'])->name('payment-settings.methods.store');
        Route::delete('payment-settings/methods/{method}', [AdminPaymentSettingController::class, 'destroyMethod'])->name('payment-settings.methods.destroy');

        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [AdminReportController::class, 'exportCsv'])->name('reports.export');
    });
});
