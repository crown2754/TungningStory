<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif" style="background-image: url('{{ asset('images/paper-texture.png') }}'); background-blend-mode: multiply;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8 border-b-4 border-tungning-gold pb-4">
            <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                {{ __('東寧商界') }}
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- [左側] NPC 楊英 互動區 --}}
            <div class="lg:col-span-1 flex flex-col items-center sticky top-4">
                @if($npc)
                {{-- NPC 立繪 --}}
                <div class="relative w-48 h-48 mb-4 group">
                    <div class="absolute inset-0 bg-tungning-gold rounded-full opacity-20 blur-xl group-hover:opacity-40 transition duration-500"></div>
                    <img src="{{ $npc->avatar_url }}"
                        alt="{{ $npc->name }}"
                        class="relative w-full h-full object-cover rounded-full border-4 border-tungning-gold shadow-2xl z-10 hover:scale-105 transition duration-300">
                    <div class="absolute -bottom-2 inset-x-0 text-center z-20">
                        <span class="bg-tungning-wood text-tungning-gold px-3 py-1 rounded-full text-sm font-bold border border-tungning-gold shadow-md">
                            {{ $npc->title }}．{{ $npc->name }}
                        </span>
                    </div>
                </div>

                {{-- 動態對話框 --}}
                <div class="bg-white/90 p-4 rounded-xl border-2 border-tungning-brown shadow-lg relative max-w-xs text-center min-h-[100px] flex items-center justify-center">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-[10px] border-l-transparent border-r-[10px] border-r-transparent border-b-[10px] border-b-tungning-brown"></div>

                    <p class="text-tungning-brown font-bold leading-relaxed typing-effect">
                        @if(!empty($npcSpeech))
                        {{-- 1. NPC 的即時回應 --}}
                        「{{ $npcSpeech }}」
                        @elseif($errors->any())
                        {{-- 2. 表單錯誤 --}}
                        「這商號名稱似乎不合規矩... 煩請大人參閱下方的紅字說明。」
                        @elseif($hasShop)
                        {{-- 3. 已有店鋪 --}}
                        「{{ auth()->user()->name }} 老闆，您的『{{ $shop->name }}』經營得有聲有色啊！記得按時繳稅。」
                        @else
                        {{-- 4. 還沒開店 --}}
                        「這位開拓者，東寧市集寸土寸金。若想在此立足，得先簽下這紙官契，繳清盤讓費才行。」
                        @endif
                    </p>
                </div>
                @else
                <div class="text-tungning-paper/50 italic">（戶部主事 楊英 似乎外出巡查了...）</div>
                @endif
            </div>

            {{-- [右側] 內容區 --}}
            <div class="lg:col-span-2">
                @if($hasShop)
                {{-- 已有店鋪介面 --}}
                <div class="bg-tungning-paper p-8 rounded-lg border-4 border-tungning-wood shadow-2xl text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none select-none">
                        <span class="text-9xl">🛖</span>
                    </div>
                    <div class="mb-4">
                        <span class="text-6xl drop-shadow-md">🛖</span>
                    </div>
                    <h3 class="text-3xl font-black text-tungning-brown mb-2 tracking-widest">{{ $shop->name }}</h3>
                    <p class="text-tungning-wood font-bold mb-6 text-lg">{{ $shop->description }}</p>

                    <div class="badge badge-lg mb-8 p-4 font-bold shadow-md border-2
                    {{ $shop->is_open 
                        ? 'bg-green-800 text-amber-100 border-green-950'  
                        : 'bg-stone-600 text-gray-300 border-stone-800'   
                    }}">
                        {{ $shop->is_open ? '營業中' : '休息中' }}
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                        <button class="btn bg-tungning-brown text-tungning-paper border-2 border-tungning-gold hover:bg-tungning-wood">
                            📦 貨架管理
                        </button>
                        <button class="btn bg-[#e8e0d5] text-tungning-brown border-2 border-tungning-wood hover:bg-white">
                            💰 帳本查看
                        </button>
                    </div>
                </div>
                @else
                {{-- 購買契約 --}}
                <div class="bg-[#e8e0d5] p-8 rounded-sm shadow-[0_0_20px_rgba(0,0,0,0.5)] border border-gray-400 relative">
                    {{-- 浮水印印章 --}}
                    <div class="absolute top-10 right-10 w-24 h-24 border-4 border-red-800 rounded-full flex items-center justify-center opacity-20 transform rotate-12 pointer-events-none">
                        <span class="text-red-800 font-black text-4xl">官契</span>
                    </div>

                    <h3 class="text-center text-3xl font-black text-gray-800 mb-8 border-b border-gray-400 pb-4">
                        商號經營權轉讓契約
                    </h3>

                    <div class="space-y-4 text-lg text-gray-700 leading-relaxed font-medium">
                        <p>
                            立契約人 <span class="font-bold underline decoration-dotted text-tungning-brown">{{ auth()->user()->name }}</span> (以下簡稱乙方)，
                            茲向東寧府 (以下簡稱甲方) 申請開設商號。
                        </p>
                        <p>
                            乙方願支付盤讓費 <span class="font-black text-red-800 text-2xl mx-1">{{ number_format($cost) }}</span> 文通寶，
                            以取得於東寧市集之合法經營權。
                        </p>
                        <p>
                            雙方同意，商號名稱一經核定，不得隨意更改。乙方需恪守商律，誠信經營。
                        </p>
                    </div>

                    <div class="mt-8 p-6 bg-gray-50 rounded border border-gray-300 shadow-inner">
                        <label class="block text-gray-600 font-bold mb-2">請題寫商號名稱：</label>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" wire:model="shopName" placeholder="例：四海商行"
                                class="input input-bordered flex-1 bg-white text-gray-900 border-gray-400 focus:border-tungning-brown font-black text-xl" />

                            <button wire:click="createShop"
                                wire:loading.attr="disabled"
                                class="btn bg-red-900 text-white hover:bg-red-800 border-none shadow-lg px-8 text-lg font-bold flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">

                                {{-- 一般狀態顯示圖示 --}}
                                <svg wire:loading.remove target="createShop" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>

                                {{-- 載入中顯示轉圈圈 --}}
                                <svg wire:loading target="createShop" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>

                                <span wire:loading.remove target="createShop">畫押支付</span>
                                <span wire:loading target="createShop">用印中...</span>
                            </button>
                        </div>
                        @error('shopName') <span class="text-red-600 text-sm mt-2 block font-bold">⚠ {{ $message }}</span> @enderror
                    </div>

                    {{-- [新增] 底部資產顯示區：金錢 + 體力 --}}
                    <div wire:poll.15s class="mt-4 flex flex-col sm:flex-row justify-end items-center gap-2 sm:gap-6 text-sm text-gray-500">
                        <div>
                            您目前擁有：<span class="font-bold text-tungning-brown text-lg">{{ number_format(auth()->user()->gold) }}</span> 文
                        </div>
                        <div class="hidden sm:block text-gray-300">|</div>
                        <div>
                            當前體力：<span class="font-bold text-red-800 text-lg">{{ auth()->user()->stamina }}</span>
                        </div>
                    </div>

                </div>
                @endif
            </div>
        </div>
    </div>
</div>