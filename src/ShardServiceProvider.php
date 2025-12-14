<?php

declare(strict_types=1);

namespace Shard\Ui;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Shard\Ui\Console\Commands\InstallBiometricPackage;
use Shard\Ui\Console\Commands\PurgeShardSessions;
use Shard\Ui\Http\Middleware\VerifyShardSignature;
use Shard\Ui\Biometric\FingerprintServiceProvider;

final class ShardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/shard-ui.php',
            'shard-ui'
        );
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/shard-ui.php',
            'shard-ui'
        );

        // Set channel prefix based on auth requirement
        $channelPrefix = config('shard-ui.auth.required', false) ? 'private-shard' : 'shard';
        config(['shard-ui.channel_prefix' => $channelPrefix]);

        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerComponents();
        $this->registerMiddleware();
        $this->configureRateLimiting();
        $this->registerCommands();
        $this->registerBiometricServices();
    }

    private function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/shard-ui.php' => config_path('shard-ui.php'),
        ], 'shard-ui-config');

        $this->publishes([
            __DIR__ . '/../routes/api.php' => base_path('routes/vendor/shard-ui-api.php'),
        ], 'shard-ui-routes');

        $this->publishes([
            __DIR__ . '/../routes/docs.php' => base_path('routes/vendor/shard-ui-docs.php'),
        ], 'shard-ui-routes');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/shard-ui'),
        ], 'shard-ui-views');

        $this->publishes([
            __DIR__ . '/../resources/js' => resource_path('js/vendor/shard-ui'),
        ], 'shard-ui-assets');

        $this->publishes([
            __DIR__ . '/../database/migrations/biometric' => database_path('migrations'),
        ], 'shard-ui-migrations');
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/docs.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/channels.php');
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'shard-ui');
    }

    private function registerComponents(): void
    {
        Blade::component('shard-ui::components.shard', 'shard');
    }

    private function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('shard.signature', VerifyShardSignature::class);
    }

    private function configureRateLimiting(): void
    {
        $limits = config('shard-ui.rate_limits');

        if (isset($limits['session'])) {
            RateLimiter::for('shard-session', function ($request) use ($limits) {
                $config = $limits['session'];

                return Limit::perMinutes($config['decay_minutes'], $config['max_attempts'])
                    ->by($request->ip());
            });
        }

        if (isset($limits['connect'])) {
            RateLimiter::for('shard-connect', function ($request) use ($limits) {
                $config = $limits['connect'];

                return Limit::perMinutes($config['decay_minutes'], $config['max_attempts'])
                    ->by($request->ip());
            });
        }
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PurgeShardSessions::class,
                InstallBiometricPackage::class,
            ]);
        }
    }

    private function registerBiometricServices(): void
    {
        // Register biometric service provider
        $this->app->register(FingerprintServiceProvider::class);
    }
}
