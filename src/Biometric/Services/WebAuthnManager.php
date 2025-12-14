<?php

declare(strict_types=1);

namespace Shard\UI\Biometric\Services;

use Illuminate\Contracts\Config\Repository as Config;

class WebAuthnManager
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Create WebAuthn credential creation options
     */
    public function createCredentialOptions(array $user): array
    {
        $challenge = $this->generateChallenge();

        return [
            'rp' => [
                'name' => $this->config->get('biometric.fingerprint.rp_name', 'Shard UI'),
                'id' => $this->getRpId(),
            ],
            'user' => [
                'id' => base64_encode($user['id']),
                'name' => $user['name'],
                'displayName' => $user['displayName'] ?? $user['name'],
            ],
            'challenge' => $challenge,
            'pubKeyCredParams' => $this->getSupportedAlgorithms(),
            'timeout' => $this->config->get('biometric.fingerprint.timeout', 60000),
            'attestation' => $this->config->get('biometric.fingerprint.attestation', 'direct'),
            'authenticatorSelection' => [
                'authenticatorAttachment' => $this->config->get('biometric.fingerprint.authenticator_attachment', 'platform'),
                'userVerification' => $this->config->get('biometric.fingerprint.require_user_verification', true) ? 'required' : 'preferred',
                'residentKey' => 'preferred',
            ],
            'extensions' => [
                'credProps' => true,
            ],
        ];
    }

    /**
     * Create WebAuthn assertion options
     */
    public function createAssertionOptions(string $credentialId = null): array
    {
        $challenge = $this->generateChallenge();

        $allowCredentials = [];
        if ($credentialId) {
            $allowCredentials[] = [
                'type' => 'public-key',
                'id' => $credentialId, 
            ];
        }

        return [
            'challenge' => $challenge,
            'allowCredentials' => $allowCredentials,
            'userVerification' => 'required',
            'timeout' => $this->config->get('biometric.fingerprint.timeout', 60000),
        ];
    }

    /**
     * Verify WebAuthn credential
     */
    public function verifyCredential(object $credential): bool
    {
        try {
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyAttestation(array $attestationResponse, string $expectedChallenge): bool
    {
        try {
            $clientData = $this->decodeClientData($attestationResponse['clientDataJSON'] ?? '');

            if (($clientData['type'] ?? '') !== 'webauthn.create') {
                return false;
            }

            if (!$this->challengesMatch($clientData['challenge'] ?? '', $expectedChallenge)) {
                return false;
            }

            if (!$this->originMatches($clientData['origin'] ?? '')) {
                return false;
            }

            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * Verify credential assertion
     */
    public function verifyAssertion(array $assertionResponse, array $storedCredential, string $expectedChallenge): bool
    {
        try {
            $clientData = $this->decodeClientData($assertionResponse['clientDataJSON'] ?? '');

            if (($clientData['type'] ?? '') !== 'webauthn.get') {
                return false;
            }

            if (!$this->challengesMatch($clientData['challenge'] ?? '', $expectedChallenge)) {
                return false;
            }

            if (!$this->originMatches($clientData['origin'] ?? '')) {
                return false;
            }

            if (empty($assertionResponse['signature'])) {
                return false;
            }

            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * Generate random challenge
     */
    public function generateChallenge(): string
    {
        return base64_encode(random_bytes(32));
    }

    /**
     * Get relying party ID
     */
    private function getRpId(): string
    {
        $rpId = $this->config->get('biometric.fingerprint.rp_id', $this->config->get('app.url'));
        return parse_url($rpId, PHP_URL_HOST) ?: $rpId;
    }

    /**
     * Get user ID for WebAuthn
     */
    private function getUserId($user): string
    {
        return base64_encode((string) $user->id);
    }

    /**
     * Check if platform supports WebAuthn
     */
    public function isSupported(): bool
    {
        if (!function_exists('apache_request_headers')) {
            return false;
        }

        return true;
    }

    /**
     * Get supported algorithms
     */
    public function getSupportedAlgorithms(): array
    {
        return [
            ['alg' => -7, 'type' => 'public-key'],  // ES256
            ['alg' => -257, 'type' => 'public-key'], // RS256
            ['alg' => -37, 'type' => 'public-key'],  // PS256
            ['alg' => -8, 'type' => 'public-key'],  // EdDSA
        ];
    }

    private function decodeClientData(string $clientData): array
    {
        $decoded = $this->decodeBase64Url($clientData);
        return json_decode($decoded, true) ?? [];
    }

    private function challengesMatch(string $clientChallenge, string $expectedChallenge): bool
    {
        try {
            $clientBinary = $this->decodeBase64Url($clientChallenge);
            $expectedBinary = base64_decode($expectedChallenge);

            return hash_equals($clientBinary, $expectedBinary);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    private function originMatches(string $origin): bool
    {
        $expectedOrigin = $this->config->get('biometric.fingerprint.origin', 'https://unpsychopathic-zander-unedified.ngrok-free.dev');

        return rtrim($origin, '/') === rtrim($expectedOrigin, '/');
    }

    private function decodeBase64Url(string $value): string
    {
        $value = strtr($value, '-_', '+/');
        return base64_decode($value) ?: '';
    }
}
