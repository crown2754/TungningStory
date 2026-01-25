<div>
    <div class="flex mb-8 justify-center gap-10">
        <button wire:click="toggleForm" class="pb-2 text-xl font-black {{ $showLogin ? 'text-tungning-brown border-b-4 border-tungning-brown' : 'text-gray-400' }}">登入</button>
        <button wire:click="toggleForm" class="pb-2 text-xl font-black {{ !$showLogin ? 'text-tungning-brown border-b-4 border-tungning-brown' : 'text-gray-400' }}">註冊</button>
    </div>

    <form wire:submit.prevent="{{ $showLogin ? 'login' : 'register' }}" class="space-y-5">
        @csrf
        
        @if (!$showLogin)
        <div class="flex flex-col">
            <label class="mb-1 font-bold text-tungning-brown text-sm">開拓者稱號</label>
            <input type="text" wire:model="name" class="input input-bordered w-full bg-white/50 border-tungning-wood/30 text-tungning-brown" required />
        </div>
        @endif

        <div class="flex flex-col">
            <label class="mb-1 font-bold text-tungning-brown text-sm">通行電子郵件</label>
            <input type="email" wire:model="email" class="input input-bordered w-full bg-white/50 border-tungning-wood/30 text-tungning-brown" required />
        </div>

        <div class="flex flex-col">
            <label class="mb-1 font-bold text-tungning-brown text-sm">秘密口令</label>
            <input type="password" wire:model="password" class="input input-bordered w-full bg-white/50 border-tungning-wood/30 text-tungning-brown" required />
        </div>

        <button type="submit" class="btn w-full bg-tungning-brown text-tungning-paper border-none shadow-xl text-lg h-14">
            {{ $showLogin ? '大展鴻圖' : '簽署名冊' }}
        </button>
    </form>
</div>