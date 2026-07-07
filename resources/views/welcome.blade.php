<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LuxeHotel - Premium Hotel Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest" defer></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f7ff', 100: '#e0effe', 200: '#bae0fd', 300: '#7cc7fb',
                            400: '#38a9f8', 500: '#0e8ce9', 600: '#026fc7', 700: '#0358a1',
                            800: '#074a85', 900: '#0c3f6e', 950: '#082949',
                        },
                        accent: {
                            gold: '#C5A059',
                            dark: '#1e1e1e',
                        }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-slate-50 text-slate-900 font-sans overflow-x-hidden">

    {{-- Navigation --}}
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="#" class="flex items-center gap-3 group">
                    <div class="bg-slate-900 p-2 rounded-xl text-accent-gold shadow-xl shadow-slate-900/10 group-hover:scale-105 transition-transform">
                        <i data-lucide="hotel" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter text-slate-900">LUXE<span class="text-accent-gold">HOTEL</span></span>
                </a>

                <div class="hidden md:flex items-center gap-8 text-sm font-bold uppercase tracking-widest text-slate-500">
                    <a href="#features" class="hover:text-slate-900 transition-colors">Features</a>
                    <a href="#solutions" class="hover:text-slate-900 transition-colors">Solutions</a>
                    <a href="#about" class="hover:text-slate-900 transition-colors">About Project</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-black transition-all uppercase tracking-widest shadow-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-black transition-all uppercase tracking-widest shadow-md">Operator Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 opacity-30 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400 rounded-full blur-[120px] will-change-transform"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-amber-200 rounded-full blur-[120px] will-change-transform"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center space-y-8 max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-accent-gold animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">Web Programming 2 - Final Project</span>
                </div>

                <h1 class="text-5xl lg:text-7xl font-black text-slate-900 tracking-tight leading-[1.1]">
                    Elegance in Every <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-accent-gold">Reservation.</span>
                </h1>

                <p class="text-lg lg:text-xl text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto">
                    A web-based relational management platform engineered for luxury boutique hotels. Designed to optimize database normalization, operational logic, and access control matrices.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-slate-900 text-white font-black rounded-2xl hover:bg-black transition-all shadow-2xl shadow-slate-900/20 uppercase text-xs tracking-widest flex items-center justify-center gap-3">
                        Launch Application <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="#about" class="w-full sm:w-auto px-10 py-5 bg-white text-slate-900 font-black rounded-2xl hover:bg-slate-50 transition-all border border-slate-200 uppercase text-xs tracking-widest text-center">
                        Project Overview
                    </a>
                </div>
            </div>

            {{-- Mockup Interface Preview --}}
            <div class="mt-20 relative group cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent z-10 pointer-events-none"></div>
                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-2xl shadow-slate-900/10 overflow-hidden transform group-hover:scale-[1.005] transition-all duration-500">
                    <div class="rounded-2xl overflow-hidden bg-slate-900 aspect-video flex items-center justify-center relative">
                        <i data-lucide="layout-dashboard" class="w-32 h-32 text-slate-800 opacity-20"></i>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-white space-y-4 bg-slate-950/20 backdrop-blur-[1px]">
                            <div class="w-16 h-16 bg-accent-gold text-slate-900 rounded-full flex items-center justify-center shadow-lg">
                                <i data-lucide="play" class="w-6 h-6 fill-current ml-1"></i>
                            </div>
                            <span class="font-black uppercase tracking-[0.2em] text-[10px] text-slate-200">System Interface Architecture</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 1. FEATURES SECTION --}}
    <section id="features" class="py-24 bg-white border-y border-slate-100 relative z-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4 group">
                    <div class="w-12 h-12 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center border border-slate-100 shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                        <i data-lucide="calendar-check" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Seamless Booking</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">Advanced reservation engine with server-side conflict detection and dynamic data validation logic.</p>
                </div>
                <div class="space-y-4 group">
                    <div class="w-12 h-12 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center border border-slate-100 shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Instant Node Alerts</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">Integrated notification pipeline utilizing automated mail and dispatch delivery nodes.</p>
                </div>
                <div class="space-y-4 group">
                    <div class="w-12 h-12 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center border border-slate-100 shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Relational Analytics</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">Comprehensive aggregation queries rendering structured financial logs and room occupancy yields.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. SOLUTIONS SECTION --}}
    <section id="solutions" class="py-24 bg-slate-50 relative z-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-accent-gold">Multi-Tier Architecture</span>
                <h2 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Engineered for Distinct User Roles</h2>
                <p class="text-slate-500 font-medium text-sm">Designed with Role-Based Access Control (RBAC) to handle authorization layers cleanly.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm space-y-4">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center"><i data-lucide="shield-check" class="w-5 h-5"></i></div>
                    <h4 class="text-lg font-black text-slate-900">Operator Module (Staff)</h4>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Handles fast data mutation for guest check-ins, instantaneous room status clearing, and basic relational transactional records without system administrative overhead.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm space-y-4">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center"><i data-lucide="sliders" class="w-5 h-5"></i></div>
                    <h4 class="text-lg font-black text-slate-900">Administrator Module</h4>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Grants full global CRUD permissions over system assets, rooms config, transaction logs monitoring, and system configuration profiles safely.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. ABOUT SECTION (ACADEMIC SPECS FOR WEB PROGRAMMING 2) --}}
    <section id="about" class="py-24 bg-white relative z-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <span class="text-[10px] font-black uppercase tracking-widest text-accent-gold">Project Background</span>
                        <h2 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Web-Based Hotel Management Information System</h2>
                    </div>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">
                        This LuxeHotel application was developed as a digital prototype solution to handle complex data structures within hospitality business operations. The primary target of this project is to eliminate reservation data overlapping through severe server-side data constraint checks.
                    </p>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">
                        The application implements modern backend routing paradigms, model validation methods, multi-tier user authorization matrices, and dynamic aggregated accounting structures.
                    </p>
                    <div class="grid grid-cols-2 gap-6 pt-2">
                        <div>
                            <div class="text-3xl font-black text-slate-900">ACID</div>
                            <div class="text-slate-400 text-xs font-bold uppercase mt-1">Database Integrity</div>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-slate-900">REST</div>
                            <div class="text-slate-400 text-xs font-bold uppercase mt-1">Architectural Pattern</div>
                        </div>
                    </div>
                </div>

                {{-- Scope Specification Card --}}
                <div class="bg-slate-50 border border-slate-200 rounded-3xl p-8 aspect-[4/3] flex flex-col justify-between">
                    <div class="text-slate-400 text-xs font-black uppercase tracking-widest">Application Scope & Boundaries</div>
                    
                    <div class="space-y-4 my-auto pt-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-1 text-slate-900"><i data-lucide="check-circle-2" class="w-4 h-4"></i></div>
                            <p class="text-slate-600 text-xs font-medium"><strong class="text-slate-900">Multi-User Authentication:</strong> Isolated system workspaces separating root Administrators from desk Operators.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-1 text-slate-900"><i data-lucide="check-circle-2" class="w-4 h-4"></i></div>
                            <p class="text-slate-600 text-xs font-medium"><strong class="text-slate-900">Dynamic Inventory CRUD:</strong> Complete data handling over room records, structural types, and real-time maintenance statuses.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-1 text-slate-900"><i data-lucide="check-circle-2" class="w-4 h-4"></i></div>
                            <p class="text-slate-600 text-xs font-medium"><strong class="text-slate-900">Ledger Calculation Engine:</strong> Automatic billing computation mapped via model parameters based on stay duration inputs.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 border-t border-slate-200 pt-4">
                        <div class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center text-white"><i data-lucide="terminal" class="w-4 h-4"></i></div>
                        <div>
                            <div class="text-sm font-black text-slate-900">Reservation Engine Core v1.0</div>
                            <div class="text-slate-400 text-[10px] font-black uppercase">UAS - Pemograman WEB 2</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 60px 60px;"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center space-y-8">
            <h2 class="text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Ready to Run System Architecture?</h2>
            <p class="text-slate-400 font-medium text-sm max-w-xl mx-auto">Access the localized administrative pipeline deployment module to inspect code behavior and constraints.</p>
            <div class="pt-4">
                <a href="{{ route('login') }}" class="inline-block px-12 py-5 bg-accent-gold text-slate-900 font-black rounded-2xl hover:bg-white hover:scale-105 transition-all uppercase text-xs tracking-widest shadow-2xl">
                    Authenticate Environment
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-white py-12 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="bg-slate-900 p-1.5 rounded-lg text-accent-gold">
                    <i data-lucide="hotel" class="w-4 h-4"></i>
                </div>
                <span class="text-sm font-black tracking-tighter text-slate-900 uppercase">LUXE<span class="text-accent-gold">HOTEL</span></span>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">© {{ date('Y') }} LuxeHotel Global Systems. All architectural rights reserved.</p>
            <div class="flex items-center gap-6 text-slate-400">
                <a href="#" aria-label="Twitter"><i data-lucide="twitter" class="w-5 h-5 hover:text-slate-900 transition-colors"></i></a>
                <a href="#" aria-label="Instagram"><i data-lucide="instagram" class="w-5 h-5 hover:text-slate-900 transition-colors"></i></a>
                <a href="#" aria-label="LinkedIn"><i data-lucide="linkedin" class="w-5 h-5 hover:text-slate-900 transition-colors"></i></a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>