<?php

declare(strict_types=1);

namespace Shard\UI\Biometric\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shard\UI\Biometric\Services\WebAuthnManager;

class BiometricController extends Controller
{
    private WebAuthnManager $webAuthnManager;

    public function __construct(WebAuthnManager $webAuthnManager)
    {
        $this->webAuthnManager = $webAuthnManager;
    }

    /**
     * Get WebAuthn registration options
     */
    public function registerOptions(Request $request): JsonResponse
    {
        try {
            $user = $request->input('user', [
                'id' => random_bytes(16),
                'name' => 'demo@shard-ui.com',
                'displayName' => 'Demo User'
            ]);

            $options = $this->webAuthnManager->createCredentialOptions($user);

            return response()->json([
                'success' => true,
                'options' => $options
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store WebAuthn credential
     */
    public function storeCredential(Request $request): JsonResponse
    {
        try {
            $credential = $request->all();
            
            // For demo, we'll just return success
            // In production, you'd store this in database
            
            return response()->json([
                'success' => true,
                'message' => 'Credential stored successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get WebAuthn authentication options
     */
    public function authenticateOptions(Request $request): JsonResponse
    {
        try {
            $deviceId = $request->input('device_id');
            $options = $this->webAuthnManager->createAssertionOptions($deviceId);

            return response()->json([
                'success' => true,
                'options' => $options
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify WebAuthn authentication
     */
    public function verifyAuthentication(Request $request): JsonResponse
    {
        try {
            $credential = $request->all();
            
            // For demo, we'll just return success with mock shards
            // In production, you'd verify the signature against stored credential
            
            return response()->json([
                'success' => true,
                'userId' => 1,
                'shards' => ['dashboard', 'controls', 'notifications'],
                'message' => 'Authentication verified successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get registered credentials
     */
    public function getCredentials(): JsonResponse
    {
        try {
            // For demo, return empty credentials
            // In production, you'd fetch from database
            
            return response()->json([
                'success' => true,
                'credentials' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register device
     */
    public function registerDevice(Request $request): JsonResponse
    {
        try {
            $deviceData = $request->all();
            
            // For demo, just return success
            // In production, you'd store in database
            
            return response()->json([
                'success' => true,
                'message' => 'Device registered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
