# Shard UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shard/ui.svg)](https://packagist.org/packages/shard/ui)
[![Total Downloads](https://img.shields.io/packagist/dt/shard/ui.svg)](https://packagist.org/packages/shard/ui)
[![PHP Version Require](https://img.shields.io/packagist/php-v/shard/ui.svg)](https://packagist.org/packages/shard/ui)
[![Laravel Version Require](https://img.shields.io/packagist/dependency-v/shard/ui/laravel/framework.svg)](https://packagist.org/packages/shard/ui)

**Distributed Viewports for Laravel** - Enable seamless cross-device interactions with real-time shard content streaming.

Shard UI allows you to create interactive experiences that span multiple devices. Push HTML content from your server to remote clients instantly via WebSocket, perfect for collaborative dashboards, remote controls, multi-screen presentations, and distributed UIs.

## ✨ Features

- 🚀 **Real-time Content Streaming** - Push HTML shards to remote devices instantly
- 🔐 **Secure Authentication** - User-based session ownership with Laravel Sanctum
- 📱 **Cross-Device Compatibility** - Works on any device with a modern browser
- 🎨 **Framework Agnostic** - Pure HTML/CSS/JS output, style with Tailwind or any framework
- 🔧 **Laravel Native** - Integrates seamlessly with Laravel's ecosystem
- 📡 **WebSocket Powered** - Built on Laravel Reverb for reliable real-time communication
- 📚 **Well Documented** - Complete API reference and usage examples

## 📖 Interactive Documentation

Shard UI comes with a built-in, interactive documentation system accessible at `/docs` after installation.

### Features:
- 🎨 **Modern SaaS Design** - Beautiful, responsive interface with animations
- 📋 **Copy-Paste Ready** - One-click code copying for all examples
- 🔗 **Interactive API Testing** - Try API endpoints directly from the browser
- 📱 **Mobile Friendly** - Fully responsive design for all devices
- 🎯 **Related Content** - Smart linking between related sections

## 🔐 Biometric Authentication (WebAuthn)

Shard UI includes built-in WebAuthn fingerprint authentication for enhanced security.

### Biometric Installation

After installing the main package, set up biometric authentication:

```bash
# Option 1: Use the installation command (recommended)
php artisan shard-ui:install-biometric

# Option 2: Manual setup
php artisan vendor:publish --tag=shard-ui-migrations
php artisan migrate
```

### Biometric Security Features

- 🛡️ **Rate Limiting** - Prevents brute force attacks (configurable attempts per minute)
- 🚫 **Device Lockout** - Temporary locks after failed attempts (configurable duration)
- 🔍 **Suspicious Activity Detection** - Identifies unusual patterns and timing anomalies
- 📊 **Security Monitoring** - Real-time alerts and risk assessment
- 📋 **Comprehensive Auditing** - Complete authentication history with security context
- ⚙️ **Configurable Policies** - Environment-based security settings

### Biometric API Endpoints

```http
POST   /api/biometric/fingerprint/register    # Register device
POST   /api/biometric/fingerprint/authenticate # Get auth options  
POST   /api/biometric/fingerprint/verify       # Verify authentication
GET    /api/biometric/fingerprint/credentials  # List devices
```

### Security Responses

**Rate Limited (429):**
```json
{
  "success": false,
  "error": "Too many authentication attempts. Please try again later."
}
```

**Device Locked (423):**
```json
{
  "success": false,
  "error": "Device is temporarily locked due to too many failed attempts.",
  "lockout_expires": "2025-12-14T15:30:00.000000Z"
}
```

**Successful Authentication:**
```json
{
  "success": true,
  "userId": 1,
  "deviceId": "device_1234567890_abc123",
  "shards": ["dashboard", "controls", "notifications"],
  "security_status": {
    "success_rate": 0.95,
    "recent_failures": 0,
    "security_alerts": 0,
    "risk_level": "low"
  },
  "message": "Authentication verified successfully."
}
```

### Database Tables

The biometric package creates:
- `fingerprint_credentials` - WebAuthn credentials
- `biometric_devices` - Device information  
- `biometric_logs` - Authentication audit logs

## 📦 Installation

### Requirements
- PHP ^8.2
- Laravel ^11.0
- Laravel Reverb (for WebSocket communication)
- Laravel Sanctum (for API authentication)

### Install Package
```bash
composer require shard/ui
php artisan vendor:publish --provider="Shard\Ui\ShardServiceProvider"
```

### Publish Assets (Optional)
```bash
# Configuration
php artisan vendor:publish --tag=shard-ui-config

# Views (for customization)
php artisan vendor:publish --tag=shard-ui-views

# API Routes (for customization)
php artisan vendor:publish --tag=shard-ui-routes

# JavaScript assets (for customization)
php artisan vendor:publish --tag=shard-ui-assets
```

### Environment Configuration
Add to your `.env` file:
```env
# Required for WebSocket communication
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Client-side WebSocket configuration
VITE_REVERB_APP_KEY=your-app-key
VITE_REVERB_ENCRYPTED=false

# Shard UI Configuration
SHARD_UI_REQUIRE_AUTH=false  # Set to true for authenticated sessions
SHARD_UI_ALLOW_ANONYMOUS=true
SHARD_UI_ENABLE_DEMO=true
```

### Database Migration
If using authentication, ensure your users table has the Sanctum columns:
```bash
php artisan migrate
```

## 🚀 Quick Start

### 1. Basic Blade Component Usage

Create a shard in your Blade template:
```blade
{{-- resources/views/dashboard.blade.php --}}
<x-shard target="mobile" name="controls">
    <div class="p-4 bg-white rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Remote Controls</h2>
        <button @click="status = 'activated'"
                class="px-4 py-2 bg-blue-500 text-white rounded">
            Activate System
        </button>
        <p x-text="status" class="mt-2"></p>
    </div>
</x-shard>
```

### 2. Controller Integration

Use the `InteractsWithShard` trait in your controllers:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Shard\Ui\Support\Concerns\InteractsWithShard;

class DashboardController extends Controller
{
    use InteractsWithShard;

    public function show(Request $request)
    {
        // Start a session (associates with authenticated user if available)
        $sessionId = $this->startShardSession();

        // Push content to the session
        $this->pushShardContent($sessionId, 'dashboard', view('dashboard-content')->render());

        return view('dashboard', compact('sessionId'));
    }
}
```

### 3. API Integration

Create and manage sessions programmatically:
```php
// Create a new session
$response = Http::withToken($token)->post('/api/shard/sessions');
$sessionId = $response['data']['id'];

// Push content to the session
Http::withToken($token)->post("/api/shard/sessions/{$sessionId}/shards", [
    'shard_name' => 'notifications',
    'html' => '<div class="alert">System updated!</div>'
]);

// Get session details
$session = Http::withToken($token)->get("/api/shard/sessions/{$sessionId}");

// List user's sessions
$sessions = Http::withToken($token)->get('/api/shard/sessions');
```

## 📡 API Reference

### Authentication
All API endpoints require Laravel Sanctum authentication:
```bash
# Get token
curl -X POST /api/sanctum/token \
  -d "email=user@example.com&password=password&device_name=device"

# Use token for requests
curl -H "Authorization: Bearer {token}" /api/shard/sessions
```

### Endpoints

#### Sessions
```http
POST   /api/shard/sessions
GET    /api/shard/sessions
GET    /api/shard/sessions/{sessionId}
DELETE /api/shard/sessions/{sessionId}
```

#### Shards
```http
POST   /api/shard/sessions/{sessionId}/shards
GET    /api/shard/sessions/{sessionId}/shards/{shardName}
```

### Response Formats

**Session Response:**
```json
{
  "data": {
    "id": "session-uuid",
    "user_id": 1,
    "shards_count": 3,
    "created_at": "2025-12-13T18:00:00Z"
  }
}
```

**Shard Response:**
```json
{
  "data": {
    "session_id": "session-uuid",
    "name": "dashboard",
    "html": "<div>Content</div>"
  }
}
```

## 🔐 Security & Authentication

### Anonymous Mode (Default)
- Sessions can be created without authentication
- Useful for public demos and guest interactions
- Set `SHARD_UI_ALLOW_ANONYMOUS=true`

### Authenticated Mode
- Set `SHARD_UI_REQUIRE_AUTH=true` to enforce authentication
- Sessions are associated with user accounts
- Private WebSocket channels ensure data isolation
- API endpoints require Sanctum tokens

### Channel Authorization
```php
// Private channels (authenticated mode)
Broadcast::channel('private-shard.{sessionId}', function ($user, $sessionId) {
    $session = app(ShardManager::class)->getSession($sessionId);
    return $session && ($session['user_id'] === null || $session['user_id'] === $user->id);
});

// Public channels (anonymous mode)
Broadcast::channel('shard.{sessionId}', function ($user, $sessionId) {
    return true; // Open to all
});
```

## 🎨 Frontend Integration

### Alpine.js Client
The package includes a pre-configured Alpine.js client:

```javascript
// In your app.js
import './vendor/shard-ui/shard-client';

// Initialize client
const client = shardClient({
    sessionId: 'session-uuid',
    channel: 'shard.session-uuid',
    initialPayload: '<div>Loading...</div>' // Optional
});
```

### Manual Echo Integration
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: window.location.hostname,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

echo.channel('shard.session-uuid')
    .listen('.ShardHtmlPushed', (event) => {
        if (event.session_id === 'your-session-id') {
            document.getElementById('shard-container').innerHTML = event.html;
        }
    });
```

## ⚙️ Configuration

### Core Settings (`config/shard-ui.php`)
```php
return [
    'channel_prefix' => 'shard', // Auto-switches to 'private-shard' when auth enabled
    'session_ttl' => 3600,       // Session lifetime in seconds
    'connect_route_name' => 'shard.session.connect',
    'qr_route_name' => 'shard.session.qr',

    'demo' => [
        'enabled' => env('SHARD_UI_ENABLE_DEMO', false),
        'allow_anonymous' => env('SHARD_UI_ALLOW_ANONYMOUS', true),
    ],

    'auth' => [
        'required' => env('SHARD_UI_REQUIRE_AUTH', false),
    ],

    'rate_limits' => [
        'session' => ['max_attempts' => 20, 'decay_minutes' => 1],
        'connect' => ['max_attempts' => 60, 'decay_minutes' => 1],
    ],

    'telemetry' => [
        'log_sessions' => true,
        'log_payloads' => false,
    ],

    // Biometric Security Configuration
    'biometric' => [
        'security' => [
            'rate_limit_attempts' => env('BIOMETRIC_RATE_LIMIT_ATTEMPTS', 10),
            'rate_limit_decay' => env('BIOMETRIC_RATE_LIMIT_DECAY', 1),
            'max_attempts' => env('BIOMETRIC_MAX_ATTEMPTS', 5),
            'lockout_minutes' => env('BIOMETRIC_LOCKOUT_MINUTES', 15),
            'enable_security_alerts' => env('BIOMETRIC_SECURITY_ALERTS', true),
        ],
        'fingerprint' => [
            'timeout' => env('WEBAUTHN_TIMEOUT', 60000),
            'user_verification' => env('WEBAUTHN_USER_VERIFICATION', 'required'),
        ],
        'devices' => [
            'max_devices_per_user' => env('BIOMETRIC_MAX_DEVICES_PER_USER', 5),
        ],
    ],
];
```

## 🧪 Testing

Run the comprehensive test suite:
```bash
# Feature tests
./vendor/bin/pest tests/Feature/

# Unit tests
./vendor/bin/pest tests/Unit/

# With coverage
./vendor/bin/pest --coverage
```

Test key functionality:
```php
use Shard\Ui\Services\ShardManager;
use Shard\Ui\Http\Controllers\ShardApiController;

// Test session management
$manager = app(ShardManager::class);
$sessionId = $manager->startSession('test-session', 1);
$manager->attachShardContent($sessionId, 'test', '<div>Test</div>');

// Test API endpoints
$controller = app(ShardApiController::class);
$response = $controller->getSession($sessionId);
$this->assertEquals(200, $response->getStatusCode());
```

## 🎯 Use Cases

### Remote Dashboard Controls
```php
// Controller
public function dashboard()
{
    $sessionId = $this->startShardSession();
    $this->pushShardContent($sessionId, 'controls', view('remote-controls')->render());

    return view('dashboard', compact('sessionId'));
}
```

### Collaborative Whiteboard
```php
// Push drawing updates in real-time
$this->pushShardContent($sessionId, 'canvas', $drawingHtml);
```

### Multi-Screen Presentations
```php
// Different content for different devices
$this->pushShardContent($sessionId, 'presenter-notes', $notesHtml);
$this->pushShardContent($sessionId, 'audience-view', $slidesHtml);
```

### IoT Device Management
```php
// Control interface for connected devices
$this->pushShardContent($sessionId, 'device-panel', $deviceControlsHtml);
```

## 🛠️ Troubleshooting

### WebSocket Connection Issues
```bash
# Check Reverb server
php artisan reverb:start --debug

# Verify environment variables
php artisan tinker
>>> config('broadcasting.connections.reverb')
```

### Content Not Appearing
1. Verify session exists: `GET /api/shard/sessions/{sessionId}`
2. Check WebSocket connection in browser dev tools
3. Ensure proper channel authorization
4. Verify Vite assets are compiled: `npm run build`

### Authentication Problems
1. Confirm Sanctum is installed and configured
2. Check token generation: `auth()->user()->createToken('name')`
3. Verify `SHARD_UI_REQUIRE_AUTH` setting

## 📈 Performance

- **WebSocket Compression** - Automatic payload compression
- **Session Caching** - Redis/database caching for high performance
- **Rate Limiting** - Built-in throttling to prevent abuse
- **Connection Pooling** - Efficient WebSocket connection management

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for new functionality
4. Ensure all tests pass
5. Submit a pull request

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

- 📖 [Documentation](https://shard-ui.dev)
- 🐛 [Issue Tracker](https://github.com/shard/ui/issues)
- 💬 [Discord Community](https://discord.gg/shard-ui)

---

**Built with Abrar Ali (Senior Software Developer ) for the Laravel community**
