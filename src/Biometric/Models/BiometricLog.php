<?php

declare(strict_types=1);

namespace Shard\Ui\Biometric\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class BiometricLog extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'method',
        'action',
        'success',
        'status_code',
        'error_message',
        'ip_address',
        'user_agent',
        'request_data',
        'response_data',
        'response_time_ms',
        'assigned_shards',
        'shards_count',
        'session_id',
        'security_flags',
        'created_at',
    ];

    protected $casts = [
        'success' => 'boolean',
        'response_time_ms' => 'integer',
        'shards_count' => 'integer',
        'request_data' => 'array',
        'response_data' => 'array',
        'assigned_shards' => 'array',
        'security_flags' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Get the user that owns the log entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the device that owns the log entry.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id', 'device_id');
    }

    /**
     * Scope to get successful authentications
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope to get failed authentications
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope to get logs by method
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope to get logs by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to get logs for a specific time period
     */
    public function scopeTimeRange($query, \Carbon\Carbon $start, \Carbon\Carbon $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Scope to get recent logs
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>', now()->subHours($hours));
    }

    /**
     * Check if log indicates a security concern
     */
    public function isSecurityConcern(): bool
    {
        return $this->hasSecurityFlags() ||
               $this->isFromSuspiciousLocation() ||
               $this->hasUnusualUserAgent() ||
               $this->isRapidFailure();
    }

    /**
     * Check if log has security flags
     */
    public function hasSecurityFlags(): bool
    {
        return !empty($this->security_flags);
    }

    /**
     * Check if request is from suspicious location
     */
    public function isFromSuspiciousLocation(): bool
    {
        // This would integrate with GeoIP service
        // For now, return false as placeholder
        return false;
    }

    /**
     * Check if user agent is unusual
     */
    public function hasUnusualUserAgent(): bool
    {
        if (!$this->user_agent) {
            return false;
        }

        // Check for suspicious patterns
        $suspicious = [
            'bot', 'crawler', 'spider',
            'python', 'curl', 'wget',
            'postman', 'insomnia'
        ];

        $userAgent = strtolower($this->user_agent);
        foreach ($suspicious as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if this is part of rapid failure pattern
     */
    public function isRapidFailure(): bool
    {
        if ($this->success) {
            return false;
        }

        // Count failures in last 5 minutes for this user
        $recentFailures = self::where('user_id', $this->user_id)
            ->where('success', false)
            ->where('created_at', '>=', $this->created_at->copy()->subMinutes(5))
            ->where('created_at', '<=', $this->created_at)
            ->count();

        return $recentFailures >= 3;
    }

    /**
     * Get security severity level
     */
    public function getSecuritySeverity(): string
    {
        if ($this->hasUnusualUserAgent()) {
            return 'high';
        }

        if ($this->hasSecurityFlags() || $this->isRapidFailure()) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Scope to get security alerts
     */
    public function scopeSecurityAlerts($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('security_flags')
              ->orWhereRaw("LOWER(user_agent) LIKE '%bot%'")
              ->orWhereRaw("LOWER(user_agent) LIKE '%crawler%'")
              ->orWhereRaw("LOWER(user_agent) LIKE '%python%'");
        });
    }

    /**
     * Scope to get recent security events
     */
    public function scopeRecentSecurityEvents($query, int $hours = 24)
    {
        return $query->securityAlerts()
                    ->where('created_at', '>', now()->subHours($hours));
    }
}
