<?php

declare(strict_types=1);

namespace Shard\Ui\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $session_id
 * @property string $shard_name
 * @property string $html
 */
final class ShardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'session_id' => $this->resource['session_id'],
            'name' => $this->resource['shard_name'],
            'html' => $this->resource['html'],
        ];
    }
}
