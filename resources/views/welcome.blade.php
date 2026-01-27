<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>東寧物語 - Tungning Story</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+TC:wght@700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#2d1b0e] antialiased">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
        
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/logo-tungning-bg.png') }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
        </div>

        <div class="relative z-10 w-full max-w-lg px-6">
            
            <div class="mb-10 text-center p-6 rounded shadow-2xl border-b-8 border-r-8 border-tungning-brown bg-tungning-paper/90 ring-4 ring-tungning-wood">
                <h1 class="text-5xl font-black text-tungning-brown tracking-[0.3em]" style="font-family: 'Noto Serif TC', serif;">
                    東寧物語
                </h1>
                <div class="h-[2px] w-3/4 mx-auto bg-tungning-wood my-3 opacity-50"></div>
                <p class="text-tungning-wood font-bold tracking-widest text-sm">
                    大航海時代 ‧ 成為自己的商業傳奇
                </p>
            </div>

            <div class="bg-tungning-paper/95 rounded-sm shadow-[0_35px_60px_-15px_rgba(0,0,0,0.6)] border-t-4 border-tungning-gold p-8">
                
                @auth
                    {{-- [已登入狀態] 顯示玩家頭像與進入按鈕 --}}
                    <div class="text-center space-y-6">
                        
                        <div class="relative inline-block group cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-tungning-gold to-tungning-wood rounded-full blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                            <img src="{{ auth()->user()->avatar_url }}" 
                                 class="relative w-32 h-32 rounded-full border-4 border-[#2d1b0e] shadow-2xl object-cover mx-auto" 
                                 alt="Avatar">
                            
                            <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-600 border-4 border-tungning-paper rounded-full"></div>
                        </div>

                        <div>
                            <h2 class="text-2xl font-black text-tungning-brown mb-1 tracking-widest">
                                歡迎歸來，{{ auth()->user()->name }}
                            </h2>
                            <p class="text-tungning-wood font-bold text-sm opacity-80">
                                身份：{{ auth()->user()->role === 'Player' ? '開拓者' : '管理者' }} ｜ 職業：{{ auth()->user()->job }}
                            </p>
                        </div>

                        <a href="{{ route('dashboard') }}" class="btn w-full bg-tungning-brown text-tungning-paper border-none shadow-xl text-lg h-14 hover:bg-[#3d2311] hover:scale-[1.02] transition-transform flex items-center justify-center gap-2 group">
                            <span>進入府邸</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="text-xs text-gray-500 hover:text-red-700 underline underline-offset-4">
                                {{ __('這不是我，登出並切換帳號') }}
                            </button>
                        </form>
                    </div>

                @else
                    {{-- [未登入狀態] 顯示原本的登入/註冊切換器 --}}
                    @livewire('auth-switcher')
                @endauth

            </div>

            <p class="mt-8 text-center text-tungning-gold/80 font-serif text-xs tracking-tighter">
                &copy; 1661 TUNGNING STORY | PROJECT REFORGED
            </p>
        </div>
    </div>
    @livewireScripts
</body>
</html>