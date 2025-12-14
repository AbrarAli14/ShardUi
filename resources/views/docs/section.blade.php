<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Shard UI Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        .code-block {
            position: relative;
        }

        .copy-btn {
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            transform: scale(1.1);
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="sectionApp()" x-init="init()">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/docs" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">S</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Shard UI</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/docs" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1"></i>Back to Docs
                    </a>
                    <a href="https://github.com/shard/ui" target="_blank" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                        <i data-lucide="github" class="w-4 h-4 inline mr-2"></i>GitHub
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="/docs" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="home" class="w-4 h-4"></i>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($section === 'installation')
            @include('shard-ui::docs.sections.installation')
        @elseif($section === 'quick-start')
            @include('shard-ui::docs.sections.quick-start')
        @elseif($section === 'api')
            @include('shard-ui::docs.sections.api')
        @elseif($section === 'security')
            @include('shard-ui::docs.sections.security')
        @elseif($section === 'frontend')
            @include('shard-ui::docs.sections.frontend')
        @elseif($section === 'configuration')
            @include('shard-ui::docs.sections.configuration')
        @elseif($section === 'testing')
            @include('shard-ui::docs.sections.testing')
        @elseif($section === 'examples')
            @include('shard-ui::docs.sections.examples')
        @elseif($section === 'troubleshooting')
            @include('shard-ui::docs.sections.troubleshooting')
        @endif
    </main>

    <script>
        function sectionApp() {
            return {
                init() {
                    lucide.createIcons();
                },

                copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.showToast('Copied to clipboard!');
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                    });
                },

                showToast(message) {
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                }
            }
        }
    </script>
</body>
</html>
