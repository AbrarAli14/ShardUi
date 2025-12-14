<?php

declare(strict_types=1);

namespace Shard\UI\Biometric\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Shard\UI\Biometric\Services\FingerprintAuth;
use Shard\UI\Biometric\Services\DeviceFingerprintAuth;
use Illuminate\Support\Facades\Auth;

class FingerprintController extends Controller
{
    private FingerprintAuth $fingerprintAuth;
    private DeviceFingerprintAuth $deviceAuth;

    public function __construct(FingerprintAuth $fingerprintAuth, DeviceFingerprintAuth $deviceAuth)
    {
        $this->fingerprintAuth = $fingerprintAuth;
        $this->deviceAuth = $deviceAuth;
    }

    /**
     * Register fingerprint for current user
     */
    public function register(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $options = $this->fingerprintAuth->generateCreationOptions($user);
            
            return response()->json([
                'success' => true,
                'options' => $options
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate registration options'
            ], 500);
        }
    }

    /**
     * Store fingerprint credential
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $credential = $request->validate([
            'id' => 'required|string',
            'rawId' => 'required|string',
            'response' => 'required|array',
            'type' => 'required|string|in:public-key'
        ]);

        try {
            $success = $this->fingerprintAuth->verifyAndStoreCredential($user, $credential);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fingerprint registered successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to register fingerprint'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Registration failed'
            ], 500);
        }
    }

    /**
     * Get authentication options for device
     */
    public function authenticate(Request $request): JsonResponse
    {
        $request->validate([
            'device_id' => 'required|string'
        ]);

        try {
            $options = $this->deviceAuth->getDeviceAuthOptions($request->device_id);
            
            if (!$options) {
                return response()->json([
                    'success' => false,
                    'error' => 'Device not found or not registered'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'options' => $options
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get authentication options'
            ], 500);
        }
    }

    /**
     * Verify fingerprint authentication
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'device_id' => 'required|string',
            'credential' => 'required|array'
        ]);

        try {
            $result = $this->deviceAuth->authenticateDevice(
                $request->device_id,
                $request->credential
            );

            if ($result && $result['success']) {
                return response()->json($result);
            }

            return response()->json([
                'success' => false,
                'error' => 'Authentication failed'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Authentication verification failed'
            ], 500);
        }
    }

    /**
     * Register device for biometric authentication
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $deviceInfo = $request->validate([
            'device_id' => 'required|string',
            'name' => 'required|string',
            'type' => 'required|string|in:mobile,tablet,kiosk,workstation',
            'capabilities' => 'array',
            'biometric_methods' => 'array'
        ]);

        try {
            $success = $this->deviceAuth->registerDevice(
                $request->device_id,
                $user,
                $deviceInfo
            );

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device registered successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to register device'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Device registration failed'
            ], 500);
        }
    }

    /**
     * Get user's registered credentials
     */
    public function credentials(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // This would typically query the database for user's credentials
            $credentials = []; // Placeholder

            return response()->json([
                'success' => true,
                'credentials' => $credentials
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get credentials'
            ], 500);
        }
    }

    /**
     * Delete fingerprint credential
     */
    public function delete(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'credential_id' => 'required|string'
        ]);

        try {
            // This would typically delete from database
            // Placeholder implementation
            
            return response()->json([
                'success' => true,
                'message' => 'Credential deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete credential'
            ], 500);
        }
    }
}
