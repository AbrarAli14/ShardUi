<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shard UI Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 text-slate-100">
    <div class="min-h-screen px-6 py-10">
        <div class="max-w-5xl mx-auto space-y-10">
            <header class="space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-emerald-300">Shard UI Demo</p>
                <h1 class="text-4xl font-semibold text-white">Dual-Screen Mission Console</h1>
                <p class="text-slate-400">Scan the QR code to turn your phone into a command deck. Interactions stream over Reverb to the remote shard.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <section class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Session {{ $sessionId }}
                    </h2>
                    <div class="flex flex-col items-center gap-4">
                        <div class="bg-white rounded-xl p-4" aria-label="QR code to connect shard session">
                            {!! $qrSvg !!}
                        </div>
                        <p class="text-sm text-slate-400">or open<br><span class="text-emerald-300 break-all">{{ $signedUrl }}</span></p>
                    </div>
                    <div class="space-y-2 text-sm text-slate-400">
                        <p>How it works:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Open this page on your desktop.</li>
                            <li>Scan the QR with your phone to join as remote.</li>
                            <li>The controls shard streams instantly to your phone.</li>
                        </ol>
                    </div>
                </section>

                <section class="bg-gradient-to-br from-slate-900/70 to-slate-900/30 border border-slate-800/80 rounded-2xl p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white">Host Panel</h2>
                        <span class="text-xs text-slate-400">Shard: {{ $demoShardName }}</span>
                    </div>
                    <div class="p-4 bg-slate-950/60 rounded-xl border border-slate-800/80">
                        <x-shard target="mobile" name="{{ $demoShardName }}">
                            @include('shard-ui::demo.partials.remote-controls')
                        </x-shard>
                    </div>
                    <p class="text-sm text-slate-500">The shard above is suppressed on the host view. Once the remote joins, the HTML is rendered on their device automatically.</p>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
