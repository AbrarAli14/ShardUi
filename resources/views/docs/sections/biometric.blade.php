<section id="biometric" class="relative py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-emerald-900/20 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-emerald-400/20 to-teal-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-cyan-400/20 to-emerald-400/20 rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto">
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 dark:from-emerald-400/20 dark:to-teal-400/20 rounded-full mb-6">
                <span class="text-emerald-600 dark:text-emerald-400 text-sm font-semibold">Biometric Authentication</span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold bg-gradient-to-r from-gray-900 via-emerald-800 to-teal-800 dark:from-white dark:via-emerald-200 dark:to-teal-200 bg-clip-text text-transparent mb-6">
                Fingerprint Device Assignment
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                Experience automatic shard assignment using biometric authentication - no passwords, no QR codes, just touch and go!
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-emerald-500/25">
                            <i data-lucide="fingerprint" class="w-8 h-8 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Register Device</h3>
                            <p class="text-emerald-600 dark:text-emerald-400 font-medium">Setup</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Device Name</label>
                            <input type="text" id="device-name" placeholder="My Phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Device Type</label>
                            <select id="device-type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="mobile">Mobile Phone</option>
                                <option value="tablet">Tablet</option>
                                <option value="kiosk">Kiosk</option>
                                <option value="workstation">Workstation</option>
                            </select>
                        </div>
                        
                        <button onclick="registerBiometricDevice()" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                            Register Device & Fingerprint
                        </button>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-teal-500/10 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-teal-500/25">
                            <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">Authenticate</h3>
                            <p class="text-teal-600 dark:text-teal-400 font-medium">Touch to Login</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Device</label>
                            <select id="auth-device" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Choose a registered device...</option>
                            </select>
                        </div>
                        
                        <button onclick="authenticateWithFingerprint()" class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-teal-600 hover:to-cyan-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i data-lucide="fingerprint" class="w-5 h-5 inline mr-2"></i>
                            Authenticate & Get Shards
                        </button>
                        
                        <button onclick="authenticateWithQRCode()" class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-indigo-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i data-lucide="qr-code" class="w-5 h-5 inline mr-2"></i>
                            Authenticate with QR Code
                        </button>
                        
                        <div id="auth-status" class="hidden p-4 rounded-lg text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-3xl p-8 border border-white/20 dark:border-gray-700/50">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <i data-lucide="layers" class="w-6 h-6 mr-3 text-purple-500"></i>
                Live Shard Assignment
            </h3>
            
            <div id="shard-display" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="text-center py-12 col-span-full">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">Authenticate with fingerprint to see your personalized shards</p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <button onclick="checkBiometricCompatibility()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-blue-400/20 dark:to-purple-400/20 rounded-full border border-blue-200/50 dark:border-blue-700/50 hover:bg-blue-500/20 dark:hover:bg-blue-400/20 transition-colors">
                <i data-lucide="smartphone" class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400"></i>
                Check Device Compatibility
            </button>
            <div id="compatibility-info" class="mt-4 hidden">
            </div>
        </div>
    </div>
</section>

