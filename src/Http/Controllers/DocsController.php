<?php

declare(strict_types=1);

namespace Shard\Ui\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

final class DocsController extends Controller
{
    public function index(): View
    {
        return view('shard-ui::docs.index');
    }

    public function section(string $section): View
    {
        $sections = [
            'installation' => 'Installation Guide',
            'quick-start' => 'Quick Start',
            'api' => 'API Reference',
            'security' => 'Security & Authentication',
            'frontend' => 'Frontend Integration',
            'configuration' => 'Configuration',
            'testing' => 'Testing',
            'examples' => 'Use Cases & Examples',
            'troubleshooting' => 'Troubleshooting',
        ];

        if (!array_key_exists($section, $sections)) {
            abort(404);
        }

        return view('shard-ui::docs.section', [
            'section' => $section,
            'title' => $sections[$section],
        ]);
    }
}
