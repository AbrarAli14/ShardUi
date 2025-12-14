<?php

use Illuminate\Support\Facades\Route;
use Shard\UI\Biometric\Http\Controllers\FingerprintController;
use Illuminate\Http\Middleware\Authenticate;

/*
|--------------------------------------------------------------------------
| Biometric Authentication Routes
|--------------------------------------------------------------------------
|
| Routes for fingerprint biometric authentication and device assignment
| in Shard UI distributed viewport system.
|
*/

Route::prefix('api/biometric')->middleware(['api'])->group(function () {
    
    // Fingerprint Registration Routes (require authentication)
    Route::middleware([Authenticate::class])->group(function () {
        Route::post('/fingerprint/register', [FingerprintController::class, 'register']);
        Route::post('/fingerprint/store', [FingerprintController::class, 'store']);
        Route::post('/device/register', [FingerprintController::class, 'registerDevice']);
        Route::get('/fingerprint/credentials', [FingerprintController::class, 'credentials']);
        Route::delete('/fingerprint/delete', [FingerprintController::class, 'delete']);
    });

    // Fingerprint Authentication Routes (no auth required - this is the auth method)
    Route::post('/fingerprint/authenticate', [FingerprintController::class, 'authenticate']);
    Route::post('/fingerprint/verify', [FingerprintController::class, 'verify']);

    // Device Management Routes
    Route::get('/device/{deviceId}/options', function ($deviceId) {
        $controller = app(FingerprintController::class);
        $request = request()->merge(['device_id' => $deviceId]);
        return $controller->authenticate($request);
    });

    // Status and Health Check
    Route::get('/status', function () {
        return response()->json([
            'biometric_auth_enabled' => config('biometric.fingerprint.enabled', true),
            'supported_methods' => ['fingerprint'],
            'webauthn_supported' => true,
            'version' => '1.0.0'
        ]);
    });

    // Device Compatibility Check
    Route::post('/device/compatibility', function (Illuminate\Http\Request $request) {
        $userAgent = $request->header('User-Agent', '');
        
        $compatibility = [
            'webauthn_supported' => true,
            'fingerprint_supported' => $this->hasFingerprintSupport($userAgent),
            'platform' => $this->detectPlatform($userAgent),
            'recommended_methods' => []
        ];

        if ($compatibility['fingerprint_supported']) {
            $compatibility['recommended_methods'][] = 'fingerprint';
        }

        // Fallback to QR code if no biometric support
        if (empty($compatibility['recommended_methods'])) {
            $compatibility['recommended_methods'][] = 'qr_code';
        }

        return response()->json([
            'success' => true,
            'compatibility' => $compatibility
        ]);
    });
});

/**
 * Check if user agent indicates fingerprint support
 */
function hasFingerprintSupport(string $userAgent): bool
{
    // Check for platforms that typically support fingerprint sensors
    $fingerprintPlatforms = [
        'Android', // Most Android devices with fingerprint
        'iPhone',   // iPhones with Touch ID/Face ID
        'iPad',     // iPads with Touch ID/Face ID
        'Windows',  // Windows Hello
        'Macintosh', // macOS with Touch ID
    ];

    foreach ($fingerprintPlatforms as $platform) {
        if (stripos($userAgent, $platform) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * Detect platform from user agent
 */
function detectPlatform(string $userAgent): string
{
    if (stripos($userAgent, 'Android') !== false) {
        return 'android';
    } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
        return 'ios';
    } elseif (stripos($userAgent, 'Windows') !== false) {
        return 'windows';
    } elseif (stripos($userAgent, 'Macintosh') !== false) {
        return 'macos';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        return 'linux';
    }

    return 'unknown';
}
