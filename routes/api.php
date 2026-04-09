<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AntiCheatController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/anti-cheat/report', [AntiCheatController::class, 'report']);
    
    Route::post('/examens/sessions/{session}/autosave', [ExamenApiController::class, 'autosave'])
        ->name('api.examens.autosave');
});