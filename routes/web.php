<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () { return view('welcome'); });

// Rute untuk Member (Harus Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    
    // Member Routes
   
    
    // Profile bawaan Laravel Breeze (Bisa Update Nama, Email, Password)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- TAMBAHKAN RUTE INI (Pilih Paket & Invoice) ---
    Route::get('/select-plan', [SubscriptionController::class, 'index'])->name('user.plans.index');
    Route::post('/select-plan/{plan}', [SubscriptionController::class, 'subscribe'])->name('user.plans.subscribe');
    Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('user.invoice.show');

    // Rute untuk menampilkan metode pembayaran Duitku
    Route::get('/invoice/{invoice}/payment-methods', [PaymentController::class, 'getPaymentMethods'])->name('payment.methods');
    
    // Rute POST untuk memproses pilihan metode pembayaran (Tahap selanjutnya)
    Route::post('/invoice/{invoice}/request-transaction', [PaymentController::class, 'requestTransaction'])->name('payment.request');
    // Rute untuk Return URL Duitku
    Route::get('/payment/return', [PaymentController::class, 'paymentReturn'])->name('payment.return');

});


// Rute untuk Member 
Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('/member/wablas', [MemberController::class, 'wablasPairing'])->name('member.wablas');
    Route::get('/member/payment', [MemberController::class, 'showPayment'])->name('member.payment');
    Route::post('/member/payment', [MemberController::class, 'submitPayment'])->name('member.payment.submit');
    Route::get('/member/product-knowledge', [MemberController::class, 'showProductKnowledge'])->name('member.pk');
    Route::post('/member/product-knowledge', [MemberController::class, 'saveProductKnowledge'])->name('member.pk.save');
});

// Rute khusus ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/members', [AdminController::class, 'members'])->name('admin.members');
    Route::post('/members/{id}/wablas', [AdminController::class, 'updateMemberWablas'])->name('admin.members.wablas');
    Route::post('/payment/approve/{id}', [AdminController::class, 'approvePayment'])->name('admin.payment.approve');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/{id}/toggle', [CustomerController::class, 'toggleAI'])->name('customers.toggle');
    Route::get('/customers/{phone}/history', [CustomerController::class, 'history'])->name('customers.history');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/{id}/toggle', [CustomerController::class, 'toggleAI'])->name('customers.toggle');
    Route::get('/customers/{phone}/history', [CustomerController::class, 'history'])->name('customers.history');
// Tambahkan baris di bawah ini untuk fitur hapus
    Route::delete('/customers/{phone}/history/clear', [CustomerController::class, 'clearHistory'])->name('customers.history.clear');
});


Route::middleware(['auth'])->group(function () {
    Route::resource('catalogs', CatalogController::class);
});

Route::middleware(['auth'])->group(function () {
    // Rute Onboarding (Profil Usaha & OTP)
    Route::get('/onboarding/profile', [OnboardingController::class, 'profileForm'])->name('onboarding.profile.form');
    Route::post('/onboarding/profile', [OnboardingController::class, 'submitProfile'])->name('onboarding.profile.submit');
    
    Route::get('/onboarding/otp', [OnboardingController::class, 'otpForm'])->name('onboarding.otp.form');
    Route::post('/onboarding/otp', [OnboardingController::class, 'verifyOtp'])->name('onboarding.otp.verify');
    Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('user.invoice.show');
});

Route::middleware(['auth', 'verified.wa'])->group(function () {
    // Rute untuk melihat rangkuman profil & status
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    
    // Rute bawaan Breeze untuk edit/hapus
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tambahkan Rute Pembayaran Kosong (Sementara)
    Route::get('/payment', function () {
        return "Halaman Pembayaran Sedang Dibuat"; // Nanti kita ganti dengan view payment
    })->name('payment.index');
    
});

// Rute khusus Admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class);
});







require __DIR__.'/auth.php'; // Rute Login/Register bawaan Breeze
