<?php

declare(strict_types=1);

namespace Shard\Ui\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Shard\Ui\Services\ShardManager;
use SimpleSoftwareIO\QrCode\Generator;

final class DemoController extends Controller
{
    public function __construct(private readonly Generator $qrCode) {}

    public function __invoke(ShardManager $manager): View
    {
        abort_unless(config('shard-ui.demo.enabled', false), 404);

        $userId = auth()->id();
        $sessionId = $manager->startSession(null, $userId);
        $connectRoute = config('shard-ui.connect_route_name', 'shard.session.connect');
        $expiresAt = now()->addSeconds((int) config('shard-ui.session_ttl', 3600));

        $signedUrl = URL::temporarySignedRoute($connectRoute, $expiresAt, ['session' => $sessionId]);
        $qrSvg = $this->qrCode->format('svg')->size(220)->generate($signedUrl);

        $demoShardName = 'demo-controls';
        $html = view('shard-ui::demo.partials.remote-controls', [
            'sessionId' => $sessionId,
        ])->render();
        $manager->attachShardContent($sessionId, $demoShardName, $html);

        return view('shard-ui::demo.dashboard', [
            'sessionId' => $sessionId,
            'signedUrl' => $signedUrl,
            'qrSvg' => $qrSvg,
            'demoShardName' => $demoShardName,
        ]);
    }
}
