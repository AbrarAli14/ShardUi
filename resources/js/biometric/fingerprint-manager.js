/**
 * Shard UI Fingerprint Biometric Manager
 * Handles WebAuthn fingerprint authentication for device assignment
 */
class FingerprintManager {
    constructor(options = {}) {
        this.baseUrl = options.baseUrl || window.location.origin;
        this.debug = options.debug || false;
        this.webAuthnSupported = this.checkWebAuthnSupport();
        
        this.log('FingerprintManager initialized', {
            webAuthnSupported: this.webAuthnSupported,
            baseUrl: this.baseUrl
        });
    }

    /**
     * Check if WebAuthn is supported
     */
    checkWebAuthnSupport() {
        return !!(navigator.credentials && navigator.credentials.create && navigator.credentials.get);
    }

    /**
     * Register fingerprint for current user
     */
    async registerFingerprint(deviceInfo = {}) {
        if (!this.webAuthnSupported) {
            throw new Error('WebAuthn is not supported on this device');
        }

        try {
            this.log('Starting fingerprint registration...');

            // Get registration options from server
            const response = await this.makeRequest('/api/biometric/fingerprint/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (!response.success) {
                throw new Error(response.error || 'Failed to get registration options');
            }

            // Create credential using device fingerprint sensor
            const credential = await navigator.credentials.create({
                publicKey: response.options
            });

            this.log('Credential created', { credentialId: credential.id });

            // Convert credential to base64 for transmission
            const credentialData = this.serializeCredential(credential);

            // Send credential back to server
            const storeResponse = await this.makeRequest('/api/biometric/fingerprint/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify(credentialData)
            });

            if (storeResponse.success) {
                this.log('Fingerprint registered successfully');
                
                // Register device if device info provided
                if (deviceInfo.device_id) {
                    await this.registerDevice(deviceInfo);
                }

                return {
                    success: true,
                    credentialId: credential.id,
                    message: 'Fingerprint registered successfully'
                };
            } else {
                throw new Error(storeResponse.error || 'Failed to store fingerprint');
            }

        } catch (error) {
            this.log('Fingerprint registration failed', { error: error.message });
            throw error;
        }
    }

    /**
     * Authenticate using fingerprint and get shards
     */
    async authenticateWithFingerprint(deviceId) {
        if (!this.webAuthnSupported) {
            throw new Error('WebAuthn is not supported on this device');
        }

        try {
            this.log('Starting fingerprint authentication...', { deviceId });

            // Get authentication options for device
            const authResponse = await this.makeRequest('/api/biometric/fingerprint/authenticate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ device_id: deviceId })
            });

            if (!authResponse.success) {
                throw new Error(authResponse.error || 'Device not found or not registered');
            }

            this.log('Got authentication options', authResponse.options);

            // Use fingerprint sensor to authenticate
            const credential = await navigator.credentials.get({
                publicKey: authResponse.options.webauthn_options
            });

            this.log('Authentication successful', { credentialId: credential.id });

            // Convert credential to base64 for transmission
            const credentialData = this.serializeCredential(credential);

            // Send authentication result to server
            const verifyResponse = await this.makeRequest('/api/biometric/fingerprint/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    device_id: deviceId,
                    credential: credentialData
                })
            });

            if (verifyResponse.success) {
                this.log('Authentication verified, shards assigned', verifyResponse);

                // Load user's personalized shards
                await this.loadUserShards(verifyResponse.user_id, deviceId);

                return {
                    success: true,
                    userId: verifyResponse.user_id,
                    deviceId: verifyResponse.device_id,
                    shardsAssigned: verifyResponse.shards_assigned,
                    message: 'Authentication successful'
                };
            } else {
                throw new Error(verifyResponse.error || 'Authentication verification failed');
            }

        } catch (error) {
            this.log('Fingerprint authentication failed', { error: error.message });
            throw error;
        }
    }

    /**
     * Register device for biometric authentication
     */
    async registerDevice(deviceInfo) {
        try {
            this.log('Registering device...', deviceInfo);

            const response = await this.makeRequest('/api/biometric/device/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify(deviceInfo)
            });

            if (response.success) {
                this.log('Device registered successfully');
                return response;
            } else {
                throw new Error(response.error || 'Failed to register device');
            }

        } catch (error) {
            this.log('Device registration failed', { error: error.message });
            throw error;
        }
    }

    /**
     * Load user's personalized shards via WebSocket
     */
    async loadUserShards(userId, deviceId) {
        try {
            this.log('Loading user shards...', { userId, deviceId });

            // Connect to WebSocket for real-time shard updates
            const wsUrl = `ws://localhost:8000/shard/${userId}/${deviceId}`;
            const socket = new WebSocket(wsUrl);

            socket.onopen = () => {
                this.log('WebSocket connected for shard updates');
            };

            socket.onmessage = (event) => {
                const shardData = JSON.parse(event.data);
                this.log('Received shard data', shardData);
                this.renderShard(shardData);
            };

            socket.onerror = (error) => {
                this.log('WebSocket error', error);
            };

            socket.onclose = () => {
                this.log('WebSocket connection closed');
            };

            // Store socket reference for later cleanup
            this.shardSocket = socket;

        } catch (error) {
            this.log('Failed to load user shards', { error: error.message });
            throw error;
        }
    }

    /**
     * Render shard on the page
     */
    renderShard(shardData) {
        this.log('Rendering shard', shardData);

        // Find or create shard container
        let shardContainer = document.getElementById(`shard-${shardData.name}`);
        
        if (!shardContainer) {
            shardContainer = document.createElement('div');
            shardContainer.id = `shard-${shardData.name}`;
            shardContainer.className = 'shard-container';
            document.body.appendChild(shardContainer);
        }

        // Update shard content
        shardContainer.innerHTML = shardData.html;

        // Execute shard JavaScript if provided
        if (shardData.js) {
            try {
                eval(shardData.js);
            } catch (error) {
                this.log('Error executing shard JavaScript', { error: error.message });
            }
        }

        // Apply shard CSS if provided
        if (shardData.css) {
            let styleElement = document.getElementById(`shard-style-${shardData.name}`);
            if (!styleElement) {
                styleElement = document.createElement('style');
                styleElement.id = `shard-style-${shardData.name}`;
                document.head.appendChild(styleElement);
            }
            styleElement.textContent = shardData.css;
        }

        // Trigger shard loaded event
        window.dispatchEvent(new CustomEvent('shardLoaded', {
            detail: { shard: shardData }
        }));
    }

    /**
     * Check device compatibility
     */
    async checkCompatibility() {
        try {
            const response = await this.makeRequest('/api/biometric/device/compatibility', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_agent: navigator.userAgent
                })
            });

            return response.success ? response.compatibility : null;
        } catch (error) {
            this.log('Compatibility check failed', { error: error.message });
            return null;
        }
    }

    /**
     * Serialize WebAuthn credential for transmission
     */
    serializeCredential(credential) {
        return {
            id: credential.id,
            rawId: this.arrayBufferToBase64(credential.rawId),
            response: {
                attestationObject: this.arrayBufferToBase64(credential.response.attestationObject),
                clientDataJSON: this.arrayBufferToBase64(credential.response.clientDataJSON)
            },
            type: credential.type
        };
    }

    /**
     * Convert ArrayBuffer to base64
     */
    arrayBufferToBase64(buffer) {
        let binary = '';
        const bytes = new Uint8Array(buffer);
        const len = bytes.byteLength;
        for (let i = 0; i < len; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return btoa(binary);
    }

    /**
     * Get CSRF token from meta tag
     */
    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * Make HTTP request with error handling
     */
    async makeRequest(url, options = {}) {
        try {
            const response = await fetch(this.baseUrl + url, options);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || `HTTP ${response.status}`);
            }
            
            return data;
        } catch (error) {
            this.log('Request failed', { url, error: error.message });
            throw error;
        }
    }

    /**
     * Cleanup resources
     */
    cleanup() {
        if (this.shardSocket) {
            this.shardSocket.close();
            this.shardSocket = null;
        }
    }

    /**
     * Debug logging
     */
    log(message, data = null) {
        if (this.debug) {
            console.log(`[FingerprintManager] ${message}`, data);
        }
    }
}

// Auto-initialize if available
window.FingerprintManager = FingerprintManager;

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FingerprintManager;
}
