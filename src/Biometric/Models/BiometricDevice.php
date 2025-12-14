<?php

declare(strict_types=1);

namespace Shard\Ui\Biometric\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class BiometricDevice extends Model
{
    protected $fillable = [
        'device_id',
        'name',
        'type',
        'capabilities',
        'supported_methods',
        'hardware_info',
        'registered_by',
        'registered_at',
        'registration_ip',
        'active',
        'location',
        'geo_location',
        'require_user_verification',
        'max_attempts',
        'lockout_minutes',
        'auto_assign_shards',
        'default_shards',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'supported_methods' => 'array',
        'hardware_info' => 'array',
        'registered_at' => 'datetime',
        'active' => 'boolean',
        'geo_location' => 'array',
        'require_user_verification' => 'boolean',
        'max_attempts' => 'integer',
        'lockout_minutes' => 'integer',
        'auto_assign_shards' => 'boolean',
        'default_shards' => 'array',
    ];

    /**
     * Get the user who registered the device.
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get the fingerprint credentials for this device.
     */
    public function fingerprintCredentials(): HasMany
    {
        return $this->hasMany(FingerprintCredential::class, 'device_id', 'device_id');
    }

    /**
     * Get the biometric logs for this device.
     */
    public function biometricLogs(): HasMany
    {
        return $this->hasMany(BiometricLog::class, 'device_id', 'device_id');
    }

    /**
     * Check if device supports fingerprint authentication
     */
    public function supportsFingerprint(): bool
    {
        return in_array('fingerprint', $this->supported_methods ?? []);
    }

    /**
     * Check if device supports face recognition
     */
    public function supportsFace(): bool
    {
        return in_array('face', $this->supported_methods ?? []);
    }

    /**
     * Check if device supports voice authentication
     */
    public function supportsVoice(): bool
    {
        return in_array('voice', $this->supported_methods ?? []);
    }

    /**
     * Get the primary authentication method for this device
     */
    public function getPrimaryMethod(): ?string
    {
        $methods = $this->supported_methods ?? [];
        return $methods[0] ?? null;
    }

    /**
     * Check if device is currently locked out
     */
    public function isLockedOut(): bool
    {
        // Check recent failed attempts
        $recentFailures = $this->biometricLogs()
            ->where('success', false)
            ->where('created_at', '>', now()->subMinutes($this->lockout_minutes))
            ->count();

        return $recentFailures >= $this->max_attempts;
    }

    /**
     * Get time until lockout expires
     */
    public function getLockoutExpiresAt(): ?\Carbon\Carbon
    {
        if (!$this->isLockedOut()) {
            return null;
        }

        $lastFailure = $this->biometricLogs()
            ->where('success', false)
            ->orderBy('created_at', 'desc')
            ->first();

        return $lastFailure ? $lastFailure->created_at->addMinutes($this->lockout_minutes) : null;
    }

    /**
     * Scope to get only active devices
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get devices by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get devices that support specific method
     */
    public function scopeSupportingMethod($query, string $method)
    {
        return $query->whereJsonContains('supported_methods', $method);
    }
}
