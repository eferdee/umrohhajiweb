<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PackageCategoryController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PackageScheduleController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PilgrimController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi (dokumen/pembayaran yang dikirim ulang oleh customer)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Kategori & Paket
    Route::resource('package-categories', PackageCategoryController::class)->except(['show']);
    Route::resource('packages', PackageController::class)->except(['show']);

    // Jadwal keberangkatan — nested di bawah paket
    Route::prefix('packages/{package}/schedules')->name('packages.schedules.')->group(function () {
        Route::get('/', [PackageScheduleController::class, 'index'])->name('index');
        Route::get('/create', [PackageScheduleController::class, 'create'])->name('create');
        Route::post('/', [PackageScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}/edit', [PackageScheduleController::class, 'edit'])->name('edit');
        Route::put('/{schedule}', [PackageScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}', [PackageScheduleController::class, 'destroy'])->name('destroy');
    });

    // Booking / Pendaftaran
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Jamaah (lintas booking) & verifikasi dokumen
    Route::get('pilgrims', [PilgrimController::class, 'index'])->name('pilgrims.index');
    Route::get('pilgrims/{pilgrim}', [PilgrimController::class, 'show'])->name('pilgrims.show');
    Route::patch('pilgrims/{pilgrim}/document-status', [PilgrimController::class, 'updateDocumentStatus'])->name('pilgrims.document-status');

    // Pembayaran
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::patch('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Konten
    Route::resource('articles', ArticleController::class)->except(['show']);
    Route::resource('gallery', GalleryController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('testimonials', TestimonialController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('faqs', FaqController::class)->except(['show']);

    // Pesan masuk
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::patch('contacts/{contact}/follow-up', [ContactController::class, 'followUp'])->name('contacts.follow-up');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // Pengaturan situs
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

});
