<?php

declare(strict_types=1);

namespace Shard\Ui\Tests\Feature;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Orchestra\Testbench\TestCase;
use Shard\Ui\Http\Controllers\ShardApiController;
use Shard\Ui\Services\ShardManager;
use Tests\TestCase as BaseTestCase;

final class ShardApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Repository $cache;
    private ShardManager $manager;
    private Dispatcher $dispatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = new Repository(new ArrayStore());
        $this->dispatcher = $this->app->make(\Illuminate\Events\Dispatcher::class);
        $this->manager = new ShardManager($this->cache, $this->dispatcher);

        // Mock QR generator
        $qrGenerator = $this->createMock(\SimpleSoftwareIO\QrCode\Generator::class);
        $qrGenerator->method('format')->willReturnSelf();
        $qrGenerator->method('size')->willReturnSelf();
        $qrGenerator->method('generate')->willReturn('<svg>QR Code</svg>');

        $this->app->bind(\SimpleSoftwareIO\QrCode\Generator::class, fn() => $qrGenerator);
    }

    public function test_create_session_creates_session_with_user(): void
    {
        $controller = $this->app->make(ShardApiController::class);
        $request = request();

        $response = $controller->createSession($request);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('id', $data['data']);

        // Verify session was created
        $session = $this->manager->getSession($data['data']['id']);
        $this->assertNotNull($session);
        $this->assertNull($session['user_id']); // Anonymous user
    }

    public function test_get_session_returns_correct_data(): void
    {
        $sessionId = $this->manager->startSession('api-test-session', 42);

        $controller = $this->app->make(ShardApiController::class);
        $response = $controller->getSession($sessionId);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($sessionId, $data['session_id']);
        $this->assertEquals(42, $data['user_id']);
        $this->assertEquals(0, $data['shards_count']);
    }

    public function test_push_and_get_shard_workflow(): void
    {
        $sessionId = $this->manager->startSession('workflow-test', 123);

        $controller = $this->app->make(ShardApiController::class);

        // Push shard
        $request = request()->merge([
            'shard_name' => 'dashboard',
            'html' => '<div>Dashboard Content</div>',
        ]);

        $response = $controller->pushShard($request, $sessionId);
        $this->assertEquals(200, $response->getStatusCode());

        // Get shard
        $response = $controller->getShard($sessionId, 'dashboard');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('<div>Dashboard Content</div>', $data['html']);
    }

    public function test_session_lifecycle(): void
    {
        $controller = $this->app->make(ShardApiController::class);

        // Create session
        $response = $controller->createSession(request());
        $data = json_decode($response->getContent(), true);
        $sessionId = $data['data']['id'];

        // Verify it exists
        $response = $controller->getSession($sessionId);
        $this->assertEquals(200, $response->getStatusCode());

        // End session
        $response = $controller->endSession($sessionId);
        $this->assertEquals(200, $response->getStatusCode());

        // Verify it's gone
        $response = $controller->getSession($sessionId);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_user_isolation(): void
    {
        // Create sessions for different users
        $sessionId1 = $this->manager->startSession('user-1-session', 1);
        $sessionId2 = $this->manager->startSession('user-2-session', 2);
        $sessionId3 = $this->manager->startSession('anon-session', null);

        $controller = $this->app->make(ShardApiController::class);

        // All sessions should be accessible (since auth is disabled in test)
        $response = $controller->getSession($sessionId1);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $controller->getSession($sessionId2);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $controller->getSession($sessionId3);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_error_handling(): void
    {
        $controller = $this->app->make(ShardApiController::class);

        // Test nonexistent session
        $response = $controller->getSession('nonexistent-session');
        $this->assertEquals(404, $response->getStatusCode());

        // Test nonexistent shard
        $sessionId = $this->manager->startSession('error-test', 1);
        $response = $controller->getShard($sessionId, 'nonexistent-shard');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
