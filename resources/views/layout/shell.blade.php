<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shard Remote</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 text-slate-100">
    <div class="min-h-full flex items-center justify-center">
        <main x-data="shardClient({ sessionId: '{{ $sessionId }}', channel: '{{ $channel }}', initialPayload: @js($initialPayload ?? null) })"
              x-init="init()"
              class="w-full max-w-md p-6 space-y-4">
            <template x-if="errors.length">
                <div class="rounded border border-red-500/40 bg-red-500/10 p-3 text-sm text-red-200" role="alert">
                    <p class="font-semibold mb-1">Connection issues</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <template x-for="error in errors" :key="error">
                            <li x-text="error"></li>
                        </template>
                    </ul>
                </div>
            </template>

            <div x-html="payload"></div>
        </main>
    </div>
</body>
</html>
