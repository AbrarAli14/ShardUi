<div align="center">
  <img src="https://raw.githubusercontent.com/AbrarAli14/ShardUi/main/art/hero.png" alt="Shard UI Banner" width="720">
  <h1>Shard UI</h1>
  <p><strong>Distributed Viewports for Laravel</strong></p>
  <p>
    <a href="https://packagist.org/packages/abrarali14/shard-ui"><img alt="Packagist" src="https://img.shields.io/packagist/v/abrarali14/shard-ui.svg?label=packagist&color=4c1"></a>
    <a href="https://packagist.org/packages/abrarali14/shard-ui"><img alt="Downloads" src="https://img.shields.io/packagist/dt/abrarali14/shard-ui.svg"></a>
    <a href="#"><img alt="PHP" src="https://img.shields.io/packagist/php-v/abrarali14/shard-ui.svg?color=blue"></a>
    <a href="#"><img alt="Laravel" src="https://img.shields.io/badge/Laravel-11+-ff2d20"></a>
    <a href="LICENSE"><img alt="License" src="https://img.shields.io/badge/license-MIT-0f172a"></a>
  </p>
  <p>Stream HTML “shards” to any device in real time. Build collaborative dashboards, remote controls, multi-screen presentations, and biometric-secured workflows without leaving Laravel.</p>
  <p>
    <a href="https://github.com/AbrarAli14/ShardUi/wiki">Docs</a> ·
    <a href="https://github.com/AbrarAli14/ShardUi/issues">Issues</a> ·
    <a href="https://github.com/AbrarAli14/ShardUi/releases">Releases</a>
  </p>
</div>

---

## 🧭 What Problem Does It Solve?

| Challenge | Shard UI Ships With |
| --- | --- |
| Multi-device orchestration | Session manager, shard channels, QR auth |
| Real-time UI streaming | Laravel Reverb events + JS client |
| Production-ready demo | `/docs` site with API explorer + biometrics |
| Hardening & security | WebAuthn fingerprints, rate limits, telemetry |

---

---

## ⚙️ Install in Three Steps

```bash
composer require abrarali14/shard-ui
php artisan vendor:publish --provider="Shard\Ui\ShardServiceProvider"

# Optional helpers
php artisan vendor:publish --tag=shard-ui-controller   # publishes stub BiometricController
php artisan vendor:publish --tag=shard-ui-config
php artisan vendor:publish --tag=shard-ui-views
php artisan vendor:publish --tag=shard-ui-routes
php artisan vendor:publish --tag=shard-ui-assets
```

### Configure `.env`

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY=${REVERB_APP_KEY}
VITE_REVERB_ENCRYPTED=false

SHARD_UI_REQUIRE_AUTH=false
SHARD_UI_ALLOW_ANONYMOUS=true
SHARD_UI_ENABLE_DEMO=true

# Rate limits
SHARD_UI_SESSION_MAX_ATTEMPTS=20
SHARD_UI_SESSION_DECAY_MINUTES=1
SHARD_UI_CONNECT_MAX_ATTEMPTS=60
SHARD_UI_CONNECT_DECAY_MINUTES=1

# Biometric security
BIOMETRIC_RATE_LIMIT_ATTEMPTS=10
BIOMETRIC_RATE_LIMIT_DECAY=1
BIOMETRIC_MAX_ATTEMPTS=5
BIOMETRIC_LOCKOUT_MINUTES=30
BIOMETRIC_SECURITY_ALERTS=true
BIOMETRIC_MAX_DEVICES_PER_USER=5
```

---

## 🚀 Quick Start (Blade + API)

```blade
{{-- resources/views/dashboard.blade.php --}}
<x-shard target="mobile" name="controls">
    <div class="p-4 bg-white rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-4">Remote Controls</h2>
        <button wire:click="toggle" class="px-4 py-2 bg-indigo-600 text-white rounded">
            Activate System
        </button>
    </div>
