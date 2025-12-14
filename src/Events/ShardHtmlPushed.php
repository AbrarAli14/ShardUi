<?php

declare(strict_types=1);

namespace Shard\Ui\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class ShardHtmlPushed implements ShouldBroadcast
{
    use InteractsWithSockets;

    public function __construct(
        public readonly string $sessionId,
        public readonly string $shardName,
        public readonly string $html
    ) {}

    public function broadcastOn(): Channel
    {
        $channelPrefix = config('shard-ui.channel_prefix', 'private-shard');

        return new Channel(sprintf('%s.%s', $channelPrefix, $this->sessionId));
    }

    public function broadcastAs(): string
    {
        return 'ShardHtmlPushed';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionId,
            'shard_name' => $this->shardName,
            'html' => $this->html,
        ];
    }
}
