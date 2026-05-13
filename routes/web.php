<?php

use Illuminate\Support\Facades\Route;

// --- Imports Controller ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WhatsAppMonitoringController;
use App\Http\Controllers\ServerMonitoringController;
use App\Http\Controllers\TrafficMonitoringController;
use App\Http\Controllers\AiMonitoringController;
use App\Http\Controllers\Admin\MonitoringLogController;
use App\Http\Controllers\Admin\PlanController; // Untuk resource plans

// --- Imports Middleware & Models ---
use App\Http\Middleware\EnsureWaVerified;
use App\Http\Middleware\EnsureHasActiveSubscription;
use App\Http\Middleware\IsAdmin;
use App\Models\Plan;

/*
|--------------------------------------------------------------------------
| 1. AREA PUBLIC (Bisa diakses siapa saja tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Ambil data paket untuk ditampilkan di landing page
    $plans = Plan::all();
    return view('welcome', compact('plans'));
});

require __DIR__.'/auth.php'; // Rute Login/Register bawaan Breeze


/*
|--------------------------------------------------------------------------
| 2. AREA KHUSUS ADMIN (Dilindungi Middleware Auth & IsAdmin)
|--------------------------------------------------------------------------
| Semua URL di dalam sini otomatis diawali dengan '/admin'
*/
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {
    
    // -- TAMBAHAN: Rute Khusus Dashboard Admin --
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // -- Dashboard / Fitur Utama Admin --
    // Membungkus rute admin lama dengan akhiran nama 'admin.' agar navigasi tidak error
    Route::name('admin.')->group(function () {
        Route::get('/members', [AdminController::class, 'members'])->name('members');
        Route::post('/members/{id}/wablas', [AdminController::class, 'updateMemberWablas'])->name('members.wablas');
        Route::post('/payment/approve/{id}', [AdminController::class, 'approvePayment'])->name('payment.approve');
        Route::resource('plans', PlanController::class);
    });

    // -- Monitoring Log File --
    Route::prefix('monitoring')->group(function () {
        Route::get('/', [MonitoringLogController::class, 'index'])->name('admin.monitor.logs');
        Route::delete('/delete/{filename}', [MonitoringLogController::class, 'destroy'])->name('admin.monitor.delete');
    });

    // -- Traffic & AI Monitoring --
    Route::get('/traffic', [TrafficMonitoringController::class, 'index'])->name('traffic.monitor');
    Route::get('/ai-monitoring', [AiMonitoringController::class, 'index'])->name('ai.monitor');

    // -- WhatsApp Monitoring --
    Route::prefix('whatsapp')->group(function () {
        Route::get('/', [WhatsAppMonitoringController::class, 'index'])->name('wa.monitor');
        Route::get('/qr/{instanceName}', [WhatsAppMonitoringController::class, 'getQr']);
        Route::put('/restart/{instanceName}', [WhatsAppMonitoringController::class, 'restart'])->name('wa.restart');
        Route::delete('/logout/{instanceName}', [WhatsAppMonitoringController::class, 'logout'])->name('wa.logout');
    });

    // -- Server Monitoring --
    Route::prefix('servers')->group(function () {
        Route::get('/', [ServerMonitoringController::class, 'index'])->name('server.monitor');
    });
});


/*
|--------------------------------------------------------------------------
| 3. AREA KHUSUS MEMBER (Dilindungi Middleware Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- AREA ONBOARDING & VERIFIKASI WA ---
    Route::get('/onboarding/profile', [OnboardingController::class, 'profileForm'])->name('onboarding.profile.form');
    Route::post('/onboarding/profile', [OnboardingController::class, 'submitProfile'])->name('onboarding.profile.submit');
    Route::get('/onboarding/otp', [OnboardingController::class, 'otpForm'])->name('onboarding.otp.form');
    Route::post('/onboarding/otp', [OnboardingController::class, 'verifyOtp'])->name('onboarding.otp.verify');

    // --- GERBANG 2: WAJIB VERIFIKASI WHATSAPP ---
    Route::middleware([EnsureWaVerified::class])->group(function () {

        // -- TAMBAHAN: Logika Pintu Masuk Dashboard (Admin vs Member) --
        Route::get('/dashboard', function () { 
            // Jika Admin yang mencoba masuk ke sini, lempar ke dashboard admin
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            // Jika Member biasa, tampilkan dashboard member
            return view('dashboard'); 
        })->name('dashboard');

        // Profil
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Pemilihan Paket / Langganan
        Route::get('/select-plan', [SubscriptionController::class, 'index'])->name('user.plans.index');
        Route::post('/select-plan/{plan}', [SubscriptionController::class, 'subscribe'])->name('user.plans.subscribe');
        Route::post('/cancel-plan', [SubscriptionController::class, 'cancelPlan'])->name('user.plans.cancel');

        // Tagihan, Invoice & Duitku
        Route::get('/invoices', [PaymentController::class, 'index'])->name('user.invoice.index');
        Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('user.invoice.show');
        Route::get('/invoice/{invoice}/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('payment.methods');
        Route::post('/invoice/{invoice}/request-transaction', [PaymentController::class, 'requestTransaction'])->name('payment.request');
        Route::get('/payment/return', [PaymentController::class, 'paymentReturn'])->name('payment.return');

        // Product Knowledge
        Route::get('/member/product-knowledge', [MemberController::class, 'showProductKnowledge'])->name('member.pk');
        Route::post('/member/product-knowledge', [MemberController::class, 'saveProductKnowledge'])->name('member.pk.save');


        // --- GERBANG 3: WAJIB PUNYA LANGGANAN AKTIF ---
        Route::middleware([EnsureHasActiveSubscription::class])->group(function () {

            // Fitur Utama AI & Bot
            Route::get('/member/whatsapp', [MemberController::class, 'whatsappPairing'])->name('member.whatsapp.setup');
            
            // Manajemen Customer
            Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
            Route::post('/customers/{id}/toggle', [CustomerController::class, 'toggleAI'])->name('customers.toggle');
            Route::get('/customers/{phone}/history', [CustomerController::class, 'history'])->name('customers.history');
            Route::delete('/customers/{phone}/history/clear', [CustomerController::class, 'clearHistory'])->name('customers.history.clear');

            // Manajemen Katalog
            Route::resource('catalogs', CatalogController::class);

        });
    });
});