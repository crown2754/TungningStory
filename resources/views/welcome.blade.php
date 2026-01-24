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
            {{-- 請確保圖片路徑正確 --}}
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
                @livewire('auth-switcher')
            </div>

            <p class="mt-8 text-center text-tungning-gold/80 font-serif text-xs tracking-tighter">
                &copy; 1661 TUNGNING STORY | PROJECT REFORGED
            </p>
        </div>
    </div>
    @livewireScripts
</body>
</html>