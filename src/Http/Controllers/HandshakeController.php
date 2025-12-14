<?php

declare(strict_types=1);

namespace Shard\Ui\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Shard\Ui\Services\ShardManager;

final class HandshakeController extends Controller
{
    public function __construct(private readonly ShardManager $shardManager) {}

    public function create(Request $request): JsonResponse
    {
        $sessionId = $request->input('session_id');
        $userId = auth()->id();

        if ($sessionId === null) {
            $sessionId = $this->shardManager->startSession(null, $userId);
        } else {
            $this->shardManager->startSession($sessionId, $userId);
        }

        $expiresAt = now()->addSeconds((int) config('shard-ui.session_ttl', 3600));

        $signedUrl = URL::temporarySignedRoute(
            config('shard-ui.connect_route_name', 'shard.session.connect'),
            $expiresAt,
            ['session' => $sessionId]
        );

        return response()->json([
            'session_id' => $sessionId,
            'connect_url' => $signedUrl,
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    public function connect(Request $request, string $session): View
    {
        $channel = sprintf('%s.%s', config('shard-ui.channel_prefix', 'private-shard'), $session);
        $payloads = $this->shardManager->allShardContent($session);
        $initialPayload = empty($payloads) ? null : array_values($payloads)[count($payloads) - 1];

        return view('shard-ui::layout.shell', [
            'sessionId' => $session,
            'channel' => $channel,
            'initialPayload' => $initialPayload,
        ]);
    }
}
