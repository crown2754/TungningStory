<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('據點總覽') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#2d1b0e] min-h-screen" style="background-image: url('https://www.transparenttextures.com/patterns/paper.png');">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 歡迎訊息與狀態 --}}
            <div class="bg-[#f3f0eb] overflow-hidden shadow-xl sm:rounded-lg border-4 border-tungning-brown mb-8 relative">
                <div class="p-6 text-tungning-brown">
                    <h3 class="text-2xl font-black mb-2">歡迎回來，{{ Auth::user()->name }}！</h3>
                    <p class="text-lg">這裡是您的據點，您可以在此檢視您的資產與庫存。</p>

                    <div class="mt-4 flex gap-4">
                        <div class="badge badge-lg bg-tungning-gold text-tungning-brown border-none font-bold">
                            💰 銀兩：{{ number_format(Auth::user()->gold) }} 文
                        </div>
                        <div class="badge badge-lg bg-red-800 text-white border-none font-bold">
                            ⚡ 體力：{{ Auth::user()->stamina }} / 100
                        </div>
                    </div>
                </div>
                {{-- 裝飾 --}}
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <div class="text-9xl">🏠</div>
                </div>
            </div>

            {{-- [新增] 物資倉庫區塊 --}}
            <div class="bg-[#e8e0d5] overflow-hidden shadow-xl sm:rounded-lg border-4 border-tungning-wood">
                <div class="bg-tungning-brown px-6 py-4 border-b-4 border-tungning-gold">
                    <h3 class="text-xl font-bold text-tungning-paper tracking-widest">📦 物資倉庫</h3>
                </div>

                <div class="p-6">
                    @php
                    // 直接在 View 裡面撈取資料 (簡單做法)
                    $inventory = \App\Models\Inventory::where('user_id', Auth::id())
                    ->with('item')
                    ->where('quantity', '>', 0)
                    ->get();
                    @endphp

                    @if($inventory->isEmpty())
                    <div class="text-center py-10 text-gray-500 italic">
                        <div class="text-4xl mb-2">💨</div>
                        倉庫空空如也，快去<a href="{{ route('market') }}" class="text-tungning-brown underline font-bold hover:text-red-800">大井頭市集</a>採購吧！
                    </div>
                    @else
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($inventory as $slot)
                        <div class="bg-white border-2 border-gray-400 rounded-lg p-3 shadow-md flex flex-col items-center relative group hover:border-tungning-brown transition-colors">
                            {{-- 物品 Icon --}}
                            <div class="text-4xl mb-2">
                                @if(str_contains($slot->item->name, '茶')) 🍵
                                @elseif(str_contains($slot->item->name, '米')) 🍚
                                @elseif(str_contains($slot->item->name, '酒')) 🍶
                                @elseif(str_contains($slot->item->name, '甕')) 🏺
                                @elseif(str_contains($slot->item->name, '肉')) 🥓
                                @elseif(str_contains($slot->item->name, '木')) 🪵
                                @else 📦
                                @endif
                            </div>

                            <div class="font-bold text-gray-800 text-center">{{ $slot->item->name }}</div>

                            {{-- 數量標籤 --}}
                            <div class="absolute top-0 right-0 bg-tungning-brown text-white text-xs font-bold px-2 py-1 rounded-bl">
                                x{{ number_format($slot->quantity) }} {{ $slot->item->unit }}
                            </div>

                            {{-- Tooltip --}}
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 bg-gray-800 text-white text-xs p-2 rounded hidden group-hover:block z-10 text-center pointer-events-none">
                                {{ $slot->item->description }}
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>