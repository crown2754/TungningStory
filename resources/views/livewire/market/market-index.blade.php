<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif" style="background-image: url('https://www.transparenttextures.com/patterns/paper.png');">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- 頂部標題與錢包 --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 border-b-4 border-tungning-gold pb-4 gap-4">
            <div>
                <h2 class="text-4xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                    🏮 承天府・大井頭市集
                </h2>
                <p class="text-tungning-gold/70 mt-1 text-sm">
                    「凡番船、商船抵台，必泊於大井頭，百貨雲集，為全臺貿易之冠。」
                </p>
            </div>

            {{-- 懸浮錢包卡片 --}}
            <div class="bg-black/40 backdrop-blur-sm border-2 border-tungning-gold rounded-lg px-6 py-3 shadow-xl flex items-center gap-3">
                <div class="text-4xl">💰</div>
                <div class="flex flex-col text-right">
                    <span class="text-xs text-gray-300">身上銀兩</span>
                    <span class="text-2xl font-black text-tungning-gold tracking-wider">
                        {{ number_format(auth()->user()->gold) }} <span class="text-sm">文</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- 商品列表 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
            @php
            // 取得由 Model 計算的動態價格
            $currentPrice = $item->current_price;
            // 判斷漲跌 (比基準價高就是漲)
            $isExpensive = $currentPrice > $item->base_price;
            $isCheap = $currentPrice < $item->base_price;
                @endphp

                <div class="bg-[#e8e0d5] border-4 border-tungning-wood rounded-lg p-4 shadow-xl relative group hover:-translate-y-1 transition-transform duration-300">

                    {{-- 庫存標籤 --}}
                    <div class="absolute top-0 right-0 px-3 py-1 font-bold text-xs text-white rounded-bl-lg shadow-sm
                    {{ $item->stock < $item->target_stock * 0.2 ? 'bg-red-600' : 'bg-tungning-brown' }}">
                        庫存: {{ number_format($item->stock) }}
                    </div>

                    <div class="flex gap-4 mt-2">
                        {{-- 物品 Icon (這裡先用 Emoji 根據類型判斷，之後可換圖片) --}}
                        <div class="w-24 h-24 flex-shrink-0 bg-gray-300 border-2 border-gray-500 rounded flex items-center justify-center text-5xl shadow-inner">
                            @if(str_contains($item->name, '茶')) 🍵
                            @elseif(str_contains($item->name, '米')) 🍚
                            @elseif(str_contains($item->name, '酒')) 🍶
                            @elseif(str_contains($item->name, '甕')) 🏺
                            @elseif(str_contains($item->name, '肉')) 🥓
                            @elseif(str_contains($item->name, '木')) 🪵
                            @else 📦
                            @endif
                        </div>

                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-black text-xl text-tungning-brown tracking-wide">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2 leading-snug">{{ $item->description }}</p>
                            </div>

                            <div class="mt-4 flex items-end justify-between">
                                {{-- 價格顯示區 --}}
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">市價行情</span>
                                    <div class="text-2xl font-black flex items-center gap-1
                                    {{ $isExpensive ? 'text-red-700' : ($isCheap ? 'text-green-700' : 'text-gray-800') }}">
                                        {{ number_format($currentPrice) }}
                                        <span class="text-sm font-normal text-gray-600">文</span>

                                        @if($isExpensive) <span class="text-xs text-red-600 animate-pulse">▲</span> @endif
                                        @if($isCheap) <span class="text-xs text-green-600 animate-pulse">▼</span> @endif
                                    </div>
                                </div>

                                {{-- 購買按鈕 --}}
                                <button wire:click="buy({{ $item->id }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm bg-tungning-gold text-tungning-brown border-tungning-brown hover:bg-yellow-500 hover:border-yellow-600 font-bold shadow-md">
                                    <span wire:loading.remove target="buy({{ $item->id }})">購買</span>
                                    <span wire:loading target="buy({{ $item->id }})">交易中...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
        </div>

    </div>

    {{-- Toast 通知元件 --}}
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:operation-success.window="message = $event.detail.message; type='success'; show = true; setTimeout(() => show = false, 3000)"
        x-on:operation-error.window="message = $event.detail.message; type='error'; show = true; setTimeout(() => show = false, 3000)"
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2">

        <div class="alert shadow-lg border-2 flex items-center gap-2"
            :class="type === 'success' ? 'bg-[#2d1b0e] border-tungning-gold text-tungning-gold' : 'bg-red-900 border-red-500 text-white'">
            <span x-text="type === 'success' ? '✅' : '🚫'"></span>
            <span class="font-bold tracking-widest text-lg" x-text="message"></span>
        </div>
    </div>
</div>