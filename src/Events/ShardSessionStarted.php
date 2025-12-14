<?php

declare(strict_types=1);

namespace Shard\Ui\Events;

final class ShardSessionStarted
{
    public function __construct(public readonly string $sessionId) {}
}
