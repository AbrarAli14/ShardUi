<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;
use Shard\Ui\Services\ShardManager;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('private-shard.{sessionId}', function ($user, $sessionId) {
    $shardManager = app(ShardManager::class);
    $session = $shardManager->getSession($sessionId);

    if (!$session) {
        return false;
    }

    // Allow if session has no user (anonymous/demo) or user matches
    return $session['user_id'] === null || $session['user_id'] === $user->id;
});
