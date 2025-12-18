<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Shard\Ui\Biometric\Http\Controllers\BiometricController as VendorBiometricController;

/**
 * This controller extends the package's biometric controller so applications
 * can customize storage, analytics, and logging without editing vendor files.
 * Publish it via:
 * php artisan vendor:publish --provider="Shard\Ui\ShardServiceProvider" --tag=shard-ui-controller
 */
final class BiometricController extends VendorBiometricController
{
    //
}
