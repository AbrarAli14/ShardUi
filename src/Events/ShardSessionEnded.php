<?php

declare(strict_types=1);

namespace Shard\Ui\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class ShardSessionEnded implements ShouldBroadcast
{
    use InteractsWithSockets;

    public function __construct(public readonly string $sessionId) {}

    public function broadcastOn(): Channel
    {
        $channelPrefix = config('shard-ui.channel_prefix', 'private-shard');

        return new PrivateChannel(sprintf('%s.%s', $channelPrefix, $this->sessionId));
    }

    public function broadcastAs(): string
    {
        return 'ShardSessionEnded';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionId,
        ];
    }
}