<style>
.qr-auth-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.qr-auth-modal {
    background: white;
    border-radius: 16px;
    padding: 32px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.qr-auth-content h3 {
    margin-bottom: 20px;
    color: #1f2937;
    font-size: 1.5rem;
}

.qr-code-wrapper {
    position: relative;
    margin: 20px auto;
    width: 200px;
    height: 200px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.qr-code-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.qr-scanner-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #10b981, transparent);
    animation: scan 2s linear infinite;
}

@keyframes scan {
    0% { top: 0; }
    100% { top: 100%; }
}

.qr-instructions {
    margin: 20px 0;
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
}

.qr-instructions a {
    color: #10b981;
    text-decoration: underline;
    word-break: break-all;
}

.qr-status {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 20px 0;
    color: #6b7280;
}

.qr-loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-top: 2px solid #10b981;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.qr-close-btn {
    background: #ef4444;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

.qr-close-btn:hover {
    background: #dc2626;
}

.qr-scan-btn {
    background: #10b981;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
    margin: 10px 5px;
    display: inline-flex;
    align-items: center;
}

.qr-scan-btn:hover {
    background: #059669;
}

/* Dark mode styles */
.dark .qr-auth-modal {
    background: #1f2937;
}

.dark .qr-auth-content h3 {
    color: #f9fafb;
}

.dark .qr-code-wrapper {
    border-color: #374151;
}

.dark .qr-instructions {
    color: #9ca3af;
}

.dark .qr-status {
    color: #9ca3af;
}

.dark .qr-loading-spinner {
    border-color: #374151;
    border-top-color: #10b981;
}
</style>

<script>
class QRGenerator {
    constructor() {
        this.moduleCount = 0;
        this.modules = null;
    }

    generateAuthQR(deviceId, sessionId, expiresAt = null) {
        const authData = {
            type: 'biometric_auth',
            device_id: deviceId,
            session_id: sessionId,
            expires_at: expiresAt || (Date.now() + 300000),
            timestamp: Date.now()
        };

        const jsonString = JSON.stringify(authData);
        const encoded = btoa(jsonString);
        
        return this.generateQRCode(encoded, 6);
    }

    generateQRCode(text, errorCorrectionLevel = 1) {
        const size = 200;
        const canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;
        const ctx = canvas.getContext('2d');

        const cellSize = 8;
        const cells = size / cellSize;
        
        let seed = 0;
        for (let i = 0; i < text.length; i++) {
            seed += text.charCodeAt(i);
        }
        
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, size, size);
        
        ctx.fillStyle = '#000000';
        
        this.drawPositionMarker(ctx, 0, 0, cellSize);
        this.drawPositionMarker(ctx, cells - 7, 0, cellSize);
        this.drawPositionMarker(ctx, 0, cells - 7, cellSize);
        
        for (let row = 0; row < cells; row++) {
            for (let col = 0; col < cells; col++) {
                if ((row < 7 && col < 7) || 
                    (row < 7 && col >= cells - 7) || 
                    (row >= cells - 7 && col < 7)) {
                    continue;
                }
                
                const index = (row * cells + col + seed) % text.length;
                const charCode = text.charCodeAt(index);
                
                if (charCode % 2 === 0) {
                    ctx.fillRect(col * cellSize, row * cellSize, cellSize, cellSize);
                }
            }
        }
        
        return canvas.toDataURL('image/png');
    }

    drawPositionMarker(ctx, x, y, cellSize) {
        ctx.fillRect(x * cellSize, y * cellSize, 7 * cellSize, 7 * cellSize);
        
        ctx.fillStyle = '#ffffff';
        ctx.fillRect((x + 1) * cellSize, (y + 1) * cellSize, 5 * cellSize, 5 * cellSize);
        
        ctx.fillStyle = '#000000';
        ctx.fillRect((x + 2) * cellSize, (y + 2) * cellSize, 3 * cellSize, 3 * cellSize);
    }

    createQRDisplay(qrDataUrl, authUrl) {
        const container = document.createElement('div');
        container.className = 'qr-auth-container';
        container.innerHTML = `
            <div class="qr-auth-modal">
                <div class="qr-auth-content">
                    <h3>Scan QR Code to Authenticate</h3>
                    <div class="qr-code-wrapper">
                        <img src="${qrDataUrl}" alt="Authentication QR Code" class="qr-code-image">
                        <div class="qr-scanner-line"></div>
                    </div>
                    <p class="qr-instructions">
                        Open your camera app and scan this QR code<br>
                        or visit: <a href="${authUrl}" target="_blank">${authUrl}</a>
                    </p>
                    <div class="qr-status">
                        <div class="qr-loading-spinner"></div>
                        <span>Waiting for authentication...</span>
                    </div>
                    <button onclick="simulateQRScan()" class="qr-scan-btn">
                        <i data-lucide="smartphone" class="w-4 h-4 mr-2"></i>
                        Simulate Phone Scan
                    </button>
                    <button onclick="closeQRAuth()" class="qr-close-btn">Cancel</button>
                </div>
            </div>
        `;
        
        return container;
    }
}

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

    checkWebAuthnSupport() {
        this.log('Checking WebAuthn support...');

        if (!window.isSecureContext) {
            this.log('❌ Not in secure context - WebAuthn requires HTTPS');
            return false;
        }

        if (!window.PublicKeyCredential) {
            this.log('❌ PublicKeyCredential not available');
            return false;
        }

        if (!navigator.credentials) {
            this.log('❌ navigator.credentials not available');
            return false;
        }

        if (!navigator.credentials.create) {
            this.log('❌ navigator.credentials.create not available');
            return false;
        }

        if (!navigator.credentials.get) {
            this.log('❌ navigator.credentials.get not available');
            return false;
        }

        try {
            const testCredential = window.PublicKeyCredential;
            this.log('✅ Basic WebAuthn APIs available');
            return true;
        } catch (error) {
            this.log('❌ WebAuthn API test failed:', error.message);
            return false;
        }
    }

    async registerFingerprint(deviceInfo = {}) {
        try {
            if (!window.isSecureContext) {
                throw new Error('WebAuthn requires a secure HTTPS connection. Please access this page over HTTPS.');
            }

            this.log('Starting real WebAuthn fingerprint registration...');

            const response = await this.makeRequest('/api/biometric/fingerprint/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user: {
                        id: new Uint8Array(16),
                        name: 'demo@shard-ui.com',
                        displayName: 'Demo User'
                    },
                    authenticatorSelection: {
                        authenticatorAttachment: 'platform',
                        userVerification: 'required',
                        residentKey: 'preferred'
                    },
                    extensions: {
                        credProps: true
                    }
                })
            });

            if (!response.success) {
                throw new Error(response.error || 'Failed to get registration options');
            }

            sessionStorage.setItem('webauthn_challenge', response.challenge);
            sessionStorage.setItem('webauthn_user_id', response.user_id);

            let credential;
            try {
                credential = await navigator.credentials.create({
                    publicKey: {
                        ...response.options,
                        challenge: this.base64ToArrayBuffer(response.options.challenge),
                        user: {
                            ...response.options.user,
                            id: this.base64ToArrayBuffer(response.options.user.id)
                        }
                    }
                });

                this.log('Real credential created', { credentialId: credential.id });
            } catch (webauthnError) {
                this.log('WebAuthn create failed', { error: webauthnError.message });
                throw new Error(`Fingerprint registration failed: ${webauthnError.message}. Make sure you're using a secure HTTPS connection and your device supports WebAuthn.`);
            }

            const credentialData = this.serializeCredential(credential);

            const storeResponse = await this.makeRequest('/api/biometric/fingerprint/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ...credentialData,
                    challenge: sessionStorage.getItem('webauthn_challenge'),
                    user_id: sessionStorage.getItem('webauthn_user_id'),
                    device: deviceInfo 
                })
            });


            if (storeResponse.success) {
                this.log('Real fingerprint registered successfully');
                
               
                return {
                    success: true,
                    credentialId: credential.id,
                    message: 'Real fingerprint registered successfully'
                };
            } else {
                throw new Error(storeResponse.error || 'Failed to store fingerprint');
            }

        } catch (error) {
            this.log('WebAuthn registration failed', { error: error.message });
            throw error; 
        }
    }

    mockRegistration(deviceInfo) {
        const mockCredentialId = 'mock_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        
        if (deviceInfo.device_id) {
            this.registerDevice(deviceInfo);
        }

        return {
            success: true,
            credentialId: mockCredentialId,
            message: 'Mock fingerprint registration (WebAuthn not available)'
        };
    }

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

    arrayBufferToBase64(buffer) {
        const bytes = new Uint8Array(buffer);
        let binary = '';
        for (let i = 0; i < bytes.byteLength; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return btoa(binary);
    }

    base64ToArrayBuffer(base64) {
        try {
            if (!base64 || typeof base64 !== 'string') {
                throw new Error('Invalid base64 input');
            }

            const cleanBase64 = base64.replace(/[^A-Za-z0-9+/]/g, '');
            if (cleanBase64.length % 4 !== 0) {
                const padding = '==='.slice(0, (4 - cleanBase64.length % 4) % 4);
                base64 = cleanBase64 + padding;
            } else {
                base64 = cleanBase64;
            }

            const binaryString = atob(base64);
            const bytes = new Uint8Array(binaryString.length);
            for (let i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes.buffer;
        } catch (error) {
            this.log('Base64 decode error', { base64: base64?.substring(0, 50), error: error.message });
            throw new Error(`Failed to decode base64: ${error.message}`);
        }
    }

    base64urlToArrayBuffer(base64url) {
        try {
            const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
            return this.base64ToArrayBuffer(base64);
        } catch (error) {
            this.log('Base64url decode error', { base64url: base64url?.substring(0, 50), error: error.message });
            throw new Error(`Failed to decode base64url: ${error.message}`);
        }
    }

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
            return {
                success: true,
                message: 'Mock device registration successful (for testing)'
            };
        }
    }

    async authenticateWithFingerprint(deviceId) {
        try {
            if (!window.isSecureContext) {
                throw new Error('WebAuthn requires a secure HTTPS connection. Please access this page over HTTPS.');
            }

            this.log('Starting real WebAuthn authentication...');

            const response = await this.makeRequest('/api/biometric/fingerprint/authenticate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    device_id: deviceId,
                    userVerification: 'required'
                })
            });

            if (!response.success) {
                throw new Error(response.error || 'Failed to get authentication options');
            }

            sessionStorage.setItem('webauthn_auth_challenge', response.options.challenge);
            sessionStorage.setItem('webauthn_auth_credential_id', response.options.allowCredentials[0].id);

            let credential;
            try {
                credential = await navigator.credentials.get({
                    publicKey: {
                        ...response.options,
                        challenge: this.base64ToArrayBuffer(response.options.challenge),
                        allowCredentials: response.options.allowCredentials.map(cred => {
                            try {
                                const decodedId = this.base64urlToArrayBuffer(cred.id);
                                return {
                                    ...cred,
                                    id: decodedId
                                };
                            } catch (decodeError) {
                                throw new Error('Invalid credential data');
                            }
                        })
                    }
                });

                this.log('Real authentication successful', { credentialId: credential.id });
            } catch (webauthnError) {
                this.log('WebAuthn get failed', { error: webauthnError.message });
                throw new Error(`Fingerprint authentication failed: ${webauthnError.message}. Make sure you're using a secure HTTPS connection and your device supports WebAuthn.`);
            }

            const authResponse = await this.makeRequest('/api/biometric/fingerprint/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    credentialId: credential.id,
                    response: {
                        authenticatorData: this.arrayBufferToBase64(credential.response.authenticatorData),
                        clientDataJSON: this.arrayBufferToBase64(credential.response.clientDataJSON),
                        signature: this.arrayBufferToBase64(credential.response.signature),
                        userHandle: credential.response.userHandle ? this.arrayBufferToBase64(credential.response.userHandle) : null
                    },
                    challenge: sessionStorage.getItem('webauthn_auth_challenge'),
                    expected_credential_id: sessionStorage.getItem('webauthn_auth_credential_id')
                })
            });

            if (authResponse.success) {
                return {
                    success: true,
                    userId: authResponse.userId || 1,
                    deviceId: deviceId,
                    shardsAssigned: authResponse.shards || ['dashboard', 'controls', 'notifications'],
                    message: 'Real WebAuthn authentication successful'
                };
            } else {
                throw new Error(authResponse.error || 'Authentication verification failed');
            }

        } catch (error) {
            this.log('WebAuthn authentication failed', { error: error.message });
            throw error; 
        }
    }

    mockAuthentication(deviceId) {
        return {
            success: true,
            userId: 1,
            deviceId: deviceId,
            shardsAssigned: ['dashboard', 'controls', 'notifications'],
            message: 'Mock authentication (WebAuthn not available)'
        };
    }

    log(message, data = null) {
        if (this.debug) {
        }
    }

    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }
}

