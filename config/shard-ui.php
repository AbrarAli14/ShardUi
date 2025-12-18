<?php

declare(strict_types=1);

return [
    'channel_prefix' => 'shard',
    'session_ttl' => 3600,
    'qr_route_name' => 'shard.session.qr',
    'connect_route_name' => 'shard.session.connect',
    'demo' => [
        'enabled' => env('SHARD_UI_ENABLE_DEMO', false),
        'allow_anonymous' => env('SHARD_UI_ALLOW_ANONYMOUS', true),
        'user' => [
            'model' => env('SHARD_UI_DEMO_USER_MODEL'),
            'email' => env('SHARD_UI_DEMO_USER_EMAIL', 'demo@shard-ui.com'),
            'name' => env('SHARD_UI_DEMO_USER_NAME', 'Demo User'),
            'password' => env('SHARD_UI_DEMO_USER_PASSWORD', 'password'),
        ],
    ],
    'auth' => [
        'required' => env('SHARD_UI_REQUIRE_AUTH', false),
    ],
    'rate_limits' => [
        'session' => [
            'max_attempts' => 20,
            'decay_minutes' => 1,
        ],
        'connect' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
    ],
    'telemetry' => [
        'log_sessions' => true,
        'log_payloads' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Biometric Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WebAuthn biometric authentication including security
    | policies, rate limiting, and device management.
    |
    */
    'biometric' => [
        'security' => [
            'rate_limit_attempts' => env('BIOMETRIC_RATE_LIMIT_ATTEMPTS', 10),
            'rate_limit_decay' => env('BIOMETRIC_RATE_LIMIT_DECAY', 1), // minutes
            'max_attempts' => env('BIOMETRIC_MAX_ATTEMPTS', 5),
            'lockout_minutes' => env('BIOMETRIC_LOCKOUT_MINUTES', 15),
            'suspicious_ip_threshold' => env('BIOMETRIC_SUSPICIOUS_IP_THRESHOLD', 3),
            'geographic_anomaly_threshold' => env('BIOMETRIC_GEOGRAPHIC_ANOMALY_THRESHOLD', 500), // km
            'enable_security_alerts' => env('BIOMETRIC_SECURITY_ALERTS', true),
            'require_user_verification' => env('BIOMETRIC_REQUIRE_USER_VERIFICATION', true),
        ],
        'fingerprint' => [
            'timeout' => env('WEBAUTHN_TIMEOUT', 60000),
            'credential_expiration_days' => env('WEBAUTHN_CREDENTIAL_EXPIRATION', 365),
            'challenge_length' => env('WEBAUTHN_CHALLENGE_LENGTH', 32),
            'user_verification' => env('WEBAUTHN_USER_VERIFICATION', 'required'),
            'rp_id' => env('WEBAUTHN_RP_ID', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost'),
            'origin' => env('WEBAUTHN_ORIGIN', env('APP_URL', 'http://localhost')),
        ],
        'devices' => [
            'max_devices_per_user' => env('BIOMETRIC_MAX_DEVICES_PER_USER', 5),
            'auto_cleanup_inactive' => env('BIOMETRIC_AUTO_CLEANUP_INACTIVE', true),
            'inactive_threshold_days' => env('BIOMETRIC_INACTIVE_THRESHOLD_DAYS', 90),
        ],
    ],
];
