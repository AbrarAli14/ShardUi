<?php

declare(strict_types=1);

namespace Shard\Ui\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $session_id
 * @property ?int $user_id
 * @property int $shards
 * @property ?string $created_at
 */
final class ShardSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['session_id'],
            'user_id' => $this->resource['user_id'],
            'shards_count' => $this->resource['shards'],
            'created_at' => $this->resource['created_at'],
        ];
    }
}