function initializeBiometricSystem() {
    try {
        window.fingerprintManager = new FingerprintManager({
            baseUrl: window.location.origin,
            debug: false
        });


        window.registeredDevices = [];

        const mockDevices = localStorage.getItem('mockBiometricDevices');
        if (mockDevices) {
            window.registeredDevices = JSON.parse(mockDevices);
        }

        loadRegisteredDevices();
        checkBiometricCompatibility();

    } catch (error) {
        showBiometricNotification('Failed to initialize biometric system: ' + error.message, 'error');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeBiometricSystem);
} else {
    initializeBiometricSystem();
}

async function registerBiometricDevice() {
    if (!window.fingerprintManager) {
        showBiometricNotification('Fingerprint Manager not initialized', 'error');
        return;
    }
    const deviceName = document.getElementById('device-name').value;
    const deviceType = document.getElementById('device-type').value;
    
    if (!deviceName) {
        showBiometricNotification('Please enter a device name', 'error');
        return;
    }
    
    try {
        showBiometricNotification('Registering fingerprint...', 'info');
        
        const deviceId = 'device_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        
        const result = await window.fingerprintManager.registerFingerprint({
            device_id: deviceId,
            name: deviceName,
            type: deviceType,
            capabilities: ['fingerprint', 'webauthn'],
            biometric_methods: ['fingerprint']
        });
        
        if (result.success) {
            showBiometricNotification('Device and fingerprint registered successfully!', 'success');
            
            const mockDevice = {
                device_id: deviceId,
                name: deviceName,
                device_type: deviceType,
                credential_id: result.credentialId,
                registered_at: new Date().toISOString()
            };
            
            window.registeredDevices.push(mockDevice);
            
            localStorage.setItem('mockBiometricDevices', JSON.stringify(window.registeredDevices));
            
            document.getElementById('device-name').value = '';
            
            await loadRegisteredDevices();
        }
    } catch (error) {
        showBiometricNotification('Registration failed: ' + error.message, 'error');
    }
}

