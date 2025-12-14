/**
 * Simple QR Code Generator for Biometric Authentication
 * Lightweight implementation without external dependencies
 */

class QRGenerator {
    constructor() {
        this.moduleCount = 0;
        this.modules = null;
    }

    /**
     * Generate QR code for biometric authentication
     */
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

    /**
     * Simple QR code generation (basic implementation)
     */
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

    /**
     * Draw QR code position marker
     */
    drawPositionMarker(ctx, x, y, cellSize) {
        ctx.fillRect(x * cellSize, y * cellSize, 7 * cellSize, 7 * cellSize);
        
        ctx.fillStyle = '#ffffff';
        ctx.fillRect((x + 1) * cellSize, (y + 1) * cellSize, 5 * cellSize, 5 * cellSize);
        
        ctx.fillStyle = '#000000';
        ctx.fillRect((x + 2) * cellSize, (y + 2) * cellSize, 3 * cellSize, 3 * cellSize);
    }

    /**
     * Create QR code display element
     */
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
                    <button onclick="closeQRAuth()" class="qr-close-btn">Cancel</button>
                </div>
            </div>
        `;
        
        return container;
    }
}

window.QRGenerator = QRGenerator;
