<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\BotController;
use App\Http\Controllers\PaymentController;
use App\Models\BotLog;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/bot-logger', function (Request $request) {
    // Validasi sederhana
    $request->validate([
        'instance_name' => 'required',
        'status' => 'required'
    ]);

    // Simpan ke database
    BotLog::create($request->all());

    return response()->json(['message' => 'Log berhasil dicatat!']);
});

Route::post('/bot/context', [BotController::class, 'getContext']);
Route::post('/bot/history', [BotController::class, 'saveHistory']);
Route::post('/webhook/get-context', [WebhookController::class, 'getContext']);
Route::post('/webhook/toggle-ai', [WebhookController::class, 'toggleAi']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});
// URL Callback untuk diinput di Dashboard Duitku: https://domainkamu.com/api/duitku/callback
Route::post('/duitku/callback', [PaymentController::class, 'callback']);