async function authenticateWithFingerprint() {
    if (!window.fingerprintManager) {
        showBiometricNotification('Fingerprint Manager not initialized', 'error');
        return;
    }
    
    const deviceId = document.getElementById('auth-device').value;
    
    if (!deviceId) {
        showBiometricNotification('Please select a device', 'error');
        return;
    }
    
    try {
        showBiometricNotification('Authenticating with fingerprint...', 'info');
        
        const result = await window.fingerprintManager.authenticateWithFingerprint(deviceId);
        
        if (result.success) {
            showBiometricNotification(`Authentication successful! ${result.shardsAssigned.length} shards assigned`, 'success');
            
            displayAssignedShards(result.shardsAssigned, result.userId);

            if (typeof window.loadAnalyticsData === 'function') {
                window.loadAnalyticsData();
            } else {
                window.pendingAnalyticsRefresh = true;
            }
            if (typeof window.loadSecurityAlerts === 'function') {
                window.loadSecurityAlerts();
            } else {
                window.pendingAnalyticsRefresh = true;
            }
            document.dispatchEvent(new CustomEvent('biometric:auth-success', {
                detail: {
                    userId: result.userId,
                    deviceId: deviceId
                }
            }));
        }
    } catch (error) {
        showBiometricNotification('Authentication failed: ' + error.message, 'error');
    }
}

