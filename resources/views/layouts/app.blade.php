<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Transylvania') 🦇</title>

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts: Creepster (display) + Cinzel (heading) + Inter (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Cinzel:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'castle': {
                            'dark':   '#0a0512',
                            'deep':   '#120820',
                            'panel':  '#1a0f2e',
                            'border': '#2d1b4e',
                            'mist':   '#3d2a5e',
                        },
                        'pumpkin': { DEFAULT: '#ff6b00', 'light': '#ff8c38', 'glow': '#ff6b0033' },
                        'neon':    { DEFAULT: '#39ff14', 'muted': '#22cc0d', 'glow': '#39ff1420' },
                        'blood':   '#dc2626',
                        'bone':    '#e8e0d5',
                        'ghost':   '#9d8fba',
                        'lavender':'#c084fc',
                    },
                    fontFamily: {
                        'creepster': ['Creepster', 'cursive'],
                        'cinzel':    ['Cinzel', 'serif'],
                        'inter':     ['Inter', 'sans-serif'],
                    },
                    keyframes: {
                        'bat-fly':  { '0%,100%': { transform: 'translateY(0) rotate(-5deg)' }, '50%': { transform: 'translateY(-5px) rotate(5deg)' } },
                        'flicker':  { '0%,90%,100%': { opacity:'1' }, '93%': { opacity:'0.4' }, '96%': { opacity:'0.8' } },
                        'fog':      { '0%': { transform:'translateX(-10px)', opacity:'0.3' }, '100%': { transform:'translateX(10px)', opacity:'0.6' } },
                        'fade-down':{ '0%': { transform:'translateY(-8px)', opacity:'0' }, '100%': { transform:'translateY(0)', opacity:'1' } },
                        'slide-in': { '0%': { transform:'translateX(-100%)' }, '100%': { transform:'translateX(0)' } },
                    },
                    animation: {
                        'bat-fly':   'bat-fly 2s ease-in-out infinite',
                        'flicker':   'flicker 4s infinite',
                        'fog':       'fog 6s ease-in-out infinite alternate',
                        'fade-down': 'fade-down 0.2s ease-out',
                        'slide-in':  'slide-in 0.3s ease-out',
                    },
                }
            }
        }
    </script>

    <style>
        *,*::before,*::after{box-sizing:border-box;}

        html,body{
            margin:0;padding:0;min-height:100vh;
            background-color:#0a0512;
            color:#e8e0d5;
            font-family:'Inter',sans-serif;
            overflow-x:hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar{width:5px;}
        ::-webkit-scrollbar-track{background:#120820;}
        ::-webkit-scrollbar-thumb{background:#6b21a8;border-radius:9999px;}
        ::-webkit-scrollbar-thumb:hover{background:#9333ea;}

        /* ── Sidebar ─────────────────────────────────── */
        #sidebar{
            width:260px;min-height:100vh;
            position:fixed;top:0;left:0;z-index:50;
            background:linear-gradient(180deg,#130922 0%,#0a0512 50%,#130922 100%);
            border-right:1px solid #2d1b4e;
            display:flex;flex-direction:column;
            transition:transform .3s cubic-bezier(.4,0,.2,1);
        }
        #main-content{
            margin-left:260px;min-height:100vh;
            display:flex;flex-direction:column;
            transition:margin-left .3s cubic-bezier(.4,0,.2,1);
        }

        /* ── Nav links ───────────────────────────────── */
        .nav-link{
            display:flex;align-items:center;gap:10px;
            padding:10px 16px;margin:2px 8px;
            border-radius:12px;border:1px solid transparent;
            color:#9d8fba;font-size:.875rem;font-weight:500;
            text-decoration:none;position:relative;
            transition:all .2s ease;overflow:hidden;
        }
        .nav-link::before{
            content:'';position:absolute;inset:0;
            background:linear-gradient(90deg,transparent,rgba(168,85,247,.07),transparent);
            transform:translateX(-100%);transition:transform .4s ease;
        }
        .nav-link:hover{color:#c084fc;background:rgba(107,33,168,.18);border-color:rgba(107,33,168,.28);}
        .nav-link:hover::before{transform:translateX(100%);}
        .nav-link:hover .bat-icon{animation:bat-fly .8s ease-in-out;}

        .nav-link.active{
            color:#ff6b00;
            background:linear-gradient(90deg,rgba(255,107,0,.14),rgba(255,107,0,.04));
            border-color:rgba(255,107,0,.28);
            box-shadow:0 0 14px rgba(255,107,0,.18);
        }
        .nav-link.active::after{
            content:'';position:absolute;left:0;top:20%;bottom:20%;
            width:3px;background:linear-gradient(180deg,#ff6b00,#ff8c38);
            border-radius:0 4px 4px 0;box-shadow:0 0 8px rgba(255,107,0,.6);
        }
        .nav-link.active .nav-ico{color:#ff6b00;}

        .sidebar-label{
            font-family:'Cinzel',serif;font-size:.6rem;font-weight:600;
            letter-spacing:.12em;text-transform:uppercase;
            color:#2d1b4e;padding:16px 24px 6px;
        }

        /* ── Topbar ──────────────────────────────────── */
        #topbar{
            position:sticky;top:0;z-index:40;
            background:rgba(10,5,18,.88);
            backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);
            border-bottom:1px solid #2d1b4e;
            padding:0 24px;height:64px;
            display:flex;align-items:center;justify-content:space-between;
        }

        /* ── Cards ───────────────────────────────────── */
        .castle-card{
            background:linear-gradient(135deg,#1a0f2e 0%,#120820 100%);
            border:1px solid #2d1b4e;border-radius:1rem;
            box-shadow:0 4px 24px rgba(0,0,0,.5),0 1px 0 rgba(192,132,252,.07);
        }

        /* ── Alerts ──────────────────────────────────── */
        .alert-success{background:rgba(57,255,20,.08);border:1px solid rgba(57,255,20,.3);color:#39ff14;border-radius:12px;padding:12px 16px;}
        .alert-error  {background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.35);color:#fca5a5;border-radius:12px;padding:12px 16px;}
        .alert-warning{background:rgba(255,107,0,.08);border:1px solid rgba(255,107,0,.35);color:#ff8c38;border-radius:12px;padding:12px 16px;}
        .alert-info   {background:rgba(168,85,247,.08);border:1px solid rgba(168,85,247,.3);color:#c084fc;border-radius:12px;padding:12px 16px;}

        /* ── Buttons ─────────────────────────────────── */
        .btn-pumpkin{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;background:linear-gradient(135deg,#ff6b00,#e05a00);color:#fff;font-size:.875rem;font-weight:600;border:none;border-radius:10px;cursor:pointer;transition:all .2s ease;text-decoration:none;}
        .btn-pumpkin:hover{background:linear-gradient(135deg,#ff8c38,#ff6b00);box-shadow:0 0 20px rgba(255,107,0,.5);transform:translateY(-1px);}

        .btn-neon{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;background:linear-gradient(135deg,#22cc0d,#39ff14);color:#0a0512;font-size:.875rem;font-weight:700;border:none;border-radius:10px;cursor:pointer;transition:all .2s ease;text-decoration:none;}
        .btn-neon:hover{box-shadow:0 0 20px rgba(57,255,20,.5);transform:translateY(-1px);}

        .btn-ghost{display:inline-flex;align-items:center;gap:8px;padding:8px 18px;background:transparent;color:#9d8fba;font-size:.875rem;font-weight:500;border:1px solid #2d1b4e;border-radius:10px;cursor:pointer;transition:all .2s ease;text-decoration:none;}
        .btn-ghost:hover{border-color:#6b21a8;color:#c084fc;background:rgba(107,33,168,.1);}

        .btn-blood{display:inline-flex;align-items:center;gap:8px;padding:8px 18px;background:rgba(220,38,38,.12);color:#fca5a5;font-size:.875rem;font-weight:500;border:1px solid rgba(220,38,38,.3);border-radius:10px;cursor:pointer;transition:all .2s ease;text-decoration:none;}
        .btn-blood:hover{background:rgba(220,38,38,.22);border-color:rgba(220,38,38,.5);color:#fff;box-shadow:0 0 12px rgba(220,38,38,.3);}

        /* ── Table ───────────────────────────────────── */
        .castle-table{width:100%;border-collapse:collapse;}
        .castle-table thead tr{background:linear-gradient(90deg,rgba(107,33,168,.18),rgba(107,33,168,.04));border-bottom:1px solid #2d1b4e;}
        .castle-table thead th{padding:12px 16px;font-family:'Cinzel',serif;font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:#9d8fba;text-align:left;}
        .castle-table tbody tr{border-bottom:1px solid rgba(45,27,78,.4);transition:background .15s ease;}
        .castle-table tbody tr:hover{background:rgba(107,33,168,.08);}
        .castle-table tbody td{padding:12px 16px;font-size:.875rem;color:#c4b5d6;}

        /* ── Forms ───────────────────────────────────── */
        .castle-input{width:100%;padding:10px 14px;background:rgba(10,5,18,.8);border:1px solid #2d1b4e;border-radius:10px;color:#e8e0d5;font-family:'Inter',sans-serif;font-size:.875rem;outline:none;transition:border-color .2s,box-shadow .2s;}
        .castle-input:focus{border-color:#7c22d6;box-shadow:0 0 0 3px rgba(124,34,214,.15);}
        .castle-input::placeholder{color:#3d2a5e;}
        .castle-select{width:100%;padding:10px 14px;background:rgba(10,5,18,.8) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%239d8fba'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") no-repeat right 12px center/20px;border:1px solid #2d1b4e;border-radius:10px;color:#e8e0d5;font-family:'Inter',sans-serif;font-size:.875rem;outline:none;cursor:pointer;appearance:none;padding-right:40px;transition:border-color .2s;}
        .castle-select:focus{border-color:#7c22d6;}
        .form-label{display:block;font-family:'Cinzel',serif;font-size:.68rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#9d8fba;margin-bottom:6px;}

        /* ── Badges ──────────────────────────────────── */
        .badge{display:inline-flex;align-items:center;gap:3px;padding:3px 10px;border-radius:9999px;font-size:.68rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;}
        .badge-pending   {background:rgba(107,33,168,.2);color:#c084fc;border:1px solid rgba(107,33,168,.3);}
        .badge-confirmed {background:rgba(59,130,246,.15);color:#93c5fd;border:1px solid rgba(59,130,246,.3);}
        .badge-checkin   {background:rgba(255,107,0,.15);color:#ff8c38;border:1px solid rgba(255,107,0,.3);}
        .badge-checkout  {background:rgba(57,255,20,.1);color:#39ff14;border:1px solid rgba(57,255,20,.25);}
        .badge-cancelled {background:rgba(220,38,38,.12);color:#fca5a5;border:1px solid rgba(220,38,38,.3);}
        .badge-tersedia  {background:rgba(57,255,20,.1);color:#39ff14;border:1px solid rgba(57,255,20,.25);}
        .badge-ditempati {background:rgba(255,107,0,.15);color:#ff8c38;border:1px solid rgba(255,107,0,.3);}
        .badge-maintenance{background:rgba(234,179,8,.12);color:#fde047;border:1px solid rgba(234,179,8,.3);}
        .badge-admin     {background:rgba(168,85,247,.2);color:#d8b4fe;border:1px solid rgba(168,85,247,.35);}
        .badge-petugas   {background:rgba(45,27,78,.8);color:#9d8fba;border:1px solid #2d1b4e;}

        /* ── Notif badge ─────────────────────────────── */
        .notif-dot{position:absolute;top:-4px;right:-4px;width:16px;height:16px;background:#ff6b00;border-radius:9999px;font-size:.6rem;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 0 8px rgba(255,107,0,.5);}

        /* ── Cobweb SVG ──────────────────────────────── */
        .cobweb{position:absolute;pointer-events:none;opacity:.2;}

        /* ── Mobile ──────────────────────────────────── */
        #sidebar-overlay{display:none;position:fixed;inset:0;z-index:40;background:rgba(0,0,0,.6);backdrop-filter:blur(3px);}

        @media(max-width:1024px){
            #sidebar{transform:translateX(-100%);}
            #sidebar.open{transform:translateX(0);box-shadow:4px 0 40px rgba(0,0,0,.8);}
            #main-content{margin-left:0;}
            #sidebar-overlay.open{display:block;}
        }

        @media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important;}}
    </style>

    @stack('styles')
</head>

<body>

{{-- ═══════════════════════════════════════════════════════
     SIDEBAR — Kastil Transylvania
═══════════════════════════════════════════════════════ --}}
<aside id="sidebar" role="navigation" aria-label="Navigasi Utama">

    {{-- Cobweb ornamen atas kiri (signature element) --}}
    <svg class="cobweb" style="top:0;left:0;width:88px;height:88px" viewBox="0 0 88 88" fill="none">
        <line x1="0" y1="0" x2="88" y2="88" stroke="#c084fc" stroke-width=".7"/>
        <line x1="0" y1="0" x2="0"  y2="88" stroke="#c084fc" stroke-width=".7"/>
        <line x1="0" y1="0" x2="88" y2="0"  stroke="#c084fc" stroke-width=".7"/>
        <line x1="0" y1="0" x2="60" y2="88" stroke="#c084fc" stroke-width=".4"/>
        <line x1="0" y1="0" x2="88" y2="60" stroke="#c084fc" stroke-width=".4"/>
        <path d="M0 18 Q9 9 18 0"  stroke="#c084fc" stroke-width=".5" fill="none"/>
        <path d="M0 36 Q18 18 36 0" stroke="#c084fc" stroke-width=".5" fill="none"/>
        <path d="M0 54 Q27 27 54 0" stroke="#c084fc" stroke-width=".5" fill="none"/>
        <path d="M0 72 Q36 36 72 0" stroke="#c084fc" stroke-width=".5" fill="none"/>
        <circle cx="0" cy="0" r="2" fill="#c084fc" opacity=".5"/>
    </svg>

    {{-- ── Brand ──────────────────────────────────────────────── --}}
    <div class="relative z-10 px-5 py-5 border-b border-[#2d1b4e]">
        <div class="flex items-center gap-3">
            {{-- Castle icon --}}
            <div class="relative flex-shrink-0">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" class="animate-flicker">
                    <rect x="2"  y="11" width="8"  height="25" fill="#6b21a8" rx="1.5"/>
                    <rect x="30" y="11" width="8"  height="25" fill="#6b21a8" rx="1.5"/>
                    <rect x="10" y="17" width="20" height="19" fill="#7c22d6" rx="1"/>
                    <rect x="2"  y="7"  width="3" height="5" fill="#9333ea" rx="1"/>
                    <rect x="6"  y="7"  width="3" height="5" fill="#9333ea" rx="1"/>
                    <rect x="30" y="7"  width="3" height="5" fill="#9333ea" rx="1"/>
                    <rect x="34" y="7"  width="3" height="5" fill="#9333ea" rx="1"/>
                    <rect x="11" y="14" width="3" height="4" fill="#9333ea" rx="1"/>
                    <rect x="16" y="14" width="3" height="4" fill="#9333ea" rx="1"/>
                    <rect x="21" y="14" width="3" height="4" fill="#9333ea" rx="1"/>
                    <rect x="26" y="14" width="3" height="4" fill="#9333ea" rx="1"/>
                    <rect x="17" y="30" width="6" height="6"  fill="#ff6b00" rx="1" opacity=".85"/>
                    <rect x="12" y="21" width="4" height="4"  fill="#ff6b00" rx=".5" opacity=".9"/>
                    <rect x="24" y="21" width="4" height="4"  fill="#ff6b00" rx=".5" opacity=".9"/>
                    <circle cx="32" cy="7" r="4" fill="#c084fc" opacity=".5"/>
                    <path d="M19 4.5Q17 2 15 4.5Q17 5.5 19 4.5ZM19 4.5Q21 2 23 4.5Q21 5.5 19 4.5Z" fill="#0a0512"/>
                </svg>
            </div>
            <div>
                <h1 class="font-creepster text-[1.4rem] leading-none" style="color:#ff6b00;text-shadow:0 0 14px rgba(255,107,0,.5);">Hotel</h1>
                <h1 class="font-creepster text-[1.4rem] leading-none" style="color:#c084fc;text-shadow:0 0 14px rgba(192,132,252,.4);">Transylvania</h1>
            </div>
        </div>
        <p class="mt-1.5 text-[.58rem] font-cinzel tracking-[.14em] text-[#3d2a5e] uppercase pl-[52px]">
            ✦ Sistem Reservasi ✦
        </p>
    </div>

    {{-- ── Navigation Links ────────────────────────────────────── --}}
    <nav class="relative z-10 flex-1 overflow-y-auto py-2">

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="bat-icon text-sm opacity-0 w-0 overflow-hidden transition-all duration-200"
                  style="{{ request()->routeIs('dashboard') ? 'opacity:1;width:1.2rem' : '' }}">🦇</span>
            <i data-lucide="layout-dashboard" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('dashboard') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Dasbor</span>
        </a>

        <p class="sidebar-label">Operasional</p>

        <a href="{{ route('booking.index') }}"
           class="nav-link {{ request()->routeIs('booking.*') ? 'active' : '' }}">
            <i data-lucide="calendar-check" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('booking.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Booking</span>
            @php $pendingCount = \App\Models\Booking::where('status','pending')->count() ?? 0; @endphp
            @if($pendingCount > 0)
                <span class="ml-auto text-[.65rem] font-bold px-2 py-0.5 rounded-full"
                      style="background:rgba(255,107,0,.2);color:#ff8c38;border:1px solid rgba(255,107,0,.3);">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('checkin.index') }}"
           class="nav-link {{ request()->routeIs('checkin.*') ? 'active' : '' }}">
            <i data-lucide="log-in" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('checkin.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Check-In</span>
        </a>

        <a href="{{ route('checkout.index') }}"
           class="nav-link {{ request()->routeIs('checkout.*') ? 'active' : '' }}">
            <i data-lucide="log-out" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('checkout.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Check-Out</span>
        </a>

        <p class="sidebar-label">Master Data</p>

        <a href="{{ route('tamu.index') }}"
           class="nav-link {{ request()->routeIs('tamu.*') ? 'active' : '' }}">
            <i data-lucide="users" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('tamu.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Data Tamu</span>
        </a>

        @auth
        @if(auth()->user()->isAdmin())
        <a href="{{ route('kamar.index') }}"
           class="nav-link {{ request()->routeIs('kamar.*') ? 'active' : '' }}">
            <i data-lucide="door-closed" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('kamar.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Kamar</span>
        </a>

        <a href="{{ route('tipe-kamar.index') }}"
           class="nav-link {{ request()->routeIs('tipe-kamar.*') ? 'active' : '' }}">
            <i data-lucide="tag" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('tipe-kamar.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Tipe Kamar</span>
        </a>
        @endif
        @endauth

        <p class="sidebar-label">Pelaporan</p>

        <a href="{{ route('laporan.index') }}"
           class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i data-lucide="bar-chart-2" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('laporan.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Laporan</span>
        </a>

        @auth
        @if(auth()->user()->isAdmin())
        <a href="{{ route('activity-log.index') }}"
           class="nav-link {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">
            <i data-lucide="scroll-text" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('activity-log.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Activity Log</span>
        </a>

        <p class="sidebar-label">Pengaturan</p>

        <a href="{{ route('users.index') }}"
           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i data-lucide="user-cog" class="nav-ico w-4 h-4 flex-shrink-0"
               style="{{ request()->routeIs('users.*') ? 'color:#ff6b00' : 'color:#9d8fba' }}"></i>
            <span>Manajemen User</span>
        </a>
        @endif
        @endauth
    </nav>

    {{-- ── Sidebar Footer ──────────────────────────────────────── --}}
    @auth
    <div class="relative z-10 border-t border-[#2d1b4e] px-4 py-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold"
                 style="background:linear-gradient(135deg,#6b21a8,#9333ea);color:#e8e0d5;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="overflow-hidden flex-1 min-w-0">
                <p class="text-[.8rem] font-semibold text-bone truncate">{{ auth()->user()->name }}</p>
                <p class="text-[.65rem] text-ghost">
                    {{ auth()->user()->role === 'admin' ? '🧛 Admin' : '🧟 Petugas' }}
                </p>
            </div>
        </div>
    </div>
    @endauth

    {{-- Fog bottom gradient --}}
    <div class="absolute bottom-0 left-0 right-0 h-16 pointer-events-none"
         style="background:linear-gradient(0deg,rgba(10,5,18,.9) 0%,transparent 100%);"></div>
</aside>

{{-- Overlay mobile --}}
<div id="sidebar-overlay" onclick="closeSidebar()" aria-hidden="true"></div>

{{-- ═══════════════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════════ --}}
<div id="main-content">

    {{-- ── TOPBAR ─────────────────────────────────────────────── --}}
    <header id="topbar">

        {{-- Kiri --}}
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 rounded-xl text-ghost hover:text-lavender hover:bg-[#2d1b4e] transition-colors"
                    aria-label="Buka navigasi">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <span class="font-cinzel text-[1rem] font-bold text-lavender tracking-wide">
                @yield('page-title', 'Dasbor')
            </span>
        </div>

        {{-- Kanan --}}
        <div class="flex items-center gap-2">

            @auth
                {{-- Notifikasi --}}
                <div class="relative">
                    <button id="notif-btn" onclick="toggleDropdown('notif-dropdown','user-dropdown')"
                            class="relative p-2 rounded-xl text-ghost hover:text-lavender hover:bg-[#2d1b4e] transition-colors"
                            aria-label="Notifikasi">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="notif-dot">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>

                    {{-- Notif Dropdown --}}
                    <div id="notif-dropdown"
                         class="hidden absolute right-0 top-12 w-80 castle-card z-50 animate-fade-down overflow-hidden"
                         role="menu">
                        <div class="px-4 py-3 border-b border-[#2d1b4e] flex items-center justify-between">
                            <h3 class="font-cinzel text-[.72rem] tracking-wider text-lavender uppercase">
                                🔔 Notifikasi
                            </h3>
                            @if($unreadCount > 0)
                                <span class="text-[.65rem] text-ghost">{{ $unreadCount }} belum dibaca</span>
                            @endif
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            @forelse(auth()->user()->notifications->take(6) as $notif)
                                @php $nd = $notif->data; @endphp
                                <a href="{{ $nd['url'] ?? '#' }}"
                                   class="flex items-start gap-3 px-4 py-3 border-b border-[#1a0f2e] transition-colors hover:bg-[#1a0f2e] {{ is_null($notif->read_at) ? '' : 'opacity-50' }}">
                                    <span class="flex-shrink-0 mt-0.5 text-base">
                                        {{ ($nd['type'] ?? '') === 'booking_confirmed' ? '📋' : '🔔' }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[.8rem] text-bone leading-snug">{{ $nd['message'] ?? '-' }}</p>
                                        <p class="text-[.65rem] text-ghost mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(is_null($notif->read_at))
                                        <span class="mt-1.5 w-2 h-2 rounded-full flex-shrink-0"
                                              style="background:#ff6b00;box-shadow:0 0 6px rgba(255,107,0,.5);"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="px-4 py-8 text-center">
                                    <p class="text-3xl mb-2">👻</p>
                                    <p class="text-sm text-ghost">Sepi seperti kastil kosong…</p>
                                    <p class="text-xs text-[#3d2a5e] mt-1">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="w-px h-6 bg-[#2d1b4e] mx-1"></div>

                {{-- User dropdown --}}
                <div class="relative">
                    <button id="user-btn" onclick="toggleDropdown('user-dropdown','notif-dropdown')"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-[#2d1b4e] transition-colors"
                            aria-label="Menu pengguna">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                             style="background:linear-gradient(135deg,#6b21a8,#a855f7);color:#fff;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-[.8rem] font-semibold text-bone leading-tight">
                                {{ \Illuminate\Support\Str::limit(auth()->user()->name, 14) }}
                            </p>
                            <p class="text-[.65rem] text-ghost leading-tight">
                                {{ auth()->user()->role === 'admin' ? '🧛 Admin' : '🧟 Petugas' }}
                            </p>
                        </div>
                        <i data-lucide="chevron-down" class="w-3 h-3 text-ghost hidden sm:block"></i>
                    </button>

                    <div id="user-dropdown"
                         class="hidden absolute right-0 top-12 w-52 castle-card z-50 animate-fade-down overflow-hidden"
                         role="menu">
                        <div class="px-4 py-3 border-b border-[#2d1b4e]">
                            <p class="text-[.8rem] font-semibold text-bone">{{ auth()->user()->name }}</p>
                            <p class="text-[.7rem] text-ghost">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-[.85rem] text-ghost hover:text-bone hover:bg-[#2d1b4e] transition-colors">
                                <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                            </a>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-[.85rem] text-ghost hover:text-bone hover:bg-[#2d1b4e] transition-colors">
                                <i data-lucide="settings" class="w-4 h-4"></i> Pengaturan
                            </a>
                        </div>
                        <div class="border-t border-[#2d1b4e] py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-[.85rem] text-left transition-colors"
                                        style="color:#fca5a5;"
                                        onmouseover="this.style.background='rgba(220,38,38,.1)'"
                                        onmouseout="this.style.background='transparent'">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    Keluar dari Kastil
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                {{-- Guest buttons --}}
                <a href="{{ route('login') }}" class="btn-ghost text-sm">
                    <i data-lucide="lock" class="w-4 h-4"></i> Login
                </a>
                @if(config('services.google.client_id'))
                <a href="{{ route('auth.google') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all"
                   style="background:rgba(255,255,255,.08);color:#e8e0d5;border:1px solid rgba(255,255,255,.15);"
                   onmouseover="this.style.background='rgba(255,255,255,.14)'"
                   onmouseout="this.style.background='rgba(255,255,255,.08)'">
                    <svg width="16" height="16" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Login Google
                </a>
                @endif
            @endauth
        </div>
    </header>

    {{-- ── Flash Messages ─────────────────────────────────────── --}}
    @if(session()->hasAny(['success','error','warning','info']) || $errors->any())
    <div class="px-6 pt-4 space-y-2">
        @if(session('success'))
        <div class="alert-success flex items-start gap-3 animate-fade-down" role="alert">
            <span class="text-base flex-shrink-0 mt-0.5">✓</span>
            <p class="flex-1 text-sm">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-error flex items-start gap-3 animate-fade-down" role="alert">
            <span class="text-base flex-shrink-0 mt-0.5">⚠</span>
            <p class="flex-1 text-sm">{{ session('error') }}</p>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert-warning flex items-start gap-3 animate-fade-down" role="alert">
            <span class="text-base flex-shrink-0 mt-0.5">!</span>
            <p class="flex-1 text-sm">{{ session('warning') }}</p>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert-info flex items-start gap-3 animate-fade-down" role="alert">
            <span class="text-base flex-shrink-0 mt-0.5">ℹ</span>
            <p class="flex-1 text-sm">{{ session('info') }}</p>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert-error animate-fade-down" role="alert">
            <div class="flex items-center gap-2 mb-1.5">
                <span>⚠</span>
                <span class="text-sm font-semibold flex-1">Perbaiki kesalahan berikut:</span>
                <button onclick="this.closest('[role=alert]').remove()" class="opacity-50 hover:opacity-100 transition-opacity"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
            <ul class="text-sm space-y-1 pl-5 list-disc">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    {{-- ── Page Content ────────────────────────────────────────── --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

    {{-- ── Footer ─────────────────────────────────────────────── --}}
    <footer class="px-6 py-3 border-t border-[#2d1b4e] flex items-center justify-between">
        <p class="text-[.62rem] text-[#3d2a5e]">🦇 Hotel Transylvania © {{ date('Y') }}</p>
        <p class="text-[.62rem] text-[#3d2a5e] font-cinzel tracking-widest">✦ Laravel ✦</p>
    </footer>

</div>{{-- /main-content --}}

<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebar-overlay').classList.toggle('open');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('open');
    }

    function toggleDropdown(showId, hideId) {
        document.getElementById(hideId).classList.add('hidden');
        document.getElementById(showId).classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        ['notif','user'].forEach(function(name) {
            const btn  = document.getElementById(name + '-btn');
            const drop = document.getElementById(name + '-dropdown');
            if (btn && drop && !btn.contains(e.target) && !drop.contains(e.target)) {
                drop.classList.add('hidden');
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
            document.getElementById('notif-dropdown')?.classList.add('hidden');
            document.getElementById('user-dropdown')?.classList.add('hidden');
        }
    });

    // Auto-dismiss alerts setelah 5 detik
    setTimeout(function() {
        document.querySelectorAll('[role="alert"]').forEach(function(el) {
            el.style.transition = 'opacity .5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
