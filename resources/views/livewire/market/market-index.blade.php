<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif relative" style="background-image: url('https://www.transparenttextures.com/patterns/paper.png');">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- 頂部資訊列 --}}
        <div class="flex items-center justify-between mb-8 border-b-4 border-tungning-gold pb-4">
            <div>
                <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                    @if($activeMerchant)
                    @if($activeMerchant == 'qing') 🏮 水仔阿慶的雜貨鋪 @else 🏮 郭老爹的糧行 @endif
                    @else
                    🏮 承天府・大井頭市集
                    @endif
                </h2>
                <p class="text-tungning-gold/70 mt-1 text-sm">
                    @if($activeMerchant)
                    <button wire:click="leaveShop" class="hover:text-white underline">⬅ 返回市集廣場</button>
                    @else
                    「凡番船、商船抵台，必泊於大井頭，百貨雲集。」
                    @endif
                </p>
            </div>

            {{-- 資源顯示 --}}
            <div class="flex gap-4">
                <div class="bg-black/40 backdrop-blur-sm border-2 border-tungning-gold rounded-lg px-4 py-2 flex items-center gap-2 text-white">
                    <span>體力</span>
                    <span class="{{ auth()->user()->stamina < 15 ? 'text-red-500 animate-pulse' : '' }}">
                        {{ auth()->user()->stamina }} / {{auth()->user()->max_stamina}}
                    </span>
                </div>
                <div class="bg-black/40 backdrop-blur-sm border-2 border-tungning-gold rounded-lg px-4 py-2 flex items-center gap-2 text-tungning-gold">
                    {{ number_format(auth()->user()->gold) }} <span>文</span>
                </div>
            </div>
        </div>

        {{-- 場景一：市集廣場 (選擇 NPC) --}}
        @if(!$activeMerchant)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">

            {{-- NPC 1 --}}
            <div wire:click="enterShop('qing')" class="cursor-pointer group relative bg-[#e8e0d5] border-4 border-tungning-wood rounded-xl p-6 shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="flex items-center gap-6">
                    <div class="w-32 h-32 border-4 border-tungning-gold rounded-full overflow-hidden shadow-inner bg-gray-300">
                        @if($npcQing && $npcQing->avatar)
                        <img src="{{ $npcQing->avatar->path }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">🧑‍🏭</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black text-tungning-brown mb-2">水仔阿慶</h3>
                        <div class="text-gray-700">專營：茶葉、瓷器、水槽</div>
                    </div>
                </div>
                <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-tungning-brown font-bold animate-bounce">
                    點擊進店 ➡
                </div>
            </div>

            {{-- NPC 2 --}}
            <div wire:click="enterShop('guo')" class="cursor-pointer group relative bg-[#e8e0d5] border-4 border-tungning-wood rounded-xl p-6 shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="flex items-center gap-6">
                    <div class="w-32 h-32 border-4 border-tungning-gold rounded-full overflow-hidden shadow-inner bg-gray-300">
                        @if($npcGuo && $npcGuo->avatar)
                        <img src="{{ $npcGuo->avatar->path }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">👴</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black text-tungning-brown mb-2">郭老爹</h3>
                        <div class="text-gray-700">專營：稻米、臘肉、白酒</div>
                    </div>
                </div>
                <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-tungning-brown font-bold animate-bounce">
                    點擊進店 ➡
                </div>
            </div>

        </div>
        @endif

        {{-- 場景二：商店內部 --}}
        @if($activeMerchant)
        <div class="animate-fade-in-up">

            {{-- NPC 櫃台互動區 --}}
            <div class="bg-[#f3f0eb] border-4 border-tungning-brown rounded-xl p-6 mb-8 shadow-xl relative overflow-hidden">
                <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">

                    {{-- NPC 頭像 (修改處：加上 cursor-pointer 與 wire:click) --}}
                    <div wire:click="talkToNpc"
                        class="w-24 h-24 md:w-32 md:h-32 flex-shrink-0 border-4 border-tungning-gold rounded-full overflow-hidden shadow-md bg-gray-300 cursor-pointer hover:scale-105 transition-transform active:scale-95 group relative">

                        {{-- 加上一個小的提示圖示 --}}
                        <div class="absolute bottom-0 right-0 bg-white/80 text-xs px-1 rounded-tl opacity-0 group-hover:opacity-100 transition-opacity font-bold text-tungning-brown">
                            💬 攀談
                        </div>

                        @php $currentNpc = ($activeMerchant == 'qing') ? $npcQing : $npcGuo; @endphp
                        @if($currentNpc && $currentNpc->avatar)
                        <img src="{{ $currentNpc->avatar->path }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">👤</div>
                        @endif
                    </div>

                    {{-- 對話氣泡區 --}}
                    <div class="flex-1 w-full">
                        <div class="bg-white border-2 border-gray-300 rounded-2xl p-5 relative shadow-sm transition-all duration-200"
                            key="{{ $bubbleShake }}"
                            :class="{ 'animate-shake border-red-400 bg-red-50': {{ $bubbleShake }} > 0 }">

                            {{-- 氣泡箭頭 --}}
                            <div class="absolute top-0 md:top-1/2 left-1/2 md:left-0 -mt-2 md:-mt-3 -ml-3 md:-ml-2 w-4 h-4 bg-inherit border-t border-l border-inherit transform rotate-45 md:-rotate-45"></div>

                            <h4 class="font-bold text-tungning-brown text-lg mb-1">
                                {{ $activeMerchant == 'qing' ? '水仔阿慶' : '郭老爹' }}
                            </h4>

                            <p class="text-gray-800 text-lg leading-relaxed font-bold">
                                {{ $greetingMessage }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 背景裝飾紋理 --}}
                <div class="absolute top-0 right-0 w-64 h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/wood-pattern.png')]"></div>
            </div>

            {{-- 商品區與購物車 --}}
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- 左側：商品區 --}}
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($this->shopItems as $item)
                    <div class="bg-[#e8e0d5] border-2 border-tungning-wood rounded-lg p-4 shadow-lg flex flex-col justify-between hover:border-tungning-gold transition-colors relative group">

                        {{-- 民間庫存標示（阿慶/郭老爹手上的貨，非官府） --}}
                        <div class="absolute top-2 right-2 text-xs font-bold text-gray-500">
                            存貨: {{ $item->civilian_stock }}
                        </div>

                        <div class="flex gap-4">
                            {{-- 物品圖片 --}}
                            <div class="w-24 h-24 bg-gray-200 border border-gray-400 rounded-lg flex items-center justify-center overflow-hidden shadow-inner shrink-0 relative">
                                @if($item->image_path)
                                <img src="{{ asset($item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                {{-- 沒圖片時的 Fallback Emoji (保留原本邏輯作為備案) --}}
                                <div class="text-4xl">
                                    @if(str_contains($item->name, '茶')) 🍵
                                    @elseif(str_contains($item->name, '米')) 🍚
                                    @elseif(str_contains($item->name, '酒')) 🍶
                                    @elseif(str_contains($item->name, '甕')) 🏺
                                    @elseif(str_contains($item->name, '肉')) 🥓
                                    @elseif(str_contains($item->name, '木')) 🪵
                                    @else 📦
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-black text-xl text-gray-800">{{ $item->name }}</h4>
                                <div class="text-lg font-bold text-red-800 mt-2">
                                    {{ number_format($item->current_price) }} 文/ {{ $item->unit }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-3 bg-white/50 p-2 rounded">
                            <button wire:click="decrement({{ $item->id }})" class="btn btn-xs btn-circle bg-gray-300 border-none hover:bg-gray-400 text-lg font-bold">-</button>
                            <input
                                type="number"
                                wire:model.lazy="cart.{{ $item->id }}"
                                min="0"
                                max="{{ $item->civilian_stock }}"
                                class="input input-xs w-16 text-center bg-white border-gray-300 font-bold"
                            >
                            <button wire:click="increment({{ $item->id }})" class="btn btn-xs btn-circle bg-tungning-gold border-none hover:bg-yellow-500 text-lg font-bold text-tungning-brown">+</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- 右側：結帳面板 --}}
                <div class="w-full lg:w-80">
                    <div class="sticky top-4 bg-[#3d2311] text-tungning-paper border-4 border-tungning-gold rounded-xl p-6 shadow-2xl">
                        <h3 class="text-xl font-bold mb-4 border-b border-tungning-gold/30 pb-2 text-center">
                            📜 採購清單
                        </h3>
                        @if($summary['totalItems'] > 0)
                        <div class="space-y-2 mb-4 max-h-60 overflow-y-auto pr-2">
                            @foreach($summary['details'] as $detail)
                            <div class="flex justify-between text-sm">
                                <span>{{ $detail['item']->name }} x {{ $detail['qty'] }}</span>
                                <span class="text-tungning-gold">{{ number_format($detail['subtotal']) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="border-t border-tungning-gold/30 pt-4 space-y-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>商品總計</span>
                                <span class="text-tungning-gold">{{ number_format($summary['totalGold']) }} 文</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-400">
                                <span>預估體力消耗</span>
                                <span class="text-red-400">-{{ $summary['staminaCost'] }} 體力</span>
                            </div>
                        </div>
                        <button wire:click="previewCheckout" class="btn bg-tungning-gold text-tungning-brown w-full mt-6 border-none hover:bg-yellow-500 font-bold shadow-lg text-lg">
                            查看單據 & 結帳
                        </button>
                        @else
                        <div class="text-center text-gray-500 py-8 italic">
                            請點選左側商品<br>加入購物籃
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- 單據確認 Modal (Receipt) --}}
    @if($showReceipt)
    <div class="fixed inset-0 z-[70] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">

        <div class="bg-[#f3f0eb] w-full max-w-md relative shadow-2xl transform rotate-1 border border-gray-300"
            style="background-image: url('https://www.transparenttextures.com/patterns/paper.png'); box-shadow: 10px 10px 30px rgba(0,0,0,0.5);">

            <div class="absolute -top-2 left-0 w-full h-4 bg-[length:20px_20px] bg-repeat-x"
                style="background-image: radial-gradient(circle, transparent 50%, #f3f0eb 50%); background-position: -10px -10px;"></div>

            <div class="p-8 text-tungning-brown font-serif">
                <div class="text-center border-b-2 border-dashed border-gray-400 pb-4 mb-4">
                    <h2 class="text-3xl font-black tracking-[0.2em] mb-1">交易單據</h2>
                    <p class="text-sm text-gray-600">
                        {{ $activeMerchant == 'qing' ? '水仔阿慶雜貨鋪' : '郭老爹糧行' }} / 承天府
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('Y-m-d H:i') }}</p>
                </div>

                <table class="w-full text-left mb-4">
                    <thead class="text-sm text-gray-500 border-b border-gray-300">
                        <tr>
                            <th class="pb-1">品名</th>
                            <th class="pb-1 text-right">數量</th>
                            <th class="pb-1 text-right">小計</th>
                        </tr>
                    </thead>
                    <tbody class="text-lg">
                        @foreach($summary['details'] as $detail)
                        <tr>
                            <td class="py-1">{{ $detail['item']->name }}</td>
                            <td class="py-1 text-right">x{{ $detail['qty'] }}</td>
                            <td class="py-1 text-right font-bold">{{ number_format($detail['subtotal']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="border-t-2 border-gray-800 pt-2 mb-6">
                    <div class="flex justify-between text-xl font-black">
                        <span>總金額</span>
                        <span>{{ number_format($summary['totalGold']) }} 文</span>
                    </div>
                    <div class="flex justify-between text-sm text-red-600 mt-1 font-bold">
                        <span>體力消耗</span>
                        <span>-{{ $summary['staminaCost'] }}</span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button wire:click="$set('showReceipt', false)" class="flex-1 btn btn-outline btn-sm border-gray-400 text-gray-600 hover:bg-gray-200 hover:border-gray-400">
                        取消
                    </button>
                    <button wire:click="confirmCheckout" class="flex-1 btn btn-sm bg-red-800 text-white hover:bg-red-900 border-none shadow relative overflow-hidden group">
                        <span class="relative z-10">蓋章確認 (結帳)</span>
                        <div class="absolute inset-0 bg-red-700 transform scale-0 group-hover:scale-150 transition-transform duration-300 rounded-full opacity-50"></div>
                    </button>
                </div>

                <div class="absolute bottom-16 right-8 opacity-20 transform -rotate-12 pointer-events-none">
                    <div class="w-24 h-24 border-4 border-red-800 rounded-full flex items-center justify-center">
                        <span class="text-red-800 font-black text-xl border-2 border-red-800 p-1">貨銀兩訖</span>
                    </div>
                </div>
            </div>

            <div class="absolute -bottom-2 left-0 w-full h-4 bg-[length:20px_20px] bg-repeat-x transform rotate-180"
                style="background-image: radial-gradient(circle, transparent 50%, #f3f0eb 50%); background-position: -10px -10px;"></div>
        </div>
    </div>
    @endif

    {{-- 加入一個 CSS 動畫讓氣泡搖晃 (模擬錯誤提示) --}}
    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .animate-shake {
            animation: shake 0.3s ease-in-out;
        }
    </style>

    {{-- Toast (成功時顯示) --}}
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:operation-success.window="message = $event.detail.message; type='success'; show = true; setTimeout(() => show = false, 3000)"
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2">

        <div class="alert shadow-lg border-2 flex items-center gap-2 bg-[#2d1b0e] border-tungning-gold text-tungning-gold">
            <span>✅</span>
            <span class="font-bold tracking-widest text-lg" x-text="message"></span>
        </div>
    </div>

</div>