<div>
    <div class="flex mb-8 justify-center gap-10">
        <button wire:click="toggleForm" class="pb-2 text-xl font-black {{ $showLogin ? 'text-tungning-brown border-b-4 border-tungning-brown' : 'text-gray-400' }}">登入</button>
        <button wire:click="toggleForm" class="pb-2 text-xl font-black {{ !$showLogin ? 'text-tungning-brown border-b-4 border-tungning-brown' : 'text-gray-400' }}">註冊</button>
    </div>

    @if ($errors->any())
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="mb-6">
        <div role="alert" class="alert bg-red-100 border-l-4 border-red-800 text-red-900 shadow-md rounded-md flex items-start gap-3 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 text-red-700" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <div class="flex flex-col text-left">
                <span class="font-bold text-sm">通關失敗</span>
                <span class="text-xs mt-1">{{ $errors->first() }}</span>
            </div>
        </div>
    </div>
    @endif

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