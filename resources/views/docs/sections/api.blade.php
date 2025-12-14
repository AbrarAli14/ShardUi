<div class="animate-fade-in space-y-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $title }}</h1>

    <div class="prose prose-lg max-w-none">
        <p class="text-gray-600 mb-8 leading-relaxed">
            Complete REST API reference for programmatic shard session management with authentication and real-time capabilities.
        </p>

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="key" class="h-5 w-5 text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Authentication:</strong> All endpoints require Laravel Sanctum tokens via <code class="bg-blue-100 px-1 rounded text-xs">Authorization: Bearer {token}</code> header.
                    </p>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Endpoints Overview</h2>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8 mb-8">
            <!-- Sessions -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="server" class="w-5 h-5 mr-2 text-blue-500"></i>
                    Session Management
                </h3>
                <div class="space-y-4">
                    <div class="flex flex-wrap items-start gap-2">
                        <span class="bg-green-100 text-green-800 text-[10px] sm:text-xs px-2 py-1 rounded mt-1">POST</span>
                        <code class="flex-1 min-w-[160px] block text-[11px] sm:text-sm text-gray-900 break-words leading-relaxed">
                            /api/shard/sessions
                        </code>
                        <button @click="copyToClipboard('curl -X POST /api/shard/sessions -H \"Authorization: Bearer {token}\"')" class="copy-btn text-gray-400 hover:text-gray-600 text-sm flex-shrink-0">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 pl-1">Create new shard session</p>

                    <div class="flex flex-wrap items-start gap-2">
                        <span class="bg-blue-100 text-blue-800 text-[10px] sm:text-xs px-2 py-1 rounded mt-1">GET</span>
                        <code class="flex-1 min-w-[160px] block text-[11px] sm:text-sm text-gray-900 break-words leading-relaxed">
                            /api/shard/sessions
                        </code>
                        <button @click="copyToClipboard('curl -X GET /api/shard/sessions -H \"Authorization: Bearer {token}\"')" class="copy-btn text-gray-400 hover:text-gray-600 text-sm flex-shrink-0">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 pl-1">List user sessions</p>
                </div>
            </div>

            <!-- Shards -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="layers" class="w-5 h-5 mr-2 text-green-500"></i>
                    Shard Content
                </h3>
                <div class="space-y-4">
                    <div class="flex flex-wrap items-start gap-2">
                        <span class="bg-green-100 text-green-800 text-[10px] sm:text-xs px-2 py-1 rounded mt-1">POST</span>
                        <code class="flex-1 min-w-[160px] block text-[11px] sm:text-sm text-gray-900 break-words leading-relaxed">
                            /sessions/{id}/shards
                        </code>
                        <button @click="copyToClipboard(`curl -X POST /api/shard/sessions/{sessionId}/shards -H \"Authorization: Bearer {token}\" -H \"Content-Type: application/json\" -d '{\"shard_name\": \"dashboard\", \"html\": \"<div>Content</div>\"}'`)" class="copy-btn text-gray-400 hover:text-gray-600 text-sm flex-shrink-0">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 pl-1">Push HTML content</p>

                    <div class="flex flex-wrap items-start gap-2">
                        <span class="bg-blue-100 text-blue-800 text-[10px] sm:text-xs px-2 py-1 rounded mt-1">GET</span>
                        <code class="flex-1 min-w-[160px] block text-[11px] sm:text-sm text-gray-900 break-words leading-relaxed">
                            /sessions/{id}/shards/{name}
                        </code>
                        <button @click="copyToClipboard('curl -X GET /api/shard/sessions/{sessionId}/shards/{shardName} -H \"Authorization: Bearer {token}\"')" class="copy-btn text-gray-400 hover:text-gray-600 text-sm flex-shrink-0">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 pl-1">Retrieve shard content</p>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Authentication</h2>

        <h3 class="text-lg font-semibold text-gray-900 mb-4">Getting an API Token</h3>
        <div class="relative code-block bg-gray-900 rounded-2xl p-4 mb-6 w-full overflow-hidden">
            <pre class="text-yellow-400 text-sm overflow-x-auto break-words"><code># Get authentication token
curl -X POST http://localhost:8000/api/sanctum/token \
  -d "email=user@example.com&password=password&device_name=device"

# Response contains: {"token": "your-token-here"}</code></pre>
            <button @click="copyToClipboard(`# Get authentication token
curl -X POST http://localhost:8000/api/sanctum/token \\
  -d "email=user@example.com&password=password&device_name=device"

