<?php

declare(strict_types=1);

namespace Shard\Ui\Biometric\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class FingerprintCredential extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'credential_id',
        'public_key',
        'credential_data',
        'device_type',
        'authenticator_type',
        'algorithm',
        'active',
        'last_used_at',
        'usage_count',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'public_key' => 'array',
        'credential_data' => 'array',
        'active' => 'boolean',
        'last_used_at' => 'datetime',
        'usage_count' => 'integer',
    ];

    /**
     * Get the user that owns the credential.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the device that owns the credential.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id', 'device_id');
    }

    /**
     * Mark credential as used
     */
    public function markAsUsed(string $ipAddress = null, string $userAgent = null): void
    {
        $this->update([
            'last_used_at' => now(),
            'usage_count' => $this->usage_count + 1,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }

    /**
     * Deactivate credential
     */
    public function deactivate(): void
    {
        $this->update(['active' => false]);
    }

    /**
     * Check if credential is expired
     */
    
    public function isExpired(): bool
    {
        $expirationDays = config('biometric.fingerprint.credential_expiration_days', 365);
        return $this->created_at->diffInDays(now()) > $expirationDays;
    }

    /**
     * Scope to get only active credentials
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get credentials for a specific device
     */
    public function scopeForDevice($query, string $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }
}
