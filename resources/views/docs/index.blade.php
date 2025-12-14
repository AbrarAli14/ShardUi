<!DOCTYPE html>
<html lang="en" class="scroll-smooth font-poppins" x-data="docsApp()" x-init="init()" :class="{ 'dark': isDark }" x-cloak>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shard UI Documentation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'slide-in-left': 'slideInLeft 0.6s ease-out',
                        'slide-in-right': 'slideInRight 0.6s ease-out',
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 5px rgba(59, 130, 246, 0.5)' },
                            '100%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.8)' },
                        },
                        slideInLeft: {
                            '0%': { transform: 'translateX(-100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        slideInRight: {
                            '0%': { transform: 'translateX(100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        fadeInUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                    },
                },
            },
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }
        .dark .gradient-bg {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 50%, #4a5568 100%);
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 1.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            z-index: 2;
        }
        .dark .timeline-item::before {
            border-color: #2d3748;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.5);
        }
        .timeline-item.active::before {
            background: linear-gradient(45deg, #10b981, #059669);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.3);
            transform: scale(1.2);
            animation: pulse-green 2s infinite;
        }
        .timeline-line {
            position: absolute;
            left: 1rem;
            top: 3rem;
            bottom: -1rem;
            width: 2px;
            background: linear-gradient(to bottom, #667eea, rgba(102, 126, 234, 0.3));
            z-index: 1;
        }
        .timeline-line.active {
            background: linear-gradient(to bottom, #10b981, rgba(16, 185, 129, 0.6));
            animation: flow-down 2s ease-in-out infinite;
        }

        .parallax-element {
            transition: transform 0.1s ease-out;
        }

        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-reveal:nth-child(1) { transition-delay: 0.1s; }
        .stagger-reveal:nth-child(2) { transition-delay: 0.2s; }
        .stagger-reveal:nth-child(3) { transition-delay: 0.3s; }
        .stagger-reveal:nth-child(4) { transition-delay: 0.4s; }
        .stagger-reveal:nth-child(5) { transition-delay: 0.5s; }
        .stagger-reveal:nth-child(6) { transition-delay: 0.6s; }

        .scale-on-scroll {
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.6s ease-out;
        }
        .scale-on-scroll.revealed {
            transform: scale(1);
            opacity: 1;
        }

        .slide-in-from-left {
            transform: translateX(-50px);
            opacity: 0;
            transition: all 0.8s ease-out;
        }
        .slide-in-from-left.revealed {
            transform: translateX(0);
            opacity: 1;
        }

        .slide-in-from-right {
            transform: translateX(50px);
            opacity: 0;
            transition: all 0.8s ease-out;
        }
        .slide-in-from-right.revealed {
            transform: translateX(0);
            opacity: 1;
        }

        @keyframes float-up {
            0% { transform: translateY(100px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        @keyframes scale-in {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes slide-up {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .feature-card {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 1px;
        }

        .sidebar {
            transition: transform 0.3s ease;
        }

        .copy-btn {
            transition: all 0.2s ease;
        }
        .copy-btn:hover {
            transform: scale(1.1);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
            }
            
            .timeline-step {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .timeline-step .lg\\:hidden {
                margin-bottom: 1rem;
            }
            
            .timeline-step .bg-white\\/90 {
                padding: 1.5rem;
            }
            
            .timeline-step .text-2xl {
                font-size: 1.5rem;
            }
            
            .timeline-step .text-xl {
                font-size: 1.125rem;
            }
            
            .group .w-16 {
                width: 3rem;
                height: 3rem;
            }
            
            .group .w-16 i {
                width: 1.5rem;
                height: 1.5rem;
            }
            
            .group .text-2xl {
                font-size: 1.25rem;
            }
            
            .group .text-xl {
                font-size: 1rem;
            }
            
            .grid {
                gap: 1rem;
            }
            
            .rounded-3xl {
                border-radius: 1.5rem;
            }
        }

        /* Prevent horizontal overflow */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        * {
            box-sizing: border-box;
        }

        img, video, canvas {
            max-width: 100%;
            height: auto;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.8; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        @keyframes bounce-gentle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-bounce-gentle {
            animation: bounce-gentle 3s ease-in-out infinite;
        }

        /* Timeline Flow Animation */
        .timeline-flow {
            background: linear-gradient(180deg, 
                rgba(168, 85, 247, 0.8) 0%, 
                rgba(236, 72, 153, 0.8) 50%, 
                rgba(59, 130, 246, 0.8) 100%);
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.6);
            filter: blur(0.5px);
        }

        /* Enhanced Mobile Timeline */
        @media (max-width: 768px) {
            .timeline-step {
                padding: 2rem 1rem;
            }
            
            .timeline-step .relative.group/card {
                transform: scale(0.95);
                transition: transform 0.3s ease;
            }
            
            .timeline-step:hover .relative.group/card {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="dark:bg-gray-800 dark:bg-gray-900 dark:text-white dark:text-gray-100 min-h-screen font-poppins antialiased" x-data="docsApp()" x-init="init()">
    <!-- Modern Navigation Bar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-200/20 dark:border-gray-700/20 shadow-lg" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="/docs" class="flex items-center space-x-2 group">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300 animate-glow">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Shard UI</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#hero" @click="scrollToSection('hero'); mobileMenuOpen = false" class="nav-link px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'hero' }">Home</a>
                    <a href="#features" @click="scrollToSection('features'); mobileMenuOpen = false" class="nav-link px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'features' }">Features</a>
                    <a href="#installation" @click="scrollToSection('installation'); mobileMenuOpen = false" class="nav-link px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'installation' }">Installation</a>
                    <a href="#api" @click="scrollToSection('api'); mobileMenuOpen = false" class="nav-link px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'api' }">API</a>
                    <a href="#examples" @click="scrollToSection('examples'); mobileMenuOpen = false" class="nav-link px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'examples' }">Examples</a>
                    <a href="#biometric" @click="scrollToSection('biometric'); mobileMenuOpen = false" class="nav-link px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'biometric' }">Biometric</a>
                    <a href="#analytics" @click="scrollToSection('analytics'); mobileMenuOpen = false" class="nav-link px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'analytics' }">Analytics</a>
                    <div class="h-6 w-px bg-gray-300 dark:bg-gray-600 mx-2"></div>
                    <button @click="toggleTheme()" class="p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors group">
                        <i data-lucide="sun" class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" x-show="!isDark" x-cloak></i>
                        <i data-lucide="moon" class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" x-show="isDark" x-cloak></i>
                    </button>
                    <a href="https://github.com/shard/ui" target="_blank" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i data-lucide="github" class="w-4 h-4 inline mr-2"></i>GitHub
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center space-x-2">
                    <button @click="toggleTheme()" class="p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors">
                        <i data-lucide="sun" class="w-5 h-5" x-show="!isDark" x-cloak></i>
                        <i data-lucide="moon" class="w-5 h-5" x-show="isDark" x-cloak></i>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors relative">
                        <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenuOpen" x-cloak></i>
                        <i data-lucide="x" class="w-6 h-6" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="md:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-2xl mt-4 p-4 shadow-2xl border border-gray-200/20 dark:border-gray-700/20" x-cloak>
                <div class="space-y-2">
                    <a href="#hero" @click="scrollToSection('hero'); mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'hero' }">
                        <i data-lucide="home" class="w-5 h-5 mr-3"></i>Home
                    </a>
                    <a href="#features" @click="scrollToSection('features'); mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'features' }">
                        <i data-lucide="zap" class="w-5 h-5 mr-3"></i>Features
                    </a>
                    <a href="#installation" @click="scrollToSection('installation'); mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'installation' }">
                        <i data-lucide="download" class="w-5 h-5 mr-3"></i>Installation
                    </a>
                    <a href="#api" @click="scrollToSection('api'); mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'api' }">
                        <i data-lucide="code" class="w-5 h-5 mr-3"></i>API
                    </a>
                    <a href="#examples" @click="scrollToSection('examples'); mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'examples' }">
                        <i data-lucide="lightbulb" class="w-5 h-5 mr-3"></i>Examples
                    </a>
                    <a href="#biometric" @click="scrollToSection('biometric'); mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'biometric' }">
                        <i data-lucide="fingerprint" class="w-5 h-5 mr-3"></i>Biometric
                    </a>
                    <a href="#analytics" @click="scrollToSection('analytics'); mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors" :class="{ 'bg-indigo-100 dark:bg-gray-800 text-indigo-700 dark:text-indigo-300': activeSection === 'analytics' }">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>Analytics
                    </a>
                    <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>
                    <a href="https://github.com/shard/ui" target="_blank" class="flex items-center px-4 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">
                        <i data-lucide="github" class="w-5 h-5 mr-3"></i>View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="gradient-bg py-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden min-h-screen flex items-center pt-24">
        <!-- Floating Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 dark:bg-gray-900/10 rounded-full animate-float parallax-element" style="animation-delay: 0s;" data-parallax="0.5"></div>
        <div class="absolute top-20 right-20 w-16 h-16 bg-purple-300/20 rounded-full animate-float parallax-element" style="animation-delay: 2s;" data-parallax="0.3"></div>
        <div class="absolute bottom-20 left-20 w-12 h-12 bg-pink-300/20 rounded-full animate-float parallax-element" style="animation-delay: 4s;" data-parallax="0.7"></div>
        <div class="absolute top-1/2 left-5 w-8 h-8 bg-blue-300/20 rounded-full animate-bounce-gentle parallax-element" style="animation-delay: 1s;" data-parallax="0.4"></div>
        <div class="absolute bottom-1/3 right-10 w-14 h-14 bg-yellow-300/20 rounded-full animate-float parallax-element" style="animation-delay: 3s;" data-parallax="0.6"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="animate-fade-in-up">
                <div class="inline-flex items-center px-4 py-2 dark:bg-gray-900/20 backdrop-blur-sm rounded-full text-white text-sm font-medium mb-6 animate-slide-in-left border border-white/20">
                    <i data-lucide="sparkles" class="w-4 h-4 mr-2 animate-glow"></i>
                    Next-Generation Laravel Package
                </div>
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight animate-slide-in-right">
                    Distributed<br>
                    <span class="bg-gradient-to-r from-yellow-300 via-pink-300 to-purple-300 bg-clip-text text-transparent animate-glow">Viewports</span><br>
                    <span class="text-3xl md:text-5xl text-blue-200 animate-fade-in-up" style="animation-delay: 0.5s;">for Laravel</span>
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-4xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.3s;">
                    Enable seamless cross-device interactions with real-time shard content streaming.
                    Perfect for collaborative dashboards, remote controls, and distributed UIs.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style="animation-delay: 0.6s;">
                    <a href="#installation" @click="scrollToSection('installation')" class="dark:bg-gray-900 dark:text-white px-8 py-4 rounded-xl font-semibold hover:dark:bg-gray-800 hover:scale-105 transition-all duration-300 shadow-xl hover:shadow-2xl animate-bounce-gentle border border-white/20">
                        <i data-lucide="rocket" class="w-5 h-5 inline mr-2"></i>
                        Get Started
                    </a>
                    <button @click="showDemo = true" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:dark:bg-gray-900 hover:dark:text-white hover:scale-105 transition-all duration-300 backdrop-blur-sm dark:bg-gray-900/10 shadow-lg hover:shadow-xl">
                        <i data-lucide="play" class="w-5 h-5 inline mr-2"></i>
                        View Demo
                    </button>
                </div>
            </div>

            <!-- Hero Illustration -->
            <div class="mt-16 animate-fade-in-up" style="animation-delay: 0.9s;">
                <div class="relative max-w-5xl mx-auto">
                    <div class="dark:bg-gray-900/10 backdrop-blur-lg rounded-3xl p-8 shadow-2xl border border-white/20 animate-glow">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center text-white">
                            <div class="animate-slide-in-left" style="animation-delay: 1.2s;">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="monitor" class="w-8 h-8"></i>
                                </div>
                                <h3 class="font-semibold text-lg">Desktop</h3>
                                <p class="text-sm text-blue-200">Host Interface</p>
                            </div>
                            <div class="animate-fade-in-up" style="animation-delay: 1.4s;">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg animate-bounce-gentle hover:scale-110 transition-transform duration-300" style="animation-delay: 0.5s;">
                                    <i data-lucide="zap" class="w-8 h-8"></i>
                                </div>
                                <h3 class="font-semibold text-lg">Real-time</h3>
                                <p class="text-sm text-green-200">WebSocket Sync</p>
                            </div>
                            <div class="animate-slide-in-right" style="animation-delay: 1.6s;">
                                <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="smartphone" class="w-8 h-8"></i>
                                </div>
                                <h3 class="font-semibold text-lg">Mobile</h3>
                                <p class="text-sm text-purple-200">Remote Control</p>
                            </div>
                        </div>
                        <!-- Animated Connection Lines -->
                        <div class="absolute top-1/2 left-1/3 w-16 h-0.5 bg-gradient-to-r from-blue-400 to-green-400 animate-pulse" style="transform: rotate(15deg);"></div>
                        <div class="absolute top-1/2 right-1/3 w-16 h-0.5 bg-gradient-to-l from-green-400 to-purple-400 animate-pulse" style="transform: rotate(-15deg); animation-delay: 0.5s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section - Enhanced Content -->
    <section id="features" class="relative py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-blue-900/20 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-green-400/20 to-blue-400/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-blue-400/20 dark:to-purple-400/20 rounded-full mb-6">
                    <span class="text-blue-600 dark:text-blue-400 text-sm font-semibold">Core Features</span>
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 dark:from-white dark:via-blue-200 dark:to-purple-200 bg-clip-text text-transparent mb-6">
                    Everything You Need
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-12">
                    Shard UI revolutionizes multi-device experiences with cutting-edge distributed viewport technology
                </p>
                
                <!-- Key Differentiators -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 max-w-4xl mx-auto">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-gray-700/50">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="layers" class="w-6 h-6 text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Distributed Design</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Break free from single-screen limitations</p>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-gray-700/50">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="zap" class="w-6 h-6 text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Real-time Sync</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Instant updates across all connected devices</p>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-gray-700/50">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="shield" class="w-6 h-6 text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Secure Channels</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Signed WebSocket connections with authentication</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-blue-500/25">
                            <i data-lucide="zap" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Lightning Fast</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Optimized for performance with minimal overhead and maximum efficiency. Sub-100ms response times.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-green-500/25">
                            <i data-lucide="smartphone" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Mobile First</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Responsive design that works perfectly on all devices and screen sizes with adaptive layouts.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-red-500/25">
                            <i data-lucide="test-tube" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Thoroughly Tested</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Comprehensive test suite with 100% API coverage and reliability guarantees.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-purple-500/25">
                            <i data-lucide="book-open" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Well Documented</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Complete API reference with practical examples and implementation guides.</p>
                    </div>
                </div>
            </div>
            
            <!-- Technical Specifications -->
            <div class="mt-16 bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl rounded-3xl p-8 border border-white/20 dark:border-gray-700/50">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">Technical Architecture</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="server" class="w-8 h-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">Laravel Backend</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">WebSocket management, session handling, and HTML rendering</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="radio" class="w-8 h-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">Real-time Communication</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Laravel Reverb WebSocket server with private channels</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="monitor" class="w-8 h-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">Multi-device Display</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Desktop host + mobile remote with synchronized state</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Installation Section - Revolutionary Creative Timeline -->
    <section id="installation" class="relative py-32 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 via-purple-900/20 to-slate-900 dark:from-slate-900 dark:via-purple-900/30 dark:to-slate-900 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-20 w-32 h-32 bg-gradient-to-r from-purple-500/30 to-pink-500/30 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-48 h-48 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-full blur-3xl animate-bounce-gentle" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-40 h-40 bg-gradient-to-r from-green-500/25 to-emerald-500/25 rounded-full blur-2xl animate-pulse" style="animation-delay: 4s;"></div>
            
            <!-- Floating particles -->
            <div class="absolute top-10 left-1/4 w-2 h-2 bg-white/40 rounded-full animate-float" style="animation-delay: 0s;"></div>
            <div class="absolute top-1/3 right-1/3 w-3 h-3 bg-purple-400/30 rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/4 left-1/2 w-2 h-2 bg-blue-400/40 rounded-full animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 right-1/4 w-4 h-4 bg-pink-400/25 rounded-full animate-float" style="animation-delay: 3s;"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-24">
                <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500/20 via-pink-500/20 to-blue-500/20 rounded-full mb-8 border border-purple-400/30 backdrop-blur-sm">
                    <span class="text-purple-300 dark:text-purple-200 text-sm font-bold tracking-wider uppercase">Quick Setup</span>
                </div>
                <h2 class="text-5xl md:text-6xl lg:text-7xl font-black bg-gradient-to-r from-purple-400 via-pink-400 to-blue-400 bg-clip-text text-transparent mb-8 leading-tight">
                    Installation Journey
                </h2>
                <p class="text-xl md:text-2xl text-gray-300 max-w-4xl mx-auto leading-relaxed">
                    Experience the magic of setting up Shard UI with our interactive visual timeline
                </p>
            </div>

            <!-- Revolutionary Timeline Design -->
            <div class="relative">
                <!-- Central Animated Path -->
                <div class="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-2 bg-gradient-to-b from-purple-500 via-pink-500 to-blue-500 opacity-30 hidden lg:block"></div>
                <div class="timeline-flow absolute left-1/2 -translate-x-1/2 top-0 w-2 bg-gradient-to-b from-purple-500 via-pink-500 to-blue-500 hidden lg:block transition-all duration-2000 ease-out" :style="{ height: flowHeight + '%', opacity: flowOpacity }"></div>
                
                <!-- Timeline Steps Container -->
                <div class="space-y-32 lg:space-y-48">
                    <!-- Step 1 & 2 Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
                        <!-- Step 1 - Left Side -->
                        <div class="timeline-step relative group pt-20" :class="{ 'step-active': activeTimelineStep === 0 }">
                            <!-- Mobile Progress Indicator -->
                            <div class="lg:hidden flex justify-center mb-8">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center shadow-2xl shadow-purple-500/50 animate-pulse">
                                        <span class="text-white font-black text-xl">1</span>
                                    </div>
                                    <div class="absolute -inset-4 bg-purple-500/20 rounded-full animate-ping"></div>
                                </div>
                            </div>
                            
                            <!-- Desktop Node -->
                            <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 top-0 items-center justify-center">
                                <div class="relative group/node">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center shadow-2xl shadow-purple-500/50 transition-all duration-700" :class="{ 'scale-125 rotate-12': activeTimelineStep === 0 }">
                                        <div class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center">
                                            <i data-lucide="package" class="w-5 h-5 text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="absolute -inset-4 bg-gradient-to-r from-purple-500/30 to-pink-500/30 rounded-full blur-xl transition-all duration-700" :class="{ 'scale-125 opacity-100': activeTimelineStep === 0, 'scale-100 opacity-0': activeTimelineStep !== 0 }"></div>
                                </div>
                            </div>
                            
                            <!-- Content Card -->
                            <div class="relative group/card">
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-3xl blur-xl transition-all duration-700 group-hover/card:scale-110"></div>
                                <div class="relative bg-white/10 dark:bg-gray-800/10 backdrop-blur-2xl rounded-3xl p-8 border border-white/20 shadow-2xl hover:shadow-purple-500/20 transition-all duration-700 overflow-hidden">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center transform transition-all duration-700 group-hover/card:rotate-12">
                                            <i data-lucide="package" class="w-7 h-7 text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-black text-white mb-2">Install Package</h3>
                                            <div class="flex items-center gap-2">
                                                <span class="text-purple-300 font-bold text-sm">Step 01</span>
                                                <div class="w-6 h-0.5 bg-purple-400"></div>
                                                <span class="text-purple-400 text-xs">Foundation</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-300 mb-6 text-base leading-relaxed">
                                        Begin your journey by adding Shard UI to your Laravel project with a single, powerful Composer command
                                    </p>
                                    <div class="relative group/code">
                                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-pink-500/10 rounded-2xl blur-xl transition-all duration-700 group-hover/code:scale-105"></div>
                                        <div class="relative bg-gray-900/80 backdrop-blur rounded-2xl p-4 border border-purple-500/30 overflow-hidden">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                    <span class="text-green-400 text-xs font-mono">Terminal Ready</span>
                                                </div>
                                                <button @click="copyToClipboard('composer require shard/ui')" class="text-purple-400 hover:text-purple-300 transition-colors">
                                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                            <pre class="text-green-400 font-mono text-xs overflow-x-auto whitespace-pre-wrap break-all"><code>composer require shard/ui</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 - Right Side -->
                        <div class="timeline-step relative group pt-20" :class="{ 'step-active': activeTimelineStep === 1 }">
                            <!-- Mobile Progress Indicator -->
                            <div class="lg:hidden flex justify-center mb-8">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center shadow-2xl shadow-blue-500/50 animate-pulse">
                                        <span class="text-white font-black text-xl">2</span>
                                    </div>
                                    <div class="absolute -inset-4 bg-blue-500/20 rounded-full animate-ping"></div>
                                </div>
                            </div>
                            
                            <!-- Desktop Node -->
                            <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 top-0 items-center justify-center">
                                <div class="relative group/node">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center shadow-2xl shadow-blue-500/50 transition-all duration-700" :class="{ 'scale-125 -rotate-12': activeTimelineStep === 1 }">
                                        <div class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center">
                                            <i data-lucide="settings" class="w-5 h-5 text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/30 to-cyan-500/30 rounded-full blur-xl transition-all duration-700" :class="{ 'scale-125 opacity-100': activeTimelineStep === 1, 'scale-100 opacity-0': activeTimelineStep !== 1 }"></div>
                                </div>
                            </div>
                            
                            <!-- Content Card -->
                            <div class="relative group/card">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-3xl blur-xl transition-all duration-700 group-hover/card:scale-110"></div>
                                <div class="relative bg-white/10 dark:bg-gray-800/10 backdrop-blur-2xl rounded-3xl p-8 border border-white/20 shadow-2xl hover:shadow-blue-500/20 transition-all duration-700 overflow-hidden">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center transform transition-all duration-700 group-hover/card:-rotate-12">
                                            <i data-lucide="settings" class="w-7 h-7 text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-black text-white mb-2">Publish Assets</h3>
                                            <div class="flex items-center gap-2">
                                                <span class="text-blue-300 font-bold text-sm">Step 02</span>
                                                <div class="w-6 h-0.5 bg-blue-400"></div>
                                                <span class="text-blue-400 text-xs">Configuration</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-300 mb-6 text-base leading-relaxed">
                                        Publish configuration files and assets to unlock the full power of Shard UI in your Laravel project
                                    </p>
                                    <div class="relative group/code">
                                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 rounded-2xl blur-xl transition-all duration-700 group-hover/code:scale-105"></div>
                                        <div class="relative bg-gray-900/80 backdrop-blur rounded-2xl p-4 border border-blue-500/30 overflow-hidden">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                                    <span class="text-blue-400 text-xs font-mono">Artisan Command</span>
                                                </div>
                                                <button @click="copyToClipboard('php artisan vendor:publish --provider="Shard\\Ui\\ShardServiceProvider"')" class="text-blue-400 hover:text-blue-300 transition-colors">
                                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                            <pre class="text-blue-400 font-mono text-xs overflow-x-auto whitespace-pre-wrap break-all"><code>php artisan vendor:publish --provider="Shard\Ui\ShardServiceProvider"</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 & 4 Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
                        <!-- Step 3 - Left Side -->
                        <div class="timeline-step relative group pt-20" :class="{ 'step-active': activeTimelineStep === 2 }">
                            <!-- Mobile Progress Indicator -->
                            <div class="lg:hidden flex justify-center mb-8">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-500 rounded-full flex items-center justify-center shadow-2xl shadow-emerald-500/50 animate-pulse">
                                        <span class="text-white font-black text-xl">3</span>
                                    </div>
                                    <div class="absolute -inset-4 bg-emerald-500/20 rounded-full animate-ping"></div>
                                </div>
                            </div>
                            
                            <!-- Desktop Node -->
                            <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 top-0 items-center justify-center">
                                <div class="relative group/node">
                                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-500 rounded-full flex items-center justify-center shadow-2xl shadow-emerald-500/50 transition-all duration-700" :class="{ 'scale-125 rotate-45': activeTimelineStep === 2 }">
                                        <div class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center">
                                            <i data-lucide="database" class="w-5 h-5 text-emerald-600"></i>
                                        </div>
                                    </div>
                                    <div class="absolute -inset-4 bg-gradient-to-r from-emerald-500/30 to-green-500/30 rounded-full blur-xl transition-all duration-700" :class="{ 'scale-125 opacity-100': activeTimelineStep === 2, 'scale-100 opacity-0': activeTimelineStep !== 2 }"></div>
                                </div>
                            </div>
                            
                            <!-- Content Card -->
                            <div class="relative group/card">
                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 to-green-500/20 rounded-3xl blur-xl transition-all duration-700 group-hover/card:scale-110"></div>
                                <div class="relative bg-white/10 dark:bg-gray-800/10 backdrop-blur-2xl rounded-3xl p-8 border border-white/20 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 overflow-hidden">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-500 rounded-2xl flex items-center justify-center transform transition-all duration-700 group-hover/card:rotate-45">
                                            <i data-lucide="database" class="w-7 h-7 text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-black text-white mb-2">Environment Setup</h3>
                                            <div class="flex items-center gap-2">
                                                <span class="text-emerald-300 font-bold text-sm">Step 03</span>
                                                <div class="w-6 h-0.5 bg-emerald-400"></div>
                                                <span class="text-emerald-400 text-xs">WebSocket Ready</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-300 mb-6 text-base leading-relaxed">
                                        Configure WebSocket broadcasting and authentication to enable real-time shard communication
                                    </p>
                                    <div class="relative group/code">
                                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-green-500/10 rounded-2xl blur-xl transition-all duration-700 group-hover/code:scale-105"></div>
                                        <div class="relative bg-gray-900/80 backdrop-blur rounded-2xl p-4 border border-emerald-500/30 overflow-hidden">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                                    <span class="text-emerald-400 text-xs font-mono">Environment Config</span>
                                                </div>
                                                <button @click="copyToClipboard(`# WebSocket Configuration\nBROADCAST_CONNECTION=reverb\nREVERB_APP_ID=your-app-id\nREVERB_APP_KEY=your-app-key\nREVERB_APP_SECRET=your-app-secret\nREVERB_HOST=localhost\nREVERB_PORT=8080\nREVERB_SCHEME=http\n\n# Client Configuration\nVITE_REVERB_APP_KEY=your-app-key\nVITE_REVERB_ENCRYPTED=false\n\n# Shard UI Settings\nSHARD_UI_REQUIRE_AUTH=false\nSHARD_UI_ALLOW_ANONYMOUS=true\nSHARD_UI_ENABLE_DEMO=true`)" class="text-emerald-400 hover:text-emerald-300 transition-colors">
                                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                            <pre class="text-emerald-400 font-mono text-xs overflow-x-auto whitespace-pre-wrap break-all"><code># WebSocket Configuration
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Client Configuration
VITE_REVERB_APP_KEY=your-app-key
VITE_REVERB_ENCRYPTED=false

# Shard UI Settings
SHARD_UI_REQUIRE_AUTH=false
SHARD_UI_ALLOW_ANONYMOUS=true
SHARD_UI_ENABLE_DEMO=true</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 - Right Side -->
                        <div class="timeline-step relative group pt-20" :class="{ 'step-active': activeTimelineStep === 3 }">
                            <!-- Mobile Progress Indicator -->
                            <div class="lg:hidden flex justify-center mb-8">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center shadow-2xl shadow-orange-500/50 animate-pulse">
                                        <span class="text-white font-black text-xl">4</span>
                                    </div>
                                    <div class="absolute -inset-4 bg-orange-500/20 rounded-full animate-ping"></div>
                                </div>
                            </div>
                            
                            <!-- Desktop Node -->
                            <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 top-0 items-center justify-center">
                                <div class="relative group/node">
                                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center shadow-2xl shadow-orange-500/50 transition-all duration-700" :class="{ 'scale-125 -rotate-45': activeTimelineStep === 3 }">
                                        <div class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center">
                                            <i data-lucide="code" class="w-5 h-5 text-orange-600"></i>
                                        </div>
                                    </div>
                                    <div class="absolute -inset-4 bg-gradient-to-r from-orange-500/30 to-red-500/30 rounded-full blur-xl transition-all duration-700" :class="{ 'scale-125 opacity-100': activeTimelineStep === 3, 'scale-100 opacity-0': activeTimelineStep !== 3 }"></div>
                                </div>
                            </div>
                            
                            <!-- Content Card -->
                            <div class="relative group/card">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-red-500/20 rounded-3xl blur-xl transition-all duration-700 group-hover/card:scale-110"></div>
                                <div class="relative bg-white/10 dark:bg-gray-800/10 backdrop-blur-2xl rounded-3xl p-8 border border-white/20 shadow-2xl hover:shadow-orange-500/20 transition-all duration-700 overflow-hidden">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center transform transition-all duration-700 group-hover/card:-rotate-45">
                                            <i data-lucide="code" class="w-7 h-7 text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-black text-white mb-2">Implementation</h3>
                                            <div class="flex items-center gap-2">
                                                <span class="text-orange-300 font-bold text-sm">Step 04</span>
                                                <div class="w-6 h-0.5 bg-orange-400"></div>
                                                <span class="text-orange-400 text-xs">Ready to Build</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-300 mb-6 text-base leading-relaxed">
                                        Implement shard components in your Blade views and unlock the full potential of distributed UIs
                                    </p>
                                    <div class="relative group/code">
                                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-red-500/10 rounded-2xl blur-xl transition-all duration-700 group-hover/code:scale-105"></div>
                                        <div class="relative bg-gray-900/80 backdrop-blur rounded-2xl p-4 border border-orange-500/30 overflow-hidden">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                                                    <span class="text-orange-400 text-xs font-mono">Blade Template</span>
                                                </div>
                                                <button @click="copyToClipboard('<!-- resources/views/dashboard.blade.php -->\n&lt;x-shard target=&quot;mobile&quot; name=&quot;controls&quot;&gt;\n    &lt;div class=&quot;p-4 bg-gray-900 rounded-lg shadow&quot;&gt;\n        &lt;h2 class=&quot;text-lg font-semibold mb-4&quot;&gt;Remote Controls&lt;/h2&gt;\n        &lt;button @click=&quot;status = \'activated\'&quot;\n                class=&quot;px-4 py-2 bg-blue-500 text-white rounded&quot;&gt;\n            Activate System\n        &lt;/button&gt;\n        &lt;p x-text=&quot;status&quot; class=&quot;mt-2&quot;&gt;&lt;/p&gt;\n    &lt;/div&gt;\n&lt;/x-shard&gt;')" class="text-orange-400 hover:text-orange-300 transition-colors">
                                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                            <pre class="text-orange-400 font-mono text-xs overflow-x-auto whitespace-pre-wrap break-all"><code>&lt;!-- resources/views/dashboard.blade.php --&gt;
&lt;x-shard target="mobile" name="controls"&gt;
    &lt;div class="p-4 bg-gray-900 rounded-lg shadow"&gt;
        &lt;h2 class="text-lg font-semibold mb-4"&gt;Remote Controls&lt;/h2&gt;
        &lt;button @click="status = 'activated'"
                class="px-4 py-2 bg-blue-500 text-white rounded"&gt;
            Activate System
        &lt;/button&gt;
        &lt;p x-text="status" class="mt-2"&gt;&lt;/p&gt;
    &lt;/div&gt;
&lt;/x-shard&gt;</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completion Celebration -->
                    <div class="text-center mt-32">
                        <div class="relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 via-pink-600 to-orange-600 rounded-full shadow-2xl shadow-purple-500/50 animate-pulse">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-orange-600 rounded-full blur-xl animate-pulse"></div>
                            <div class="relative flex items-center gap-3">
                                <i data-lucide="sparkles" class="w-6 h-6 text-white animate-spin" style="animation-duration: 3s;"></i>
                                <span class="text-white font-black text-xl">Installation Complete!</span>
                                <i data-lucide="check-circle" class="w-6 h-6 text-white animate-bounce-gentle"></i>
                            </div>
                        </div>
                        <p class="text-gray-300 mt-6 text-lg">You're now ready to build amazing distributed UI experiences</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- API Section - Modern Redesign -->
    <section id="api" class="relative py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-white to-slate-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900/20 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-cyan-400/20 to-blue-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/20 to-cyan-400/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-6xl mx-auto">
            <div class="text-center mb-20">
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500/10 to-blue-500/10 dark:from-cyan-400/20 dark:to-blue-400/20 rounded-full mb-6">
                    <span class="text-cyan-600 dark:text-cyan-400 text-sm font-semibold">API Reference</span>
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold bg-gradient-to-r from-gray-900 via-cyan-800 to-blue-800 dark:from-white dark:via-cyan-200 dark:to-blue-200 bg-clip-text text-transparent mb-6">
                    RESTful API
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Complete REST API for programmatic shard management with real-time capabilities
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Sessions API Card -->
                <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-cyan-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-cyan-500/25">
                                <i data-lucide="server" class="w-8 h-8 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Session Management</h3>
                                <p class="text-cyan-600 dark:text-cyan-400 font-medium">Core API</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm bg-gray-900 dark:bg-gray-600 px-3 py-1 rounded-lg font-mono text-cyan-400 dark:text-cyan-300">POST /api/shard/sessions</code>
                                    <button @click="copyToClipboard('curl -X POST /api/shard/sessions -H "Authorization: Bearer {token}"')" class="text-gray-400 hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Create new session for shard communication</p>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm bg-gray-900 dark:bg-gray-600 px-3 py-1 rounded-lg font-mono text-cyan-400 dark:text-cyan-300">GET /api/shard/sessions</code>
                                    <button @click="copyToClipboard('curl -X GET /api/shard/sessions -H "Authorization: Bearer {token}"')" class="text-gray-400 hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">List all user sessions with metadata</p>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm bg-gray-900 dark:bg-gray-600 px-3 py-1 rounded-lg font-mono text-cyan-400 dark:text-cyan-300">DELETE /api/shard/sessions/{id}</code>
                                    <button @click="copyToClipboard('curl -X DELETE /api/shard/sessions/{sessionId} -H "Authorization: Bearer {token}"')" class="text-gray-400 hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Terminate session and cleanup resources</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shards API Card -->
                <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-green-500/25">
                                <i data-lucide="layers" class="w-8 h-8 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Shard Content</h3>
                                <p class="text-green-600 dark:text-green-400 font-medium">Content API</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm bg-gray-900 dark:bg-gray-600 px-3 py-1 rounded-lg font-mono text-green-400 dark:text-green-300">POST /api/shard/sessions/{id}/shards</code>
                                    <button @click="copyToClipboard('curl -X POST /api/shard/sessions/{sessionId}/shards -H &quot;Authorization: Bearer {token}&quot; -H &quot;Content-Type: application/json&quot; -d &apos;{&quot;shard_name&quot;: &quot;dashboard&quot;, &quot;html&quot;: &quot;&lt;div class=\&quot;p-4\&quot;&gt;Content&lt;/div&gt;&quot;, &quot;css&quot;: &quot;.custom { color: red; }&quot;, &quot;js&quot;: &quot;console.log(\&quot;loaded\&quot;);&quot;}&apos;')" class="text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Push HTML content to specific shard</p>
                                <div class="bg-gray-900 dark:bg-gray-800 rounded-xl p-3">
                                    <p class="text-xs text-gray-400 mb-2">Request Body:</p>
                                    <pre class="text-xs font-mono text-gray-300 overflow-x-auto"><code>{
  "shard_name": "dashboard",
  "html": "&lt;div class='p-4'&gt;...&lt;/div&gt;",
  "css": "optional-custom-styles",
  "js": "optional-javascript"
}</code></pre>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm bg-gray-900 dark:bg-gray-600 px-3 py-1 rounded-lg font-mono text-green-400 dark:text-green-300">GET /api/shard/sessions/{id}/shards</code>
                                    <button @click="copyToClipboard('curl -X GET /api/shard/sessions/{sessionId}/shards -H &quot;Authorization: Bearer {token}&quot;')" class="text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Retrieve all shards in a session</p>
                                <div class="bg-gray-900 dark:bg-gray-800 rounded-xl p-3">
                                    <p class="text-xs text-gray-400 mb-2">Response:</p>
                                    <pre class="text-xs font-mono text-gray-300 overflow-x-auto"><code>{
  "data": [
    {
      "name": "controls",
      "html": "&lt;div&gt;...&lt;/div&gt;",
      "updated_at": "2024-01-01T12:00:00Z"
    }
  ]
}</code></pre>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm bg-gray-900 dark:bg-gray-600 px-3 py-1 rounded-lg font-mono text-green-400 dark:text-green-300">GET /api/shard/sessions/{id}/shards/{name}</code>
                                    <button @click="copyToClipboard('curl -X GET /api/shard/sessions/{sessionId}/shards/{shardName} -H &quot;Authorization: Bearer {token}&quot;')" class="text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Get specific shard content</p>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm bg-gray-900 dark:bg-gray-600 px-3 py-1 rounded-lg font-mono text-green-400 dark:text-green-300">DELETE /api/shard/sessions/{id}/shards/{name}</code>
                                    <button @click="copyToClipboard('curl -X DELETE /api/shard/sessions/{sessionId}/shards/{shardName} -H &quot;Authorization: Bearer {token}&quot;')" class="text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Remove specific shard from session</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Authentication Note -->
            <div class="mt-12 text-center">
                <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500/10 to-orange-500/10 dark:from-amber-400/20 dark:to-orange-400/20 rounded-full border border-amber-200/50 dark:border-amber-700/50">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-600 dark:text-amber-400 mr-3"></i>
                    <span class="text-amber-800 dark:text-amber-200 font-medium">All API endpoints require Bearer token authentication</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Examples Section - Modern Redesign -->
    <section id="examples" class="relative py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 via-white to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-pink-900/20 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500/10 to-pink-500/10 dark:from-purple-400/20 dark:to-pink-400/20 rounded-full mb-6">
                    <span class="text-purple-600 dark:text-purple-400 text-sm font-semibold">Use Cases</span>
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold bg-gradient-to-r from-gray-900 via-purple-800 to-pink-800 dark:from-white dark:via-purple-200 dark:to-pink-200 bg-clip-text text-transparent mb-6">
                    Real-World Applications
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Discover how developers are using Shard UI to build amazing distributed experiences
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Remote Dashboard -->
                <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-blue-500/25">
                                <i data-lucide="monitor" class="w-8 h-8 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Remote Dashboard</h3>
                                <p class="text-blue-600 dark:text-blue-400 font-medium">IoT Control</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Control IoT devices and systems from your mobile device with real-time feedback and instant updates.
                        </p>
                        <div class="bg-gray-900 dark:bg-gray-900 rounded-2xl p-6 relative group overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <pre class="text-blue-400 text-sm font-mono overflow-x-auto"><code>// Controller
$this->pushShardContent($sessionId, 'controls',
    view('device-controls')->render());</code></pre>
                            <button @click="copyToClipboard(`// Controller\n$this->pushShardContent($sessionId, 'controls',\n    view('device-controls')->render());`)" class="copy-btn absolute top-4 right-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-105">
                                <i data-lucide="copy" class="w-4 h-4 mr-2"></i>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Collaborative Whiteboard -->
                <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-purple-500/25">
                                <i data-lucide="edit-3" class="w-8 h-8 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Collaborative Canvas</h3>
                                <p class="text-purple-600 dark:text-purple-400 font-medium">Real-time Drawing</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Share drawing updates and creative work across multiple devices in real-time with synchronized collaboration.
                        </p>
                        <div class="bg-gray-900 dark:bg-gray-900 rounded-2xl p-6 relative group overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <pre class="text-purple-400 text-sm font-mono overflow-x-auto"><code>// Push drawing updates
$this->pushShardContent($sessionId, 'canvas',
    $drawingHtml);</code></pre>
                            <button @click="copyToClipboard(`// Push drawing updates\n$this->pushShardContent($sessionId, 'canvas',\n    $drawingHtml);`)" class="copy-btn absolute top-4 right-4 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-xl text-sm font-medium opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-105">
                                <i data-lucide="copy" class="w-4 h-4 mr-2"></i>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Multi-screen Presentations -->
                <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-green-500/25">
                                <i data-lucide="presentation" class="w-8 h-8 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Presenter Mode</h3>
                                <p class="text-green-600 dark:text-green-400 font-medium">Multi-screen</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Display different content on presenter notes versus audience view for professional presentations.
                        </p>
                        <div class="bg-gray-900 dark:bg-gray-900 rounded-2xl p-6 relative group overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <pre class="text-green-400 text-sm font-mono overflow-x-auto"><code>// Different content per device
$this->pushShardContent($sessionId, 'notes',
    $presenterNotesHtml);</code></pre>
                            <button @click="copyToClipboard(`// Different content per device\n$this->pushShardContent($sessionId, 'notes',\n    $presenterNotesHtml);`)" class="copy-btn absolute top-4 right-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-105">
                                <i data-lucide="copy" class="w-4 h-4 mr-2"></i>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Live Support -->
                <div class="group relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg shadow-red-500/25">
                                <i data-lucide="headphones" class="w-8 h-8 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Remote Support</h3>
                                <p class="text-red-600 dark:text-red-400 font-medium">Live Assistance</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Provide remote assistance with interactive control interfaces and real-time diagnostic tools.
                        </p>
                        <div class="bg-gray-900 dark:bg-gray-900 rounded-2xl p-6 relative group overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <pre class="text-red-400 text-sm font-mono overflow-x-auto"><code>// Remote support tools
$this->pushShardContent($sessionId, 'support',
    $diagnosticInterfaceHtml);</code></pre>
                            <button @click="copyToClipboard(`// Remote support tools\n$this->pushShardContent($sessionId, 'support',\n    $diagnosticInterfaceHtml);`)" class="copy-btn absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-medium opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-105">
                                <i data-lucide="copy" class="w-4 h-4 mr-2"></i>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="mt-20 text-center">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-3xl p-12 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-50"></div>
                    <div class="relative z-10">
                        <h3 class="text-3xl font-bold mb-4">Start Building Today</h3>
                        <p class="text-xl text-purple-100 mb-8 max-w-2xl mx-auto">
                            Join developers using Shard UI to create innovative distributed experiences
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="#installation" @click="scrollToSection('installation')" class="bg-white text-purple-600 px-8 py-4 rounded-xl font-semibold hover:bg-purple-50 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                                <i data-lucide="rocket" class="w-5 h-5 inline mr-2"></i>
                                Get Started
                            </a>
                            <a href="https://github.com/shard/ui" target="blank" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition-all duration-300">
                                <i data-lucide="github" class="w-5 h-5 inline mr-2"></i>
                                View on GitHub
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Biometric Authentication Section -->
    @include('shard-ui::docs.sections.biometric')

    <!-- Biometric Analytics Section -->
    @include('shard-ui::docs.sections.biometric-analytics')

    <!-- Demo Modal -->
    <div x-show="showDemo" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" @click="showDemo = false">
        <div class="dark:bg-gray-900 rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold dark:text-white">Interactive Demo</h3>
                    <button @click="showDemo = false" class="text-gray-400 hover:dark:text-gray-300">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="text-center py-12">
                    <i data-lucide="play-circle" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                    <h4 class="text-lg font-medium dark:text-white mb-2">Demo Coming Soon</h4>
                    <p class="dark:text-gray-300">Interactive demo with live WebSocket streaming</p>
                    <div class="mt-6">
                        <a href="/shard/demo" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                            View Static Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">S</span>
                        </div>
                        <span class="text-xl font-bold">Shard UI</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Distributed viewports for Laravel. Enable seamless cross-device interactions
                        with real-time shard content streaming.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://github.com/shard/ui" class="text-gray-400 hover:text-white transition-colors">
                            <i data-lucide="github" class="w-5 h-5"></i>
                        </a>
                        <a href="https://packagist.org/packages/shard/ui" class="text-gray-400 hover:text-white transition-colors">
                            <i data-lucide="package" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2">
                        <li><a href="#getting-started" class="text-gray-400 hover:text-white transition-colors">Getting Started</a></li>
                        <li><a href="#api" class="text-gray-400 hover:text-white transition-colors">API Reference</a></li>
                        <li><a href="#examples" class="text-gray-400 hover:text-white transition-colors">Examples</a></li>
                        <li><a href="#biometric" class="text-gray-400 hover:text-white transition-colors">Biometric Auth</a></li>
                        <li><a href="#analytics" class="text-gray-400 hover:text-white transition-colors">Analytics</a></li>
                        <li><a href="https://github.com/shard/ui" class="text-gray-400 hover:text-white transition-colors">GitHub</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Community</h4>
                    <ul class="space-y-2">
                        <li><a href="https://github.com/shard/ui/issues" class="text-gray-400 hover:text-white transition-colors">Issues</a></li>
                        <li><a href="https://github.com/shard/ui/discussions" class="text-gray-400 hover:text-white transition-colors">Discussions</a></li>
                        <li><a href="https://packagist.org/packages/shard/ui" class="text-gray-400 hover:text-white transition-colors">Packagist</a></li>
                        <li><a href="#troubleshooting" class="text-gray-400 hover:text-white transition-colors">Support</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400"> 2025 Shard UI. Built with for the Laravel community.</p>
            </div>
        </div>
    </footer>

    </div>

    <script>
        function docsApp() {
            return {
                isDark: false,
                showDemo: false,
                activeSection: 'hero',
                activeTimelineStep: 0,
                progressHeight: 0,
                flowHeight: 0,
                flowOpacity: 0.3,
                sidebarOpen: false,

                init() {
                    this.isDark = localStorage.getItem('theme') === 'dark' || 
                        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    this.applyTheme();
                    this.initScrollAnimations();
                    this.updateActiveSection();
                    lucide.createIcons();

                    // Add scroll listener for timeline updates
                    window.addEventListener('scroll', () => {
                        this.updateActiveSection();
                    });
                },

                toggleTheme() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    this.applyTheme();
                },

                applyTheme() {
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },

                scrollToSection(sectionId) {
                    const element = document.getElementById(sectionId);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                },

                updateActiveSection() {
                    const sections = ['hero', 'features', 'installation', 'api', 'examples', 'biometric', 'analytics'];
                    const scrollPosition = window.scrollY + 100;

                    for (const section of sections) {
                        const element = document.getElementById(section);
                        if (element) {
                            const { offsetTop, offsetHeight } = element;
                            if (scrollPosition >= offsetTop && scrollPosition < offsetTop + offsetHeight) {
                                this.activeSection = section;
                                break;
                            }
                        }
                    }

                    // Update timeline steps when in installation section
                    if (this.activeSection === 'installation') {
                        this.updateActiveTimelineStep();
                    }
                },

                updateActiveTimelineStep() {
                    const timelineSteps = document.querySelectorAll('.timeline-step');
                    const scrollPosition = window.scrollY + window.innerHeight / 2;
                    const installationSection = document.getElementById('installation');
                    
                    if (!installationSection) return;

                    const sectionTop = installationSection.offsetTop;
                    const sectionHeight = installationSection.offsetHeight;
                    const sectionBottom = sectionTop + sectionHeight;
                    
                    // Calculate flow progress percentage
                    if (scrollPosition >= sectionTop && scrollPosition <= sectionBottom) {
                        const progress = ((scrollPosition - sectionTop) / sectionHeight) * 100;
                        this.flowHeight = Math.min(100, Math.max(0, progress));
                        this.flowOpacity = 0.3 + (progress / 100) * 0.7; // Fade from 0.3 to 1.0
                    } else if (scrollPosition > sectionBottom) {
                        this.flowHeight = 100;
                        this.flowOpacity = 1.0;
                    } else {
                        this.flowHeight = 0;
                        this.flowOpacity = 0.3;
                    }

                    timelineSteps.forEach((step, index) => {
                        const rect = step.getBoundingClientRect();
                        const stepTop = window.scrollY + rect.top;
                        const stepBottom = stepTop + rect.height;

                        if (scrollPosition >= stepTop && scrollPosition < stepBottom) {
                            this.activeTimelineStep = index;
                        }
                    });
                },

                initScrollAnimations() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('animate-fade-in-up');
                                entry.target.classList.add('revealed');
                            }
                        });
                    }, { threshold: 0.1 });

                    document.querySelectorAll('.animate-on-scroll').forEach(el => {
                        observer.observe(el);
                    });

                    // Initialize parallax
                    this.initParallax();

                    // Initialize reveal animations
                    this.initRevealAnimations();
                },

                initParallax() {
                    const parallaxElements = document.querySelectorAll('.parallax-element');

                    const handleScroll = () => {
                        const scrolled = window.pageYOffset;
                        const rate = scrolled * -0.5;

                        parallaxElements.forEach(element => {
                            const speed = parseFloat(element.dataset.parallax) || 0.5;
                            const yPos = -(scrolled * speed);
                            element.style.transform = `translateY(${yPos}px)`;
                        });
                    };

                    window.addEventListener('scroll', handleScroll);
                },

                initRevealAnimations() {
                    const observerOptions = {
                        threshold: 0.1,
                        rootMargin: '0px 0px -50px 0px'
                    };

                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('revealed');
                            }
                        });
                    }, observerOptions);

                    // Observe all elements with reveal classes
                    document.querySelectorAll('.reveal-on-scroll, .scale-on-scroll, .slide-in-from-left, .slide-in-from-right').forEach(el => {
                        observer.observe(el);
                    });
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
                    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-slide-in-right';
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
