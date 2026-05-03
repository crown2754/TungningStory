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
                            💰 銀兩：{{ number_format(auth()->user()->gold) }} 文
                        </div>
                        <div class="badge badge-lg bg-red-800 text-white border-none font-bold">
                            ⚡ 體力：{{ auth()->user()->stamina }} / 100
                        </div>
                        <div class="badge badge-lg bg-gray-700 text-white border-none font-bold">
                            📦 庫存：{{ number_format($currentCapacity) }} / {{ number_format(auth()->user()->inventory_capacity) }}
                        </div>
                    </div>
                </div>
                {{-- 裝飾 --}}
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <div class="text-9xl">🏠</div>
                </div>
            </div>

            {{-- [新增] 物資倉庫區塊 --}}
            <div class="bg-[#e8e0d5] overflow-hidden shadow-xl sm:rounded-lg border-4 border-tungning-wood" x-data="{ view: 'grid' }">
                <div class="bg-tungning-brown px-6 py-4 border-b-4 border-tungning-gold flex justify-between items-center">
                    <h3 class="text-xl font-bold text-tungning-paper tracking-widest">📦 物資倉庫</h3>
                    
                    {{-- 切換視圖按鈕 --}}
                    <div class="flex space-x-1 bg-black/30 rounded p-1">
                        <button @click="view = 'grid'" :class="view === 'grid' ? 'bg-tungning-gold text-tungning-brown' : 'text-gray-300 hover:text-white'" class="px-3 py-1 rounded text-sm font-bold transition-colors">圖示</button>
                        <button @click="view = 'list'" :class="view === 'list' ? 'bg-tungning-gold text-tungning-brown' : 'text-gray-300 hover:text-white'" class="px-3 py-1 rounded text-sm font-bold transition-colors">列表</button>
                    </div>
                </div>

                <div class="p-6">
                    @if($inventory->isEmpty())
                    <div class="text-center py-10 text-gray-500 italic">
                        <div class="text-4xl mb-2">💨</div>
                        倉庫空空如也，快去<a href="{{ route('market') }}" class="text-tungning-brown underline font-bold hover:text-red-800">大井頭市集</a>採購吧！
                    </div>
                    @else
                    
                    {{-- 圖示視圖 (Grid View) --}}
                    <div x-show="view === 'grid'" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($inventory as $slot)
                        @if($slot->item)
                        <div class="bg-white border-2 border-gray-400 rounded-lg p-3 shadow-md flex flex-col items-center relative group hover:border-tungning-brown transition-colors">
                            {{-- 物品圖片 --}}
                            <div class="mb-2 w-16 h-16 flex items-center justify-center rounded overflow-hidden shadow-inner border border-gray-200 bg-gray-100">
                                @if($slot->item->image_path)
                                    <img src="{{ asset($slot->item->image_path) }}" alt="{{ $slot->item->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl drop-shadow-sm">📦</span>
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
                        @endif
                        @endforeach
                    </div>

                    {{-- 列表視圖 (List View) --}}
                    <div x-show="view === 'list'" style="display: none;" class="flex flex-col gap-3">
                        @foreach($inventory as $slot)
                        @if($slot->item)
                        <div class="bg-white border-2 border-gray-400 rounded-lg p-3 shadow-md flex items-center gap-4 hover:border-tungning-brown transition-colors">
                            <div class="w-16 h-16 flex-shrink-0 flex items-center justify-center rounded overflow-hidden shadow-inner border border-gray-200 bg-gray-100">
                                @if($slot->item->image_path)
                                    <img src="{{ asset($slot->item->image_path) }}" alt="{{ $slot->item->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl drop-shadow-sm">📦</span>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <div class="font-bold text-lg text-gray-800">{{ $slot->item->name }}</div>
                                <div class="text-sm text-gray-600 mt-1">{{ $slot->item->description }}</div>
                            </div>
                            <div class="flex-shrink-0 text-right min-w-[80px]">
                                <div class="text-2xl font-black text-tungning-brown">{{ number_format($slot->quantity) }}</div>
                                <div class="text-sm text-gray-500 font-bold">{{ $slot->item->unit }}</div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>