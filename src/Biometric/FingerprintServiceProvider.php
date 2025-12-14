<?php

declare(strict_types=1);

namespace Shard\Ui\Biometric;

use Illuminate\Support\ServiceProvider;
use Shard\Ui\Biometric\Services\FingerprintAuth;
use Shard\Ui\Biometric\Services\WebAuthnManager;
use Shard\Ui\Biometric\Services\BiometricSecurityService;

class FingerprintServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind fingerprint authentication service
        $this->app->singleton(FingerprintAuth::class, function ($app) {
            return new FingerprintAuth($app['config']);
        });

        // Bind WebAuthn manager
        $this->app->singleton(WebAuthnManager::class, function ($app) {
            return new WebAuthnManager($app['config']);
        });

        // Bind device fingerprint auth
        $this->app->singleton(DeviceFingerprintAuth::class, function ($app) {
            return new DeviceFingerprintAuth(
                $app[FingerprintAuth::class],
                $app[WebAuthnManager::class]
            );
        });

        // Bind biometric security service
        $this->app->singleton(BiometricSecurityService::class, function ($app) {
            return new BiometricSecurityService($app['config']['biometric'] ?? []);
        });
    }

    public function boot(): void
    {
        // Load migrations from biometric subdirectory
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/biometric');

        // TODO: Fix routes loading issue
        // $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
