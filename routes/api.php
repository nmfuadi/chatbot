<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\BotController;
use App\Http\Controllers\PaymentController;
use App\Models\BotLog;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;



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
