<div class="animate-fade-in">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $title }}</h1>

    <div class="prose prose-lg max-w-none">
        <p class="text-gray-600 mb-8">
            Complete step-by-step installation guide for Shard UI package.
        </p>

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="info" class="h-5 w-5 text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Requirements:</strong> PHP 8.2+, Laravel 11.0+, Laravel Reverb, Laravel Sanctum
                    </p>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Install Package</h2>
        <div class="code-block bg-gray-900 rounded-lg p-4 mb-6">
            <pre class="text-green-400 text-sm overflow-x-auto"><code>composer require shard/ui
php artisan vendor:publish --provider="Shard\Ui\ShardServiceProvider"</code></pre>
            <button @click="copyToClipboard(`composer require shard/ui
php artisan vendor:publish --provider=\"Shard\\Ui\\ShardServiceProvider\"`)" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Environment Configuration</h2>
        <p class="text-gray-600 mb-4">Add these variables to your <code class="bg-gray-100 px-2 py-1 rounded text-sm">.env</code> file:</p>

        <div class="code-block bg-gray-900 rounded-lg p-4 mb-6">
            <pre class="text-blue-400 text-sm overflow-x-auto"><code># WebSocket Configuration
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
SHARD_UI_REQUIRE_AUTH=false
SHARD_UI_ALLOW_ANONYMOUS=true
SHARD_UI_ENABLE_DEMO=true</code></pre>
            <button @click="copyToClipboard(`# WebSocket Configuration
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
SHARD_UI_REQUIRE_AUTH=false
SHARD_UI_ALLOW_ANONYMOUS=true
SHARD_UI_ENABLE_DEMO=true`)" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Database Migration</h2>
        <div class="code-block bg-gray-900 rounded-lg p-4 mb-6">
            <pre class="text-green-400 text-sm overflow-x-auto"><code>php artisan migrate</code></pre>
            <button @click="copyToClipboard('php artisan migrate')" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Start Services</h2>
        <div class="code-block bg-gray-900 rounded-lg p-4 mb-6">
            <pre class="text-green-400 text-sm overflow-x-auto"><code># Terminal 1: Laravel Server
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: WebSocket Server
php artisan reverb:start --host=0.0.0.0 --port=8080

# Terminal 3: Asset Compilation (if needed)
npm run dev</code></pre>
            <button @click="copyToClipboard(`# Terminal 1: Laravel Server
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: WebSocket Server
php artisan reverb:start --host=0.0.0.0 --port=8080

# Terminal 3: Asset Compilation (if needed)
npm run dev`)" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle" class="h-5 w-5 text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Success!</strong> Visit <code class="bg-green-100 px-2 py-1 rounded text-sm">http://localhost:8000/shard/demo</code> to see the demo in action.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Next Steps:</strong> Check out the <a href="/docs/quick-start" class="text-yellow-800 underline">Quick Start guide</a> to begin using Shard UI in your application.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
