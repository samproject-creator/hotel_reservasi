<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LuxeHotel') - Premium Management</title>

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        accent: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                            950: '#451a03',
                            'gold': '#d4af37',
                        },
                        slate: {
                            850: '#1e293b',
                            950: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-item-active {
            background-color: rgba(14, 165, 233, 0.1);
            color: #0284c7;
            border-right: 4px solid #0ea5e9;
        }
        .dark .sidebar-item-active {
            background-color: rgba(14, 165, 233, 0.05);
            color: #38bdf8;
            border-right: 4px solid #38bdf8;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans transition-colors duration-200">

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside id="sidebar" class="bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 w-64 fixed h-full z-50 transition-transform duration-300 lg:translate-x-0 -translate-x-full">
            <div class="p-6 flex items-center gap-3 border-b border-gray-100 dark:border-gray-700">
                <div class="bg-primary-600 p-2 rounded-lg text-white">
                    <i data-lucide="hotel" class="w-6 h-6"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">LuxeHotel</span>
            </div>

            <nav class="mt-6 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="pt-4 pb-2 px-4">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Operations</span>
                </div>

                <a href="{{ route('booking.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('booking.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span class="font-medium">Bookings</span>
                </a>

                <a href="{{ route('checkin.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('checkin.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    <span class="font-medium">Check-In</span>
                </a>

                <a href="{{ route('checkout.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('checkout.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span class="font-medium">Check-Out</span>
                </a>

                <div class="pt-4 pb-2 px-4">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Master Data</span>
                </div>

                <a href="{{ route('tamu.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('tamu.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-medium">Guests</span>
                </a>

                @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('kamar.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('kamar.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="door-closed" class="w-5 h-5"></i>
                    <span class="font-medium">Rooms</span>
                </a>

                <a href="{{ route('tipe-kamar.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('tipe-kamar.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                    <span class="font-medium">Room Types</span>
                </a>
                @endif
                @endauth

                <div class="pt-4 pb-2 px-4">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Reports & System</span>
                </div>

                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('laporan.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span class="font-medium">Reports</span>
                </a>

                @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('activity-log.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('activity-log.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="history" class="w-5 h-5"></i>
                    <span class="font-medium">Activity Log</span>
                </a>

                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('users.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="user-cog" class="w-5 h-5"></i>
                    <span class="font-medium">User Management</span>
                </a>

                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('settings.*') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span class="font-medium">Settings</span>
                </a>
                @endif
                @endauth
            </nav>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            {{-- Topbar --}}
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 sticky top-0 z-40">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <div class="flex items-center gap-4 ml-auto">
                    {{-- Dark Mode Toggle --}}
                    <button id="dark-mode-toggle" onclick="toggleDarkMode()" class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i data-lucide="moon" class="w-5 h-5 dark:hidden"></i>
                        <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                    </button>

                    {{-- User Dropdown --}}
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-2 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile Settings</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign Out</button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-6 flex-1">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors">
                &copy; {{ date('Y') }} LuxeHotel Premium. All rights reserved.
            </footer>
        </div>
    </div>

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden"></div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
        }
    </script>
    @stack('scripts')
</body>
</html>
