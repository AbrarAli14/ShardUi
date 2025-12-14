<?php

declare(strict_types=1);

namespace Shard\Ui\Biometric\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Shard\Ui\Biometric\Models\BiometricDevice;
use Shard\Ui\Biometric\Models\BiometricLog;
use Shard\Ui\Biometric\Models\FingerprintCredential;

class BiometricSecurityService
{
    private array $processedConfig;

    public function __construct(
        array $config = []
    ) {
        $this->processedConfig = $config['biometric']['security'] ?? [
            'max_attempts' => 5,
            'lockout_minutes' => 15,
            'rate_limit_attempts' => 10,
            'rate_limit_decay' => 1, 
            'suspicious_ip_threshold' => 3,
            'geographic_anomaly_threshold' => 500,
        ];
    }

    /**
     * Check if device is locked out
     */
    public function isDeviceLockedOut(BiometricDevice $device): bool
    {
        return $device->isLockedOut();
    }

    /**
     * Check rate limiting for IP/user
     */
    public function isRateLimited(Request $request, int|string|null $userId = null): bool
    {
        $key = $this->getRateLimitKey($request, $userId);

        $attempts = Cache::get($key, 0);

        return $attempts >= $this->processedConfig['rate_limit_attempts'];
    }

    /**
     * Record authentication attempt
     */
    public function recordAttempt(
        int|string $userId,
        string $deviceId,
        bool $success,
        Request $request,
        array $additionalData = []
    ): BiometricLog {
        $securityFlags = $this->detectSuspiciousActivity($request, $userId, $deviceId);

        $log = BiometricLog::create([
            'user_id' => $userId,
            'device_id' => $deviceId,
            'method' => 'fingerprint',
            'action' => $success ? 'authenticate' : 'authenticate_failed',
            'success' => $success,
            'status_code' => $success ? 200 : 401,
            'error_message' => $success ? null : ($additionalData['error'] ?? 'Authentication failed'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_data' => $additionalData['request_data'] ?? [],
            'response_data' => $additionalData['response_data'] ?? [],
            'response_time_ms' => $additionalData['response_time_ms'] ?? 0,
            'assigned_shards' => $additionalData['shards'] ?? [],
            'shards_count' => count($additionalData['shards'] ?? []),
            'session_id' => $additionalData['session_id'] ?? null,
            'security_flags' => $securityFlags,
            'created_at' => now(),
        ]);

        // Update rate limiting
        if (!$success) {
            $this->incrementRateLimit($request, $userId);
        } else {
            $this->clearRateLimit($request, $userId);
        }

        return $log;
    }

    /**
     * Detect suspicious activity
     */
    private function detectSuspiciousActivity(Request $request, int|string $userId, string $deviceId): array
    {
        $flags = [];

        $recentFailures = BiometricLog::where('user_id', $userId)
            ->where('success', false)
            ->where('created_at', '>', now()->subMinutes(5))
            ->count();

        if ($recentFailures >= 3) {
            $flags[] = 'rapid_failures';
        }

        $previousIps = BiometricLog::where('user_id', $userId)
            ->where('success', true)
            ->where('created_at', '>', now()->subDays(30))
            ->distinct()
            ->pluck('ip_address')
            ->toArray();

        $currentIp = $request->ip();
        if (!in_array($currentIp, $previousIps) && count($previousIps) > 0) {
            $flags[] = 'new_ip_address';
        }

        $currentHour = now()->hour;
        if ($currentHour < 6 || $currentHour > 22) {
            $businessHourSuccesses = BiometricLog::where('user_id', $userId)
                ->where('success', true)
                ->whereRaw('HOUR(created_at) BETWEEN 9 AND 17')
                ->where('created_at', '>', now()->subDays(30))
                ->count();

            $totalSuccesses = BiometricLog::where('user_id', $userId)
                ->where('success', true)
                ->where('created_at', '>', now()->subDays(30))
                ->count();

            if ($totalSuccesses > 10 && $businessHourSuccesses / $totalSuccesses > 0.8) {
                $flags[] = 'unusual_timing';
            }
        }

        $commonUserAgents = BiometricLog::where('user_id', $userId)
            ->where('success', true)
            ->where('created_at', '>', now()->subDays(30))
            ->distinct()
            ->pluck('user_agent')
            ->filter()
            ->take(3)
            ->toArray();

        $currentUserAgent = $request->userAgent();
        if (!in_array($currentUserAgent, $commonUserAgents) && count($commonUserAgents) > 0) {
            $flags[] = 'unusual_user_agent';
        }

        return $flags;
    }

    /**
     * Get rate limit key
     */
    private function getRateLimitKey(Request $request, int|string|null $userId = null): string
    {
        $identifier = $userId ?: $request->ip();
        return "biometric_attempts:{$identifier}";
    }

    /**
     * Increment rate limit counter
     */
    private function incrementRateLimit(Request $request, int|string|null $userId = null): void
    {
        $key = $this->getRateLimitKey($request, $userId);

        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addMinutes($this->processedConfig['rate_limit_decay']));
    }

    /**
     * Clear rate limit counter on success
     */
    private function clearRateLimit(Request $request, int|string|null $userId = null): void
    {
        $key = $this->getRateLimitKey($request, $userId);
        Cache::forget($key);
    }

    /**
     * Get security status for user
     */
    public function getSecurityStatus(int|string $userId): array
    {
        $recentLogs = BiometricLog::where('user_id', $userId)
            ->where('created_at', '>', now()->subHours(24))
            ->get();

        $successRate = $recentLogs->count() > 0
            ? $recentLogs->where('success', true)->count() / $recentLogs->count()
            : 1;

        $recentFailures = $recentLogs->where('success', false)->count();
        $securityAlerts = $recentLogs->whereNotNull('security_flags')->count();

        return [
            'success_rate' => $successRate,
            'recent_failures' => $recentFailures,
            'security_alerts' => $securityAlerts,
            'risk_level' => $this->calculateRiskLevel($successRate, $recentFailures, $securityAlerts),
        ];
    }

    /**
     * Calculate risk level
     */
    private function calculateRiskLevel(float $successRate, int $recentFailures, int $securityAlerts): string
    {
        if ($successRate < 0.5 || $recentFailures > 5 || $securityAlerts > 2) {
            return 'high';
        }

        if ($successRate < 0.8 || $recentFailures > 2 || $securityAlerts > 0) {
            return 'medium';
        }

        return 'low';
    }
}
