<?php

declare(strict_types=1);

namespace Shard\Ui\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\Component;

final class Shard extends Component
{
    public function __construct(
        private readonly Request $request,
        public readonly string $target = 'mobile',
        public readonly ?string $name = null,
        public readonly ?string $placeholder = null,
    ) {}

    public function render(): View
    {
        return view('shard-ui::components.shard', [
            'isRemote' => $this->isRemoteRequest(),
            'target' => $this->target,
            'placeholder' => $this->getPlaceholderCopy(),
            'name' => $this->name,
        ]);
    }

    public function shardIdentifier(): string
    {
        return $this->name ?? Str::uuid()->toString();
    }

    private function isRemoteRequest(): bool
    {
        if ($this->request->attributes->get('shard-ui.remote') === true) {
            return true;
        }

        $routeName = config('shard-ui.connect_route_name');

        return $routeName !== null && $this->request->routeIs($routeName);
    }

    private function getPlaceholderCopy(): string
    {
        return $this->placeholder ?? __('Scan the QR code to continue on your remote device.');
    }
}
