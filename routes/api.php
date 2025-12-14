<?php

use Illuminate\Support\Facades\Route;
use Shard\Ui\Http\Controllers\ShardApiController;
use App\Http\Controllers\BiometricController;

// These routes are not auto-prefixed by Laravel's RouteServiceProvider, so we prefix them here.
Route::prefix('api')->middleware(['api'])->group(function (): void {
    Route::get('/test/{sessionId}', function ($sessionId) {
        return response()->json(['sessionId' => $sessionId]);
    });

    Route::post('/sessions', [ShardApiController::class, 'createSession'])
        ->name('api.shard.sessions.create')
        ->middleware('throttle:shard-session');

    Route::get('/sessions', [ShardApiController::class, 'listSessions'])
        ->name('api.shard.sessions.list');

    Route::get('/sessions/{sessionId}', [ShardApiController::class, 'getSession'])
        ->name('api.shard.sessions.show');

    Route::post('/sessions/{sessionId}/shards', [ShardApiController::class, 'pushShard'])
        ->name('api.shard.sessions.shards.push');

    Route::get('/sessions/{sessionId}/shards/{shardName}', [ShardApiController::class, 'getShard'])
        ->name('api.shard.sessions.shards.show');

    Route::delete('/sessions/{sessionId}', [ShardApiController::class, 'endSession'])
        ->name('api.shard.sessions.delete');

    // Biometric Authentication Routes
    Route::prefix('biometric')->name('biometric.')->group(function () {
        Route::prefix('fingerprint')->name('fingerprint.')->group(function () {
            Route::post('/register', [BiometricController::class, 'registerOptions'])
                ->name('register');
            Route::post('/store', [BiometricController::class, 'storeCredential'])
                ->name('store');
            Route::post('/authenticate', [BiometricController::class, 'authenticateOptions'])
                ->name('authenticate');
            Route::post('/verify', [BiometricController::class, 'verifyAuthentication'])
                ->name('verify');
            Route::get('/credentials', [BiometricController::class, 'getCredentials'])
                ->name('credentials');
            Route::post('/device/register', [BiometricController::class, 'registerDevice'])
                ->name('device.register');

            // Analytics routes
            Route::get('/analytics', [BiometricController::class, 'getAnalytics'])
                ->name('analytics');
            Route::get('/security-alerts', [BiometricController::class, 'getSecurityAlerts'])
                ->name('security.alerts');
        });
    });
});
