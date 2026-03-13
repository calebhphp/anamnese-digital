<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnamneseController;

Route::get('/', [AnamneseController::class, 'index'])->name('anamnese.index');
Route::post('/anamnese/store', [AnamneseController::class, 'store'])->name('anamnese.store');
Route::get('/anamnese/resume/{sessionId}', [AnamneseController::class, 'resume'])->name('anamnese.resume');
Route::post('/anamnese/test-webhook', [AnamneseController::class, 'testWebhook'])->name('anamnese.test-webhook');
Route::get('/anamnese/export/{id}', [AnamneseController::class, 'export'])->name('anamnese.export');