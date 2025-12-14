<?php

declare(strict_types=1);

use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Shard\Ui\Http\Controllers\DemoController;
use Shard\Ui\Http\Controllers\HandshakeController;

Route::middleware(['web', 'shard.signature'])->group(function (): void {
    Route::get('/shard/demo', [DemoController::class, '__invoke'])
        ->name('shard.demo')
        ->middleware(config('shard-ui.auth.required', false) ? 'auth' : []);

    Route::post('/shard/session', [HandshakeController::class, 'create'])
        ->middleware(['throttle:shard-session'])
        ->name(config('shard-ui.qr_route_name', 'shard.session.qr'))
        ->middleware(config('shard-ui.auth.required', false) ? 'auth' : []);

    Route::get('/shard/connect/{session}', [HandshakeController::class, 'connect'])
        ->middleware(['signed', 'throttle:shard-connect'])
        ->name(config('shard-ui.connect_route_name', 'shard.session.connect'))
        ->middleware(config('shard-ui.auth.required', false) ? 'auth' : []);
});

Route::middleware(['web'])->group(function (): void {
    Route::post('/shard/broadcasting/auth', [BroadcastController::class, 'authenticate'])
        ->middleware('web')
        ->withoutMiddleware([VerifyCsrfToken::class])
        ->name('shard.broadcasting.auth');
});