# Response contains: {"token": "your-token-here"}`)" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 mb-4">Using the Token</h3>
        <div class="relative code-block bg-gray-900 rounded-2xl p-4 mb-6 w-full overflow-hidden">
            <pre class="text-green-400 text-sm overflow-x-auto break-words"><code># Include token in all API requests
curl -H "Authorization: Bearer your-token-here" \
     -H "Accept: application/json" \
     http://localhost:8000/api/shard/sessions</code></pre>
            <button @click="copyToClipboard(`# Include token in all API requests
curl -H "Authorization: Bearer your-token-here" \\
     -H "Accept: application/json" \\
     http://localhost:8000/api/shard/sessions`)" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Interactive Examples</h2>

        <div class="bg-gray-50 rounded-2xl p-6 mb-8 shadow-inner">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Try the API Live</h3>
            <p class="text-gray-600 mb-4">Click the buttons below to test different API endpoints (requires authentication):</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button @click="testEndpoint('create')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors" :disabled="loading">
                    <span x-text="loading && currentTest === 'create' ? 'Creating...' : 'Create Session'"></span>
                </button>
                <button @click="testEndpoint('list')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors" :disabled="loading">
                    <span x-text="loading && currentTest === 'list' ? 'Loading...' : 'List Sessions'"></span>
                </button>
                <button @click="testEndpoint('push')" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors" :disabled="!sessionId || loading">
                    <span x-text="loading && currentTest === 'push' ? 'Pushing...' : 'Push Shard'"></span>
                </button>
            </div>

            <div x-show="response" class="mt-4 p-4 bg-white border border-gray-200 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-2">Response:</h4>
                <pre class="text-sm text-gray-700 whitespace-pre-wrap" x-text="response"></pre>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Response Formats</h2>

        <h3 class="text-lg font-semibold text-gray-900 mb-4">Session Response</h3>
        <div class="relative code-block bg-gray-900 rounded-2xl p-4 mb-6 w-full overflow-hidden">
            <pre class="text-blue-400 text-sm overflow-x-auto break-words"><code>{
  "data": {
    "id": "session-uuid",
    "user_id": 1,
    "shards_count": 3,
    "created_at": "2025-12-13T18:00:00Z"
  }
}</code></pre>
            <button @click="copyToClipboard(`{
  "data": {
    "id": "session-uuid",
    "user_id": 1,
    "shards_count": 3,
    "created_at": "2025-12-13T18:00:00Z"
  }
}`)" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 mb-4">Shard Response</h3>
        <div class="relative code-block bg-gray-900 rounded-2xl p-4 mb-6 w-full overflow-hidden">
            <pre class="text-green-400 text-sm overflow-x-auto break-words"><code>{
  "data": {
    "session_id": "session-uuid",
    "name": "dashboard",
    "html": "<div>Content</div>"
  }
}</code></pre>
            <button @click="copyToClipboard(`{
  "data": {
    "session_id": "session-uuid",
    "name": "dashboard",
    "html": "<div>Content</div>"
  }
}`)" class="copy-btn absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="book-open" class="h-5 w-5 text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Related:</strong> Check out the <a href="/docs/examples" class="text-yellow-800 underline">Examples section</a> for practical use cases and implementation patterns.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sectionApp() {
    return {
        loading: false,
        currentTest: null,
        response: null,
        sessionId: null,

        init() {
            lucide.createIcons();
        },

        async testEndpoint(type) {
            this.loading = true;
            this.currentTest = type;
            this.response = 'Making request...';

            try {
                let url, method = 'GET', body = null;

                switch(type) {
                    case 'create':
                        url = '/api/shard/sessions';
                        method = 'POST';
                        break;
                    case 'list':
                        url = '/api/shard/sessions';
                        break;
                    case 'push':
                        url = `/api/shard/sessions/${this.sessionId}/shards`;
                        method = 'POST';
                        body = JSON.stringify({
                            shard_name: 'test-shard',
                            html: '<div class="p-4 bg-blue-100 rounded">Test Content</div>'
                        });
                        break;
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: body
                });

                const data = await response.json();

                if (type === 'create' && data.data?.id) {
                    this.sessionId = data.data.id;
                }

                this.response = JSON.stringify(data, null, 2);

            } catch (error) {
                this.response = `Error: ${error.message}`;
            } finally {
                this.loading = false;
                this.currentTest = null;
            }
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.showToast('Copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        },

        showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in';
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    }
}
</script>
