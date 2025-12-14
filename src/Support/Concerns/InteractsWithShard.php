<?php

declare(strict_types=1);

namespace Shard\Ui\Support\Concerns;

use Shard\Ui\Services\ShardManager;

trait InteractsWithShard
{
    protected function shardManager(): ShardManager
    {
        return app(ShardManager::class);
    }

    protected function startShardSession(?string $identifier = null, ?int $userId = null, ?int $ttl = null): string
    {
        return $this->shardManager()->startSession($identifier, $userId, $ttl);
    }

    protected function pushShardContent(string $sessionId, string $shardName, string $html): void
    {
        $this->shardManager()->attachShardContent($sessionId, $shardName, $html);
    }

    protected function closeShardSession(string $sessionId): void
    {
        $this->shardManager()->teardownSession($sessionId);
    }
}
