<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shard\UI\Biometric\Http\Controllers\BiometricController;

Route::prefix('api/biometric')->group(function () {
    // WebAuthn fingerprint routes
    Route::post('/fingerprint/register', [BiometricController::class, 'registerOptions']);
    Route::post('/fingerprint/store', [BiometricController::class, 'storeCredential']);
    Route::post('/fingerprint/authenticate', [BiometricController::class, 'authenticateOptions']);
    Route::post('/fingerprint/verify', [BiometricController::class, 'verifyAuthentication']);
    Route::get('/fingerprint/credentials', [BiometricController::class, 'getCredentials']);
    
    // Device management routes
    Route::post('/device/register', [BiometricController::class, 'registerDevice']);
});
