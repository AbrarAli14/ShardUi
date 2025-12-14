<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Biometric Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for fingerprint and other biometric authentication methods
    | used in Shard UI for automatic device assignment.
    |
    */

    'fingerprint' => [
        // WebAuthn configuration
        'rp_name' => env('APP_NAME', 'Shard UI'),
        'rp_id' => env('WEBAUTHN_RP_ID', env('APP_URL')),
        'origin' => env('WEBAUTHN_ORIGIN', env('APP_URL')),
        'timeout' => env('WEBAUTHN_TIMEOUT', 60000),
        
        // Security settings
        'require_user_verification' => true,
        'require_user_presence' => true,
        'authenticator_attachment' => 'platform', // 'platform' for built-in sensors
        
        // Credential settings
        'max_credentials_per_user' => 5,
        'credential_expiration_days' => 365,
        
        // Device compatibility
        'supported_platforms' => ['android', 'ios', 'windows', 'macos'],
        'fallback_to_qr' => true,
    ],

    'security' => [
        // Encryption settings
        'encryption_key' => env('BIOMETRIC_ENCRYPTION_KEY'),
        
        // Matching thresholds
        'match_threshold' => 0.8,
        'max_attempts' => 3,
        'lockout_minutes' => 15,
        
        // Privacy settings
        'store_biometric_data' => false, // Only store credential IDs, not raw biometrics
        'data_retention_days' => 90,
    ],

    'logging' => [
        'enabled' => env('BIOMETRIC_LOGGING', true),
        'log_successful_auth' => true,
        'log_failed_attempts' => true,
        'log_device_assignments' => true,
    ],

    'devices' => [
        // Auto-assign shards after successful biometric auth
        'auto_assign_shards' => true,
        
        // Device types that support biometrics
        'supported_types' => ['mobile', 'tablet', 'kiosk', 'workstation'],
        
        // Require device registration before biometric auth
        'require_registration' => true,
    ],
];
