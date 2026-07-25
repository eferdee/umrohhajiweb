<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\PackageCategory;

Route::get('/', function () {
    $packages = Package::with(['category', 'schedules' => function ($q) {
            $q->where('status', true)->orderBy('price');
        }])
        ->where('status', true)
        ->latest()
        ->take(9)
        ->get();

    // ============ TAMBAHAN: kategori untuk filter pill di section Paket ============
    $packageCategories = PackageCategory::where('status', true)
        ->whereIn('id', $packages->pluck('category_id')->filter()->unique())
        ->get();
    // ================================================================================

    $testimonials = Testimonial::where('is_published', true)
        ->orderByDesc('is_featured')
        ->latest()
        ->take(6)
        ->get();

    $faqs = Faq::where('is_published', true)
        ->orderBy('sort_order')
        ->take(8)
        ->get();

    $settings = Setting::pluck('value', 'key');

    $articles = Article::where('is_published', true)
        ->latest('published_at')
        ->take(3)
        ->get();

    // ============ TAMBAHAN: galeri untuk section marquee di homepage ============
    $galleryItems = Gallery::where('is_published', true)
        ->orderBy('sort_order')
        ->latest()
        ->take(12)
        ->get();
    // ================================================================================

    return view('welcome', compact('packages', 'packageCategories', 'testimonials', 'faqs', 'settings', 'articles', 'galleryItems'));
});

Route::get('/paket', [PackageController::class, 'index'])->name('packages.index');
Route::get('/paket/{package:slug}', [PackageController::class, 'show'])->name('packages.show');

// ============ HALAMAN PUBLIK BARU ============
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/faq', [FaqController::class, 'index'])->name('faqs.index');

Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');
Route::get('/kontak/status', [ContactController::class, 'statusForm'])->name('contact.status');
Route::post('/kontak/status', [ContactController::class, 'statusCheck'])->name('contact.status.check');
// ================================================

Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    Route::get('/booking/{schedule}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{schedule}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/sukses/{booking}', [BookingController::class, 'success'])->name('booking.success');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user?->role?->name) {
        'admin' => redirect()->route('admin.dashboard'),
        'customer' => redirect()->route('customer.dashboard'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/bookings/{booking}', [CustomerDashboardController::class, 'show'])
            ->name('bookings.show');

        Route::get('/bookings/{booking}/bayar', [\App\Http\Controllers\Customer\PaymentController::class, 'create'])
            ->name('payments.create');
        Route::post('/bookings/{booking}/bayar', [\App\Http\Controllers\Customer\PaymentController::class, 'store'])
            ->name('payments.store');

        Route::get('/pilgrims/{pilgrim}/dokumen', [\App\Http\Controllers\Customer\PilgrimController::class, 'edit'])
            ->name('pilgrims.documents.edit');
        Route::post('/pilgrims/{pilgrim}/dokumen', [\App\Http\Controllers\Customer\PilgrimController::class, 'update'])
            ->name('pilgrims.documents.update');

        // Notifikasi (status verifikasi dokumen & pembayaran dari admin)
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
            ->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
            ->name('notifications.read-all');

    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';