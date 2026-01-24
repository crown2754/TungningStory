<div>
    <div class="flex mb-8 justify-center gap-10">
        <button wire:click="toggleForm" class="pb-2 text-xl font-black transition-all {{ $showLogin ? 'text-tungning-brown border-b-4 border-tungning-brown' : 'text-gray-400 hover:text-tungning-wood' }}">
            登入
        </button>
        <button wire:click="toggleForm" class="pb-2 text-xl font-black transition-all {{ !$showLogin ? 'text-tungning-brown border-b-4 border-tungning-brown' : 'text-gray-400 hover:text-tungning-wood' }}">
            註冊
        </button>
    </div>

    <form method="POST" action="{{ $showLogin ? route('login') : route('register') }}" class="space-y-5">
        @csrf
        
        @if (!$showLogin)
        <div class="flex flex-col">
            <label class="mb-1 ml-1 font-bold text-tungning-brown text-sm">開拓者稱號</label>
            <input type="text" name="name" class="input input-bordered w-full bg-white/50 border-tungning-wood/30 focus:outline-tungning-gold text-tungning-brown h-12" placeholder="輸入您的名稱" required />
        </div>
        @endif

        <div class="flex flex-col">
            <label class="mb-1 ml-1 font-bold text-tungning-brown text-sm">通行電子郵件</label>
            <input type="email" name="email" class="input input-bordered w-full bg-white/50 border-tungning-wood/30 focus:outline-tungning-gold text-tungning-brown h-12" placeholder="email@example.com" required />
        </div>

        <div class="flex flex-col">
            <label class="mb-1 ml-1 font-bold text-tungning-brown text-sm">秘密口令</label>
            <input type="password" name="password" class="input input-bordered w-full bg-white/50 border-tungning-wood/30 focus:outline-tungning-gold text-tungning-brown h-12" placeholder="********" required />
        </div>

        <div class="pt-4">
            <button type="submit" class="btn w-full bg-tungning-brown hover:bg-black text-tungning-paper border-none shadow-xl text-lg h-14">
                {{ $showLogin ? '大展鴻圖' : '簽署名冊' }}
            </button>
        </div>
        
        <div class="text-center pt-2">
            <a href="#" class="text-xs text-tungning-wood/70 hover:text-tungning-brown hover:underline font-medium">
                忘記密碼?
            </a>
        </div>
    </form>
</div>