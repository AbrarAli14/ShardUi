<?php

declare(strict_types=1);

namespace Shard\Ui\Services;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;
use Shard\Ui\Events\ShardHtmlPushed;
use Shard\Ui\Events\ShardSessionEnded;
use Shard\Ui\Events\ShardSessionStarted;

final class ShardManager
{
    private const CACHE_KEY_PREFIX = 'shard-ui:sessions:';
    private const INDEX_KEY = 'shard-ui:sessions:index';

    public function __construct(
        private readonly CacheRepository $cache,
        private readonly Dispatcher $dispatcher,
    ) {}

    public function startSession(?string $identifier = null, ?int $userId = null, ?int $ttl = null): string
    {
        $sessionId = $identifier ?? Str::uuid()->toString();
        $ttl ??= (int) config('shard-ui.session_ttl', 3600);

        $cacheKey = $this->cacheKey($sessionId);
        $isNew = ! $this->cache->has($cacheKey);

        if ($isNew) {
            $this->cache->put($cacheKey, [
                'payloads' => [],
                'user_id' => $userId,
                'created_at' => now(),
            ], $ttl);

            $this->addSessionToIndex($sessionId);
            $this->dispatcher->dispatch(new ShardSessionStarted($sessionId));
            $this->log('session-start', $sessionId);
        } else {
            // Extend TTL if existing
            $session = $this->cache->get($cacheKey);
            $this->cache->put($cacheKey, $session, $ttl);
        }

        return $sessionId;
    }

    public function attachShardContent(string $sessionId, string $shardName, string $html): void
    {
        $session = $this->cache->get($this->cacheKey($sessionId));

        if ($session === null) {
            $session = ['payloads' => []];
        }

        $session['payloads'][$shardName] = $html;

        $this->cache->put($this->cacheKey($sessionId), $session, (int) config('shard-ui.session_ttl', 3600));

        $this->dispatcher->dispatch(new ShardHtmlPushed($sessionId, $shardName, $html));
        $this->logPayload($sessionId, $shardName);
    }

    public function getShardContent(string $sessionId, string $shardName): ?string
    {
        $session = $this->cache->get($this->cacheKey($sessionId));

        return $session['payloads'][$shardName] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function allShardContent(string $sessionId): array
    {
        $session = $this->cache->get($this->cacheKey($sessionId));

        return $session['payloads'] ?? [];
    }

    public function teardownSession(string $sessionId): void
    {
        $this->cache->forget($this->cacheKey($sessionId));
        $this->removeSessionFromIndex($sessionId);
        $this->dispatcher->dispatch(new ShardSessionEnded($sessionId));
        $this->log('session-end', $sessionId);
    }

    /**
     * @return list<string>
     */
    public function activeSessions(): array
    {
        return array_values($this->cache->get(self::INDEX_KEY, []));
    }

    /**
     * @return list<string>
     */
    public function purgeExpiredSessions(): array
    {
        $index = $this->cache->get(self::INDEX_KEY, []);
        $remaining = [];
        $purged = [];

        foreach ($index as $sessionId) {
            if ($this->cache->get($this->cacheKey($sessionId)) === null) {
                $purged[] = $sessionId;
                continue;
            }

            $remaining[] = $sessionId;
        }

        $this->cache->forever(self::INDEX_KEY, $remaining);

        return $purged;
    }

    /**
     * @return array<int, array{session_id:string, user_id:?int, shards:int, created_at:string}>
     */
    public function inspectSessions(): array
    {
        $report = [];

        foreach ($this->cache->get(self::INDEX_KEY, []) as $sessionId) {
            $session = $this->cache->get($this->cacheKey($sessionId));

            if ($session === null) {
                continue;
            }

            $report[] = [
                'session_id' => $sessionId,
                'user_id' => $session['user_id'] ?? null,
                'shards' => count($session['payloads'] ?? []),
                'created_at' => $session['created_at'] ?? null,
            ];
        }

        return $report;
    }

    private function cacheKey(string $sessionId): string
    {
        return self::CACHE_KEY_PREFIX . $sessionId;
    }

    private function addSessionToIndex(string $sessionId): void
    {
        $index = $this->cache->get(self::INDEX_KEY, []);

        if (in_array($sessionId, $index, true)) {
            return;
        }

        $index[] = $sessionId;

        $this->cache->forever(self::INDEX_KEY, $index);
    }

    private function removeSessionFromIndex(string $sessionId): void
    {
        $index = $this->cache->get(self::INDEX_KEY, []);
        $filtered = array_values(array_filter($index, static fn (string $id): bool => $id !== $sessionId));

        $this->cache->forever(self::INDEX_KEY, $filtered);
    }

    private function log(string $event, string $sessionId): void
    {
        if (! config('shard-ui.telemetry.log_sessions', true)) {
            return;
        }
    }

    private function logPayload(string $sessionId, string $shardName): void
    {
        if (! config('shard-ui.telemetry.log_payloads', false)) {
            return;
        }
    }
}
