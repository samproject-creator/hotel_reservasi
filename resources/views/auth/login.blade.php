<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerbang Masuk Kastil — Hotel Transylvania</title>
    
    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        /* Animasi kelelawar terbang melintasi layar */
        @keyframes bat-fly-1 {
            0% { transform: translate(-10%, 80vh) scale(0.6) rotate(10deg); opacity: 0; }
            5% { opacity: 0.7; }
            90% { opacity: 0.7; }
            100% { transform: translate(110vw, 20vh) scale(1.2) rotate(-10deg); opacity: 0; }
        }
        @keyframes bat-fly-2 {
            0% { transform: translate(110vw, 70vh) scale(0.5) scaleX(-1) rotate(-15deg); opacity: 0; }
            10% { opacity: 0.6; }
            85% { opacity: 0.6; }
            100% { transform: translate(-10%, 10vh) scale(1) scaleX(-1) rotate(15deg); opacity: 0; }
        }

        .bat-1 {
            animation: bat-fly-1 12s linear infinite;
        }
        .bat-2 {
            animation: bat-fly-2 16s linear infinite;
            animation-delay: 4s;
        }
    </style>
</head>
<body class="bg-[#0d0b0f] min-h-screen flex items-center justify-center relative overflow-hidden">

    {{-- ── BACKGROUND IMAGE KASTIL DENGAN OVERLAY GELAP ── --}}
    <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000 scale-105"
         style="background-image: url('{{ asset('images/kastil.jpg') }}');">
         
         <div class="absolute inset-0 bg-gradient-to-tr from-[#0d0b0f] via-[#0d0b0f]/85 to-purple-950/40"></div>
         <div class="absolute inset-0 bg-black/40"></div>
    </div>

    {{-- ── ANIMASI KELELAWAR LEWAT ── --}}
    <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden">
        {{-- Kelelawar 1 (Kiri ke Kanan) --}}
        <div class="bat-1 absolute text-4xl select-none">🦇</div>
        <div class="bat-1 absolute text-2xl select-none" style="animation-delay: 0.5s; margin-top: -40px;">🦇</div>
        
        {{-- Kelelawar 2 (Kanan ke Kiri) --}}
        <div class="bat-2 absolute text-3xl select-none">🦇</div>
    </div>

    {{-- ── CARD LOGIN ── --}}
    <div class="relative z-20 w-full max-w-md mx-4">
        <div class="bg-slate-900/80 backdrop-blur-md border border-purple-900/50 p-8 rounded-xl shadow-2xl space-y-6">
            
            {{-- Header Logo --}}
            <div class="text-center space-y-2">
                <div class="inline-flex p-3 bg-purple-950/60 border border-purple-800/40 rounded-full text-purple-400 mb-1">
                    <i data-lucide="castle" class="w-8 h-8"></i>
                </div>
                <h1 class="text-2xl font-serif font-bold text-purple-100 tracking-wide">Hotel Transylvania</h1>
                <p class="text-xs text-slate-400">Masukkan e-mail dan sandi untuk mengakses sistem</p>
            </div>

            {{-- Form Login --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Input Email --}}
                <div class="space-y-1">
                    <label for="email" class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">User ID (Email)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="dracula@transylvania.com"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-950/80 border border-purple-950 rounded-lg text-slate-200 placeholder-slate-600 text-sm focus:outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 transition-all">
                    </div>
                    @error('email')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Password --}}
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Password</label>
                        <a href="#" class="text-xs text-purple-400 hover:underline">Lupa Mantra?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-950/80 border border-purple-950 rounded-lg text-slate-200 placeholder-slate-600 text-sm focus:outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 transition-all">
                    </div>
                    @error('password')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ingat Saya / Remember Me --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer select-none text-xs text-slate-400">
                        <input type="checkbox" name="remember" class="rounded bg-slate-950 border-purple-950 text-purple-600 focus:ring-0 focus:ring-offset-0 mr-2">
                        Simpan Info Login
                    </label>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" 
                        class="w-full py-3 bg-gradient-to-r from-purple-800 to-indigo-900 hover:from-purple-700 hover:to-indigo-800 text-purple-100 text-sm font-semibold rounded-lg shadow-md hover:shadow-purple-900/30 transition-all flex items-center justify-center gap-2 border border-purple-700/50">
                    <i data-lucide="key-round" class="w-4 h-4"></i> LOGIN
                </button>
            </form>

            {{-- Footer Info --}}
            <div class="pt-2 border-t border-purple-950/40 text-center">
                <p class="text-[11px] text-slate-500">
                    Hanya untuk petugas resmi Hotel Transylvania.
                </p>
            </div>

        </div>
    </div>

    <script>
        // Inisialisasi ikon Lucide
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>
</html>