<?php

declare(strict_types=1);

namespace Shard\Ui\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyShardSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid shard signature.');
        }

        $request->attributes->set('shard-ui.remote', true);

        return $next($request);
    }
}
