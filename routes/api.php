<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\BotController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Http\Controllers\Api\LeadAnalyticController;

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


// Jalur Endpoint untuk paket Intelligent Sales
Route::post('/v1/sales-intelligence/analytics', [LeadAnalyticController::class, 'store']);

// Endpoint untuk menerima log dari n8n dan menyimpannya ke file .log
Route::post('/bot-logger', function (Request $request) {
    $date = Carbon::today()->format('Y-m-d');
    $path = storage_path("app/bot-logs/bot-{$date}.log");

    // Pastikan folder bot-logs ada
    if (!File::exists(storage_path('app/bot-logs'))) {
        File::makeDirectory(storage_path('app/bot-logs'), 0777, true, true);
    }

    // Ubah data dari n8n jadi format JSON 1 baris, lalu tambahkan ke file .log
    $data = json_encode($request->all());
    File::append($path, $data . PHP_EOL);

    return response()->json(['message' => 'Log berhasil ditulis!']);
});


// Route Bot & Webhook lainnya
Route::post('/bot/context', [BotController::class, 'getContext']);
Route::post('/bot/history', [BotController::class, 'saveHistory']);
Route::post('/webhook/get-context', [WebhookController::class, 'getContext']);
Route::post('/webhook/toggle-ai', [WebhookController::class, 'toggleAi']);

// Pastikan path-nya sesuai dengan yang ditembak n8n
Route::prefix('v1')->group(function () {
    Route::post('/sales-intelligence/pause-ai', [App\Http\Controllers\Api\LeadAnalyticController::class, 'pauseAi']);
    Route::post('/sales-intelligence/resume-ai', [App\Http\Controllers\Api\LeadAnalyticController::class, 'resumeAi']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// URL Callback untuk diinput di Dashboard Duitku
Route::post('/duitku/callback', [PaymentController::class, 'callback']);

// ==========================================================
// --- ENDPOINT UNTUK WIDGET WEBSITE ---
// ==========================================================
Route::get('/widget/{user_id}/settings', [\App\Http\Controllers\Api\WidgetApiController::class, 'settings']);
Route::post('/widget/start', [\App\Http\Controllers\Api\WidgetApiController::class, 'startSession']);
Route::get('/widget/{session_id}/messages', [\App\Http\Controllers\Api\WidgetApiController::class, 'getMessages']);
Route::post('/widget/send', [\App\Http\Controllers\Api\WidgetApiController::class, 'sendMessage']);