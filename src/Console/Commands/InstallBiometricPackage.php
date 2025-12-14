<?php

declare(strict_types=1);

namespace Shard\Ui\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallBiometricPackage extends Command
{
    protected $signature = 'shard-ui:install-biometric';

    protected $description = 'Install and setup the biometric authentication package';

    public function handle(): void
    {
        $this->info('Installing Shard UI Biometric Package...');

        // Publish migrations
        $this->info('Publishing biometric migrations...');
        Artisan::call('vendor:publish', [
            '--tag' => 'shard-ui-migrations',
            '--force' => true
        ]);

        // Run migrations
        $this->info('Running biometric migrations...');
        Artisan::call('migrate', ['--force' => true]);

        // Publish config if needed
        $this->info('Publishing configuration...');
        Artisan::call('vendor:publish', [
            '--tag' => 'shard-ui-config',
            '--force' => true
        ]);

        $this->info('✅ Biometric package installed successfully!');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Add biometric routes to your web.php: require __DIR__.\'/../vendor/shard/ui/routes/docs.php\';');
        $this->info('2. Visit /docs to test biometric authentication');
    }
}
