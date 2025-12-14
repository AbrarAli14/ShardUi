<?php

declare(strict_types=1);

namespace Shard\Ui\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Shard\Ui\Http\Resources\ShardResource;
use Shard\Ui\Http\Resources\ShardSessionResource;
use Shard\Ui\Services\ShardManager;
use SimpleSoftwareIO\QrCode\Generator;

final class ShardApiController extends Controller
{
    public function __construct(
        private readonly ShardManager $shardManager,
    ) {}

    private function qrGenerator()
    {
        return app(\SimpleSoftwareIO\QrCode\Generator::class);
    }

    /**
     * Create a new shard session
     */
    public function createSession(Request $request): JsonResponse
    {
        $userId = auth()->id();

        $sessionId = $this->shardManager->startSession(null, $userId);
        $connectRoute = config('shard-ui.connect_route_name', 'shard.session.connect');
        $expiresAt = now()->addSeconds((int) config('shard-ui.session_ttl', 3600));

        $signedUrl = URL::temporarySignedRoute($connectRoute, $expiresAt, ['session' => $sessionId]);
        $qrSvg = $this->qrGenerator()->format('svg')->size(220)->generate($signedUrl);

        return response()->json([
            'data' => [
                'id' => $sessionId,
                'connect_url' => $signedUrl,
                'qr_code' => $qrSvg,
                'expires_at' => $expiresAt->toIso8601String(),
                'user_id' => $userId,
            ]
        ]);
    }

    /**
     * Get session information
     */
    public function getSession(string $sessionId): JsonResponse
    {
        try {
            $session = $this->shardManager->getSession($sessionId);

            if (!$session) {
                return response()->json(['error' => 'Session not found'], 404);
            }

            // Safe access to session data
            $userId = $session['user_id'] ?? null;
            $payloads = $session['payloads'] ?? [];

            return response()->json([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'shards_count' => is_array($payloads) ? count($payloads) : 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Push shard content to session
     */
    public function pushShard(Request $request, string $sessionId): JsonResponse
    {
        $request->validate([
            'shard_name' => 'required|string|max:255',
            'html' => 'required|string',
        ]);

        $session = $this->shardManager->getSession($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Check ownership
        if (auth()->check() && $session['user_id'] !== null && $session['user_id'] !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->shardManager->attachShardContent(
            $sessionId,
            $request->input('shard_name'),
            $request->input('html')
        );

        return response()->json([
            'message' => 'Shard content pushed successfully',
            'session_id' => $sessionId,
            'shard_name' => $request->input('shard_name'),
        ]);
    }

    /**
     * Get shard content
     */
    public function getShard(string $sessionId, string $shardName): JsonResponse
    {
        $session = $this->shardManager->getSession($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Check ownership
        if (auth()->check() && $session['user_id'] !== null && $session['user_id'] !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $html = $this->shardManager->getShardContent($sessionId, $shardName);

        if ($html === null) {
            return response()->json(['error' => 'Shard not found'], 404);
        }

        // Temporary debugging
        // \Log::info('getShard debug', [
        //     'sessionId' => $sessionId,
        //     'shardName' => $shardName,
        //     'session_exists' => $session !== null,
        //     'payloads' => $session['payloads'] ?? null,
        //     'html_length' => strlen($html),
        // ]);

        return response()->json([
            'session_id' => $sessionId,
            'shard_name' => $shardName,
            'html' => $html,
        ]);
    }

    /**
     * List user sessions
     */
    public function listSessions(): JsonResponse
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['data' => []]);
        }

        $allSessions = $this->shardManager->inspectSessions();
        $userSessions = array_filter($allSessions, fn($s) => $s['user_id'] === $userId);

        return response()->json([
            'data' => ShardSessionResource::collection(collect($userSessions)),
        ]);
    }

    /**
     * End session
     */
    public function endSession(string $sessionId): JsonResponse
    {
        $session = $this->shardManager->getSession($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Check ownership
        if (auth()->check() && $session['user_id'] !== null && $session['user_id'] !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->shardManager->teardownSession($sessionId);

        return response()->json([
            'message' => 'Session ended successfully',
            'session_id' => $sessionId,
        ]);
    }
}
