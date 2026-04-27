<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/', function () { return view('welcome'); });

// Rute untuk Member (Harus Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    
    // Member Routes
   
    
    // Profile bawaan Laravel Breeze (Bisa Update Nama, Email, Password)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
});





require __DIR__.'/auth.php'; // Rute Login/Register bawaan Breeze
