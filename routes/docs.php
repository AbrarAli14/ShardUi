<?php

use Illuminate\Support\Facades\Route;
use Shard\Ui\Http\Controllers\DocsController;

Route::middleware(['web'])->group(function (): void {
    Route::get('/docs', [DocsController::class, 'index'])
        ->name('shard.docs.index');

    Route::get('/docs/{section}', [DocsController::class, 'section'])
        ->name('shard.docs.section');
});
