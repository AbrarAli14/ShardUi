<?php

declare(strict_types=1);

namespace Shard\Ui\Tests\Unit;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Shard\Ui\Http\Controllers\ShardApiController;
use Shard\Ui\Services\ShardManager;

final class ShardApiControllerTest extends TestCase
{
    private Repository $cache;
    private ShardManager $manager;
    private Dispatcher $dispatcher;
    private ShardApiController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = new Repository(new ArrayStore());
        $this->dispatcher = new Dispatcher();
        $this->manager = new ShardManager($this->cache, $this->dispatcher);

        // Mock QR code generator
        $qrGenerator = $this->createMock(\SimpleSoftwareIO\QrCode\Generator::class);
        $qrGenerator->method('format')->willReturnSelf();
        $qrGenerator->method('size')->willReturnSelf();
        $qrGenerator->method('generate')->willReturn('<svg>QR Code</svg>');

        $this->controller = new ShardApiController($this->manager, $qrGenerator);
    }

    public function test_create_session_creates_session_and_returns_data(): void
    {
        $request = new Request();

        $response = $this->controller->createSession($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('id', $data['data']);
        $this->assertArrayHasKey('connect_url', $data['data']);
        $this->assertArrayHasKey('qr_code', $data['data']);
        $this->assertEquals('<svg>QR Code</svg>', $data['data']['qr_code']);
    }

    public function test_get_session_returns_correct_data(): void
    {
        $sessionId = $this->manager->startSession('test-session', 42);

        $response = $this->controller->getSession($sessionId);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($sessionId, $data['session_id']);
        $this->assertEquals(42, $data['user_id']);
        $this->assertEquals(0, $data['shards_count']);
    }

    public function test_get_session_with_shards_returns_correct_count(): void
    {
        $sessionId = $this->manager->startSession('test-session', 42);
        $this->manager->attachShardContent($sessionId, 'shard1', '<div>Content 1</div>');
        $this->manager->attachShardContent($sessionId, 'shard2', '<div>Content 2</div>');

        $response = $this->controller->getSession($sessionId);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(2, $data['shards_count']);
    }

    public function test_get_nonexistent_session_returns_404(): void
    {
        $response = $this->controller->getSession('nonexistent');

        $this->assertEquals(404, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Session not found', $data['error']);
    }

    public function test_push_shard_validates_and_stores_content(): void
    {
        $sessionId = $this->manager->startSession('test-session', 42);

        $request = new Request([
            'shard_name' => 'dashboard',
            'html' => '<div>Dashboard Content</div>',
        ]);

        $response = $this->controller->pushShard($request, $sessionId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Shard content pushed successfully', $data['message']);

        // Verify content was stored
        $stored = $this->manager->getShardContent($sessionId, 'dashboard');
        $this->assertEquals('<div>Dashboard Content</div>', $stored);
    }

    public function test_get_shard_returns_content(): void
    {
        $sessionId = $this->manager->startSession('test-session', 42);
        $this->manager->attachShardContent($sessionId, 'test-shard', '<p>Test HTML</p>');

        $response = $this->controller->getShard($sessionId, 'test-shard');

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($sessionId, $data['session_id']);
        $this->assertEquals('test-shard', $data['shard_name']);
        $this->assertEquals('<p>Test HTML</p>', $data['html']);
    }

    public function test_get_nonexistent_shard_returns_404(): void
    {
        $sessionId = $this->manager->startSession('test-session', 42);

        $response = $this->controller->getShard($sessionId, 'nonexistent');

        $this->assertEquals(404, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Shard not found', $data['error']);
    }

    public function test_list_sessions_returns_empty_for_anonymous(): void
    {
        $response = $this->controller->listSessions();

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['data' => []], $data);
    }

    public function test_end_session_removes_session_data(): void
    {
        $sessionId = $this->manager->startSession('test-session', 42);
        $this->manager->attachShardContent($sessionId, 'test', '<div>Content</div>');

        $response = $this->controller->endSession($sessionId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Session ended successfully', $data['message']);

        // Verify session is gone
        $session = $this->manager->getSession($sessionId);
        $this->assertNull($session);
    }

    public function test_user_id_association_works(): void
    {
        // Test with user ID
        $sessionId1 = $this->manager->startSession('user-session', 123);
        $response = $this->controller->getSession($sessionId1);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(123, $data['user_id']);

        // Test with null user ID (anonymous)
        $sessionId2 = $this->manager->startSession('anon-session', null);
        $response = $this->controller->getSession($sessionId2);
        $data = json_decode($response->getContent(), true);
        $this->assertNull($data['user_id']);
    }

    public function test_controller_handles_malformed_data_gracefully(): void
    {
        $sessionId = $this->manager->startSession('test-session', 42);

        // Test with invalid request data
        $request = new Request([
            'shard_name' => null,
            'html' => null,
        ]);

        // The controller should handle this gracefully
        // Since we're not using Laravel's validation in unit tests,
        // we'll just ensure the method doesn't crash
        $response = $this->controller->pushShard($request, $sessionId);
        $this->assertNotNull($response);
    }
}