</x-shard>
```

```php
use Shard\Ui\Support\Concerns\InteractsWithShard;

final class DashboardController extends Controller
{
    use InteractsWithShard;

    public function __invoke()
    {
        $sessionId = $this->startShardSession();
        $this->pushShardContent($sessionId, 'controls', view('remote-controls')->render());

        return view('dashboard', compact('sessionId'));
    }
}
```

```php
// API usage
$token = auth()->user()->createToken('dashboard')->plainTextToken;
$session = Http::withToken($token)->post('/api/shard/sessions')['data']['id']

Http::withToken($token)->post("/api/shard/sessions/{$session}/shards", [
    'shard_name' => 'notifications',
    'html' => '<div class="alert">System updated!</div>',
]);
```

---

## 🔐 WebAuthn Fingerprint Suite

- `php artisan shard-ui:install-biometric` – scaffolds tables & configs.
- Rate limiting, lockouts, anomaly detection, IP/user-agent heuristics.
- Analytics dashboard with success rates, alerts, device breakdowns.
- Endpoints:

```http
POST /api/biometric/fingerprint/register
POST /api/biometric/fingerprint/authenticate
POST /api/biometric/fingerprint/verify
GET  /api/biometric/fingerprint/credentials
```

---

## 🧱 Architecture Snapshot

```
[Blade/Alpine UI] <-- WebSocket --> [Reverb] <-- Events --> [Shard Manager]
                                      ↓
                                [Sanctum / Policies]
                                      ↓
                              [Biometric Services]
```

Key pieces:

- **ShardManager** – session lifecycle, shard payload cache, telemetry hooks.
- **ShardApiController** – REST endpoints for sessions & shards.
- **Events** – `ShardSessionStarted`, `ShardHtmlPushed`, `ShardSessionEnded`.
- **Docs/Analytics** – Live metrics, security alerts, device charts.

---

## 📡 REST Primer

```http
POST   /api/shard/sessions                     # start session
GET    /api/shard/sessions                     # list sessions
POST   /api/shard/sessions/{id}/shards         # push HTML payload
GET    /api/shard/sessions/{id}/shards/{name}  # fetch shard
DELETE /api/shard/sessions/{id}                # close session
```

🔐 When `SHARD_UI_REQUIRE_AUTH=true`, attach a Sanctum token to every request.

---

## 🧪 Testing Checklist

```bash
./vendor/bin/pest tests/Feature
./vendor/bin/pest tests/Unit
./vendor/bin/pest --coverage
```

- Sessions create/destroy without leaking payloads.
- Biometric register → verify → audit log.
- Rate limits trigger lockouts gracefully.

---

## 🧰 Configuration Cheat Sheet

| File | Purpose |
| --- | --- |
| `config/shard-ui.php` | Channel names, TTL, telemetry, rate limits. |
| `config/shard-ui-biometric.php` | Fingerprint policies, alerting, device caps. |
| `.env` | Reverb/Sanctum toggles, anonymous vs authenticated mode. |

---

## 🗺️ Roadmap

- [ ] Native mobile companion app (React Native / Flutter).
- [ ] Dedicated analytics exporter (Prometheus-friendly).
- [ ] Filament/Nova widgets for shard control.
- [ ] Multi-tenant session isolation helpers.

Contributions welcome—open an issue to discuss ideas!

---

## 🤝 Contributing & Support

1. Fork → branch → code → tests → PR.
2. Follow PSR-12 + Laravel styling.
3. Document new features in `/docs` and `README`.

Need help?

- 📖 [Docs Wiki](https://github.com/AbrarAli14/ShardUi/wiki)
- 🐛 [Issues](https://github.com/AbrarAli14/ShardUi/issues)
- 📧 abrarali.dev@gmail.com

---

**Built by [Abrar Ali](https://github.com/AbrarAli14) for the Laravel community.**
