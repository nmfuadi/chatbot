<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\EnsureWaVerified;
use App\Http\Middleware\EnsureHasActiveSubscription;
use App\Models\Plan;
use App\Http\Controllers\WhatsAppMonitoringController;
use App\Http\Controllers\ServerMonitoringController;
use App\Http\Controllers\TrafficMonitoringController;
use App\Http\Controllers\AiMonitoringController;
use App\Http\Controllers\Admin\MonitoringLogController;
use App\Http\Middleware\IsAdmin; // Pastikan middleware-nya di-import




Route::prefix('admin/monitoring')->group(function () {
    Route::get('/', [MonitoringLogController::class, 'index'])->name('admin.monitor.logs');
    Route::delete('/delete/{filename}', [MonitoringLogController::class, 'destroy'])->name('admin.monitor.delete');
});

Route::get('/', function () { return view('welcome'); });
Route::get('/admin/traffic', [TrafficMonitoringController::class, 'index'])->name('traffic.monitor');

Route::get('/admin/ai-monitoring', [AiMonitoringController::class, 'index'])->name('ai.monitor');

// =========================================================
// GERBANG 1: PENGGUNA HARUS LOGIN (AUTH)
// =========================================================
Route::middleware(['auth'])->group(function () {

    // --- AREA ONBOARDING & VERIFIKASI WA ---
    // (Rute ini diletakkan di sini agar lolos dari blokir Verifikasi WA)
    Route::get('/onboarding/profile', [OnboardingController::class, 'profileForm'])->name('onboarding.profile.form');
    Route::post('/onboarding/profile', [OnboardingController::class, 'submitProfile'])->name('onboarding.profile.submit');
    
    Route::get('/onboarding/otp', [OnboardingController::class, 'otpForm'])->name('onboarding.otp.form');
    Route::post('/onboarding/otp', [OnboardingController::class, 'verifyOtp'])->name('onboarding.otp.verify');

    // =========================================================
    // GERBANG 2: PENGGUNA WAJIB VERIFIKASI WHATSAPP
    // =========================================================
    Route::middleware([EnsureWaVerified::class])->group(function () {

        // --- AREA BEBAS LANGGANAN (Dashboard, Profil, Tagihan, SOP) ---
        Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

        // Profil Member
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Pemilihan Paket / Langganan
        Route::get('/select-plan', [SubscriptionController::class, 'index'])->name('user.plans.index');
        Route::post('/select-plan/{plan}', [SubscriptionController::class, 'subscribe'])->name('user.plans.subscribe');
        // --- TAMBAHKAN RUTE INI ---
        Route::post('/cancel-plan', [SubscriptionController::class, 'cancelPlan'])->name('user.plans.cancel');

        // Tagihan & Invoice
        Route::get('/invoices', [PaymentController::class, 'index'])->name('user.invoice.index');
        Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('user.invoice.show');

        // Proses Pembayaran Duitku
        Route::get('/invoice/{invoice}/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('payment.methods');
        Route::post('/invoice/{invoice}/request-transaction', [PaymentController::class, 'requestTransaction'])->name('payment.request');
        Route::get('/payment/return', [PaymentController::class, 'paymentReturn'])->name('payment.return');

        // Product Knowledge & SOP
        Route::get('/member/product-knowledge', [MemberController::class, 'showProductKnowledge'])->name('member.pk');
        Route::post('/member/product-knowledge', [MemberController::class, 'saveProductKnowledge'])->name('member.pk.save');

        

        // =========================================================
        // GERBANG 3: PENGGUNA WAJIB PUNYA LANGGANAN AKTIF
        // =========================================================
        Route::middleware([EnsureHasActiveSubscription::class])->group(function () {

            // --- AREA PREMIUM (Fitur Utama AI & Bot) ---
            
            // Setup Bot / Wablas
           // Route::get('/member/wablas', [MemberController::class, 'wablasPairing'])->name('member.wablas');
           Route::get('/member/whatsapp', [App\Http\Controllers\MemberController::class, 'whatsappPairing'])->name('member.whatsapp.setup');

            // Manajemen Customer / Chat History
            Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
            Route::post('/customers/{id}/toggle', [CustomerController::class, 'toggleAI'])->name('customers.toggle');
            Route::get('/customers/{phone}/history', [CustomerController::class, 'history'])->name('customers.history');
            Route::delete('/customers/{phone}/history/clear', [CustomerController::class, 'clearHistory'])->name('customers.history.clear');

            // Manajemen Katalog
            Route::resource('catalogs', CatalogController::class);

        });
    });
});

// =========================================================
// AREA KHUSUS ADMIN
// =========================================================
// Rute admin disatukan di bawah satu prefix dan middleware agar bersih
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/members', [AdminController::class, 'members'])->name('members');
    Route::post('/members/{id}/wablas', [AdminController::class, 'updateMemberWablas'])->name('members.wablas');
    Route::post('/payment/approve/{id}', [AdminController::class, 'approvePayment'])->name('payment.approve');

    Route::prefix('admin/whatsapp')->group(function () {
    Route::get('/', [WhatsAppMonitoringController::class, 'index'])->name('wa.monitor');
    Route::get('/qr/{instanceName}', [WhatsAppMonitoringController::class, 'getQr']);
    Route::put('/restart/{instanceName}', [WhatsAppMonitoringController::class, 'restart'])->name('wa.restart');
    Route::delete('/logout/{instanceName}', [WhatsAppMonitoringController::class, 'logout'])->name('wa.logout');
});

    
    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class);

});

Route::get('/', function () {
    // Ambil data paket untuk ditampilkan di landing page
    $plans = Plan::all();
    return view('welcome', compact('plans'));
});

// Rute untuk WhatsApp Monitoring


Route::prefix('admin/servers')->group(function () {
    Route::get('/', [ServerMonitoringController::class, 'index'])->name('server.monitor');
});

require __DIR__.'/auth.php'; // Rute Login/Register bawaan Breeze