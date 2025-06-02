<?php

use App\Http\Controllers\Api\LogPerangkatController;
use App\Http\Controllers\Api\PerangkatController;
use App\Http\Middleware\EspTokenMiddleware;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::get('/perangkat', [LogPerangkatController::class, 'getData']);
Route::middleware(EspTokenMiddleware::class)->group(function () {
    Route::patch('/perangkat/{id}    ', [PerangkatController::class, 'regisDevices']);
    Route::post('/devices/log', [LogPerangkatController::class, 'storeLogPerangkat']);
    Route::get('/perangkat/log', [LogPerangkatController::class, 'index']);
    Route::get('/devices/{deviceId}/logs', [LogPerangkatController::class, 'getLogsByDeviceId']);
    Route::get('/devices/{deviceId}/logs/latest', [LogPerangkatController::class, 'getLatestLogByDeviceId']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
