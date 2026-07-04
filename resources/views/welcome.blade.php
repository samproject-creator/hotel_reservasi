<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LuxeHotel - Premium Hotel Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
                <div class="flex items-center gap-3">
                    <div class="bg-slate-900 p-2 rounded-xl text-accent-gold shadow-xl shadow-slate-900/10">
                        <i data-lucide="hotel" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter text-slate-900">LUXE<span class="text-accent-gold">HOTEL</span></span>
                </div>

                <div class="hidden md:flex items-center gap-8 text-sm font-bold uppercase tracking-widest text-slate-500">
                    <a href="#features" class="hover:text-slate-900 transition-colors">Features</a>
                    <a href="#solutions" class="hover:text-slate-900 transition-colors">Solutions</a>
                    <a href="#about" class="hover:text-slate-900 transition-colors">About</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-black transition-all uppercase tracking-widest">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-black transition-all uppercase tracking-widest">Operator Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 opacity-30">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-amber-200 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center space-y-8 max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-accent-gold animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">Enterprise Hospitality Solution</span>
                </div>

                <h1 class="text-5xl lg:text-7xl font-black text-slate-900 tracking-tight leading-[1.1]">
                    Elegance in Every <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-accent-gold">Reservation.</span>
                </h1>

                <p class="text-lg lg:text-xl text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto">
                    The world's most sophisticated management platform for premium boutique hotels and luxury resorts. Streamline operations, elevate guest experiences.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-slate-900 text-white font-black rounded-2xl hover:bg-black transition-all shadow-2xl shadow-slate-900/20 uppercase text-xs tracking-widest flex items-center justify-center gap-3">
                        Get Started <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <button class="w-full sm:w-auto px-10 py-5 bg-white text-slate-900 font-black rounded-2xl hover:bg-slate-50 transition-all border border-slate-200 uppercase text-xs tracking-widest">
                        Book Demo
                    </button>
                </div>
            </div>

            {{-- Mockup --}}
            <div class="mt-20 relative group">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent z-10"></div>
                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-2xl shadow-slate-900/10 overflow-hidden transform group-hover:scale-[1.01] transition-all duration-700">
                    <div class="rounded-2xl overflow-hidden bg-slate-900 aspect-video flex items-center justify-center relative">
                        <i data-lucide="layout-dashboard" class="w-32 h-32 text-slate-800 opacity-20"></i>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-white space-y-4">
                            <div class="w-16 h-16 bg-accent-gold rounded-full flex items-center justify-center animate-bounce">
                                <i data-lucide="play" class="w-8 h-8 fill-current"></i>
                            </div>
                            <span class="font-black uppercase tracking-[0.2em] text-[10px]">Preview Interface</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-24 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-900 border border-slate-100 shadow-sm">
                        <i data-lucide="calendar-check" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Seamless Booking</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Advanced reservation engine with real-time conflict detection and dynamic pricing strategies.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-900 border border-slate-100 shadow-sm">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Instant Alerts</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Integrated WhatsApp and Email notification system powered by enterprise-grade delivery nodes.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-900 border border-slate-100 shadow-sm">
                        <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Deep Analytics</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Comprehensive financial reports and occupancy forecasting to maximize your asset yield.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="grid grid-cols-8 gap-px">
                @for($i = 0; $i < 64; $i++)
                    <div class="aspect-square border border-white"></div>
                @endfor
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center space-y-8">
            <h2 class="text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Ready to Elevate Your <br>Hospitality Standard?</h2>
            <p class="text-slate-400 font-medium max-w-xl mx-auto">Join the most prestigious hotels worldwide using LuxeHotel to define the future of luxury management.</p>
            <div class="pt-4">
                <a href="{{ route('login') }}" class="px-12 py-5 bg-accent-gold text-slate-900 font-black rounded-2xl hover:bg-white transition-all uppercase text-xs tracking-widest shadow-2xl">
                    Begin Digital Transformation
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
                <i data-lucide="twitter" class="w-5 h-5 hover:text-slate-900 cursor-pointer transition-colors"></i>
                <i data-lucide="instagram" class="w-5 h-5 hover:text-slate-900 cursor-pointer transition-colors"></i>
                <i data-lucide="linkedin" class="w-5 h-5 hover:text-slate-900 cursor-pointer transition-colors"></i>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
