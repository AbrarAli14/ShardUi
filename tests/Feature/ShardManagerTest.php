<?php

declare(strict_types=1);

namespace Shard\Ui\Tests\Feature;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Events\Dispatcher;
use Orchestra\Testbench\TestCase;
use Shard\Ui\Events\ShardHtmlPushed;
use Shard\Ui\Events\ShardSessionEnded;
use Shard\Ui\Events\ShardSessionStarted;
use Shard\Ui\Services\ShardManager;

final class ShardManagerTest extends TestCase
{
    private Repository $cache;
    private ShardManager $manager;
    private Dispatcher $dispatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = new Repository(new ArrayStore());
        $this->dispatcher = $this->app->make(\Illuminate\Events\Dispatcher::class);
        $this->manager = new ShardManager($this->cache, $this->dispatcher);
    }

    public function test_it_can_start_session_and_store_payload(): void
    {
        $sessionId = $this->manager->startSession('test-session', 10);

        $this->manager->attachShardContent($sessionId, 'controls', '<p>Controls</p>');

        $this->assertSame('<p>Controls</p>', $this->manager->getShardContent($sessionId, 'controls'));
    }

    public function test_teardown_clears_session(): void
    {
        $sessionId = $this->manager->startSession('session-one', 10);

        $this->manager->attachShardContent($sessionId, 'panel', '<div>Panel</div>');

        $this->manager->teardownSession($sessionId);

        $this->assertNull($this->manager->getShardContent($sessionId, 'panel'));
    }

    public function test_dispatches_event_on_attach(): void
    {
        $captured = [];
        $this->dispatcher->listen(ShardHtmlPushed::class, static function (ShardHtmlPushed $event) use (&$captured): void {
            $captured[] = $event;
        });

        $sessionId = $this->manager->startSession('session-two', 10);

        $this->manager->attachShardContent($sessionId, 'panel', '<div>Panel</div>');

        $this->assertCount(1, $captured);
        $this->assertSame('panel', $captured[0]->shardName);
        $this->assertSame('<div>Panel</div>', $captured[0]->html);
    }

    public function test_session_index_tracking(): void
    {
        $first = $this->manager->startSession('session-a', 10);
        $second = $this->manager->startSession('session-b', 10);

        $this->assertEqualsCanonicalizing([$first, $second], $this->manager->activeSessions());

        $this->manager->teardownSession($first);

        $this->assertEquals([$second], $this->manager->activeSessions());
    }

    public function test_purge_expired_sessions(): void
    {
        $active = $this->manager->startSession('session-active', 10);
        $expired = $this->manager->startSession('session-expired', 10);

        $this->manager->teardownSession($expired);

        $purged = $this->manager->purgeExpiredSessions();

        $this->assertContains('session-expired', $purged);
        $this->assertEquals([$active], $this->manager->activeSessions());
    }

    public function test_session_start_and_end_events_are_dispatched(): void
    {
        $started = [];
        $ended = [];

        $this->dispatcher->listen(ShardSessionStarted::class, static function (ShardSessionStarted $event) use (&$started): void {
            $started[] = $event->sessionId;
        });

        $this->dispatcher->listen(ShardSessionEnded::class, static function (ShardSessionEnded $event) use (&$ended): void {
            $ended[] = $event->sessionId;
        });

        $sessionId = $this->manager->startSession('session-z', 10);
        $this->manager->teardownSession($sessionId);

        $this->assertSame(['session-z'], $started);
        $this->assertSame(['session-z'], $ended);
    }
}