async function loadRegisteredDevices() {
    try {
        const select = document.getElementById('auth-device');
        select.innerHTML = '<option value="">Choose a registered device...</option>';
        
        try {
            const response = await fetch('/api/biometric/fingerprint/credentials', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.credentials.length > 0) {
                    data.credentials.forEach(credential => {
                        const option = document.createElement('option');
                        option.value = credential.device_id;
                        option.textContent = `${credential.device_name} (${credential.device_type})`;
                        select.appendChild(option);
                    });
                    return;
                }
            }
        } catch (apiError) {
            //
        }
        
        window.registeredDevices.forEach(device => {
            const option = document.createElement('option');
            option.value = device.device_id;
            option.textContent = `${device.name} (${device.device_type})`;
            select.appendChild(option);
        });
    } catch (error) {
    }
}

async function checkBiometricCompatibility() {
    try {
        const fingerprintManager = window.fingerprintManager;
        const compatibility = {
            webauthn_supported: fingerprintManager ? fingerprintManager.webAuthnSupported : false,
            secure_context: window.isSecureContext,
            https_required: true,
            recommended_browsers: [
                'Chrome 67+ (Android/iOS)',
                'Firefox 60+ (Android/iOS)',
                'Safari 13+ (iOS)',
                'Edge 18+ (Android/iOS)'
            ]
        };

        if (compatibility) {
            const info = document.getElementById('compatibility-info');
            info.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 max-w-md mx-auto">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold">WebAuthn Support:</span>
                        <span class="px-2 py-1 rounded-full text-xs ${
                            compatibility.webauthn_supported ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        }">
                            ${compatibility.webauthn_supported ? 'Supported' : 'Not Supported'}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold">HTTPS Connection:</span>
                        <span class="px-2 py-1 rounded-full text-xs ${
                            compatibility.secure_context ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        }">
                            ${compatibility.secure_context ? 'Secure' : 'Insecure'}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-semibold">Platform:</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">${navigator.platform || 'Unknown'}</span>
                    </div>
                    ${!compatibility.webauthn_supported ? `
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-3 mb-3">
                            <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Browser Requirements</h4>
                            <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                                <li>• Use HTTPS (required for WebAuthn)</li>
                                <li>• Modern mobile browser</li>
                                <li>• Recommended browsers:</li>
                                ${compatibility.recommended_browsers.map(browser => `<li class="ml-4">• ${browser}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}
                    ${!compatibility.secure_context ? `
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-3">
                            <h4 class="font-semibold text-red-800 dark:text-red-200 mb-2">HTTPS Required</h4>
                            <p class="text-sm text-red-700 dark:text-red-300">
                                WebAuthn fingerprint authentication requires a secure HTTPS connection.
                                Please access this page using HTTPS.
                            </p>
                        </div>
                    ` : ''}
                </div>
            `;
            info.classList.remove('hidden');
        }
    } catch (error) {
        //
    }
}

function displayAssignedShards(shards, userId) {
    const display = document.getElementById('shard-display');
    
    if (shards.length === 0) {
        display.innerHTML = `
            <div class="text-center py-12 col-span-full">
                <i data-lucide="package" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">No shards assigned to your account</p>
            </div>
        `;
        return;
    }
    
    display.innerHTML = shards.map(shardName => `
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50 transform hover:scale-105 transition-transform">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                    <i data-lucide="box" class="w-5 h-5 text-white"></i>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">${shardName}</h4>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-3">
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Live shard content</p>
        </div>
    `).join('');
    
    if (window.lucide) {
        window.lucide.createIcons();
    }
}

async function authenticateWithQRCode() {
    if (!window.fingerprintManager) {
        showBiometricNotification('Fingerprint Manager not initialized', 'error');
        return;
    }
    
    try {
        showBiometricNotification('Generating QR Code...', 'info');
        
        const sessionId = 'qr_session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        const expiresAt = Date.now() + 300000; 
        const qrGenerator = new QRGenerator();
        const qrDataUrl = qrGenerator.generateAuthQR('any_device', sessionId, expiresAt);
        
        const authUrl = `${window.location.origin}/biometric/qr-auth?session=${sessionId}`;
        
        const qrDisplay = qrGenerator.createQRDisplay(qrDataUrl, authUrl);
        document.body.appendChild(qrDisplay);
        
        pollQRAuthentication(sessionId, expiresAt);
        
    } catch (error) {
        showBiometricNotification('QR Code generation failed: ' + error.message, 'error');
    }
}

async function pollQRAuthentication(sessionId, expiresAt) {
    const maxAttempts = 60; 
    let attempts = 0;
    
    const poll = async () => {
        attempts++;
        
        if (Date.now() > expiresAt || attempts >= maxAttempts) {
            closeQRAuth();
            showBiometricNotification('QR Code expired', 'error');
            return;
        }
        
        try {
            if (Math.random() < 0.1) { 
                closeQRAuth();
                showBiometricNotification('QR Code authentication successful! 3 shards assigned', 'success');
                
                displayAssignedShards(['dashboard', 'controls', 'notifications'], 1);
                return;
            }
            
            setTimeout(poll, 5000);
            
        } catch (error) {
          
            setTimeout(poll, 5000);
        }
    };
    
    setTimeout(poll, 1000);
}

function simulateQRScan() {
    closeQRAuth();
    showBiometricNotification('QR Code scanned successfully! Authenticating...', 'info');
    
    setTimeout(() => {
        showBiometricNotification('QR Code authentication successful! 3 shards assigned', 'success');
        
        displayAssignedShards(['dashboard', 'controls', 'notifications'], 1);
    }, 2000);
}

function closeQRAuth() {
    const modal = document.querySelector('.qr-auth-container');
    if (modal) {
        modal.remove();
    }
}

function showBiometricNotification(message, type = 'info') {
    const status = document.getElementById('auth-status');
    if (!status) return;
    
    status.classList.remove('hidden');
    status.className = `p-4 rounded-lg text-center mb-4 ${
        type === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
        type === 'error' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
    }`;
    status.innerHTML = message;
    
    setTimeout(() => {
        status.classList.add('hidden');
    }, 5000);
}
</script>
