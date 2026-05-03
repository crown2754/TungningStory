<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- 頁首 --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md">物資總署 ‧ 庫存總覽</h2>
                <p class="text-tungning-wood/70 font-bold mt-1 text-sm">即時監控官府庫存與流落民間的物資數量</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.index') }}" class="btn btn-sm bg-tungning-wood text-tungning-paper border-tungning-brown hover:bg-tungning-brown">
                    ← 返回後台
                </a>
            </div>
        </div>

        {{-- 全局數字卡片 --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- 官府總庫存 --}}
            <div class="bg-tungning-paper rounded-lg border-4 border-tungning-wood shadow-xl p-6 flex items-center gap-5">
                <div class="text-5xl">🏯</div>
                <div>
                    <div class="text-xs font-bold text-tungning-wood/70 tracking-widest mb-1">官府總庫存</div>
                    <div class="text-3xl font-black text-tungning-brown">{{ number_format($totalOfficialStock) }}</div>
                    <div class="text-xs text-tungning-wood/50 mt-1">物資總署所有物品的庫存合計</div>
                </div>
            </div>
            {{-- 民間總流通 --}}
            <div class="bg-tungning-paper rounded-lg border-4 border-amber-700 shadow-xl p-6 flex items-center gap-5">
                <div class="text-5xl">🏘️</div>
                <div>
                    <div class="text-xs font-bold text-amber-700/70 tracking-widest mb-1">民間總流通量</div>
                    <div class="text-3xl font-black text-amber-800">{{ number_format($totalCivilianStock) }}</div>
                    <div class="text-xs text-amber-700/50 mt-1">散落在市井百姓手中的物資合計</div>
                </div>
            </div>
            {{-- 告急品數量 --}}
            <div class="bg-tungning-paper rounded-lg border-4 {{ $alertCount > 0 ? 'border-red-700' : 'border-green-700' }} shadow-xl p-6 flex items-center gap-5">
                <div class="text-5xl">{{ $alertCount > 0 ? '🚨' : '✅' }}</div>
                <div>
                    <div class="text-xs font-bold {{ $alertCount > 0 ? 'text-red-700/70' : 'text-green-700/70' }} tracking-widest mb-1">告急物資</div>
                    <div class="text-3xl font-black {{ $alertCount > 0 ? 'text-red-700' : 'text-green-700' }}">{{ $alertCount }} 項</div>
                    <div class="text-xs text-gray-400 mt-1">官府庫存低於目標量 20% 的物品</div>
                </div>
            </div>
        </div>

        {{-- 搜尋 --}}
        <div class="flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <span class="absolute left-3 top-3 text-tungning-wood/50">🔍</span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="搜尋物資名稱..."
                    class="input w-full pl-9 bg-[#f4f1ea] text-tungning-brown border-2 border-tungning-wood focus:border-tungning-gold font-bold"
                />
            </div>
            <div class="text-tungning-paper/60 text-sm font-bold">共 {{ $items->total() }} 項物資</div>
        </div>

        {{-- 物資表格 --}}
        <div class="bg-tungning-paper rounded-lg border-4 border-tungning-wood shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full text-tungning-brown font-serif">
                    <thead>
                        <tr class="bg-tungning-wood text-tungning-paper text-sm">
                            <th class="py-4 px-4 text-left font-black tracking-wider w-12">#</th>
                            <th class="py-4 px-4 text-left">
                                <button wire:click="sortBy('name')" class="flex items-center gap-1 font-black hover:text-tungning-gold transition">
                                    物資名稱
                                    @if($sortField === 'name') <span>{{ $sortDir === 'asc' ? '▲' : '▼' }}</span> @else <span class="opacity-30">▲</span> @endif
                                </button>
                            </th>
                            <th class="py-4 px-4 text-center">
                                <button wire:click="sortBy('stock')" class="flex items-center gap-1 font-black hover:text-tungning-gold transition mx-auto">
                                    官府庫存
                                    @if($sortField === 'stock') <span>{{ $sortDir === 'asc' ? '▲' : '▼' }}</span> @else <span class="opacity-30">▲</span> @endif
                                </button>
                            </th>
                            <th class="py-4 px-4 text-center font-black tracking-wider">目標庫存</th>
                            <th class="py-4 px-4 text-center">
                                <button wire:click="sortBy('civilian_stock')" class="flex items-center gap-1 font-black hover:text-tungning-gold transition mx-auto">
                                    民間數量
                                    @if($sortField === 'civilian_stock') <span>{{ $sortDir === 'asc' ? '▲' : '▼' }}</span> @else <span class="opacity-30">▲</span> @endif
                                </button>
                            </th>
                            <th class="py-4 px-4 text-center font-black tracking-wider">庫存狀態</th>
                            <th class="py-4 px-4 text-right">
                                <button wire:click="sortBy('current_price')" class="flex items-center gap-1 font-black hover:text-tungning-gold transition ml-auto">
                                    當前市價
                                    @if($sortField === 'current_price') <span>{{ $sortDir === 'asc' ? '▲' : '▼' }}</span> @else <span class="opacity-30">▲</span> @endif
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @php
                                $ratio = $item->target_stock > 0 ? ($item->stock / $item->target_stock) : 0;
                                $pct = min(100, round($ratio * 100));
                                
                                if ($pct <= 20) {
                                    $barColor = 'bg-red-500';
                                    $statusLabel = '告急';
                                    $statusClass = 'bg-red-100 text-red-700 border-red-300';
                                } elseif ($pct <= 50) {
                                    $barColor = 'bg-orange-400';
                                    $statusLabel = '不足';
                                    $statusClass = 'bg-orange-100 text-orange-700 border-orange-300';
                                } elseif ($pct >= 100) {
                                    $barColor = 'bg-blue-400';
                                    $statusLabel = '充裕';
                                    $statusClass = 'bg-blue-100 text-blue-700 border-blue-300';
                                } else {
                                    $barColor = 'bg-green-500';
                                    $statusLabel = '正常';
                                    $statusClass = 'bg-green-100 text-green-700 border-green-300';
                                }
                            @endphp
                            <tr class="border-b border-tungning-brown/10 hover:bg-tungning-wood/5 transition">
                                {{-- ID --}}
                                <td class="py-3 px-4 text-center text-xs text-gray-400 font-bold">{{ $item->id }}</td>

                                {{-- 物資名稱 --}}
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-md border border-tungning-brown/20 bg-white flex items-center justify-center overflow-hidden shrink-0">
                                            @if($item->image_path)
                                                <img src="{{ asset($item->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xl">📦</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-black text-tungning-brown">{{ $item->name }}</div>
                                            <div class="text-xs text-tungning-wood/60">基準價 {{ number_format($item->base_price) }} 文</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 官府庫存 + 進度條 --}}
                                <td class="py-3 px-4 text-center">
                                    <div class="font-black text-xl text-tungning-brown">{{ number_format($item->stock) }}</div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1.5 max-w-[80px] mx-auto">
                                        <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $pct }}%</div>
                                </td>

                                {{-- 目標庫存 --}}
                                <td class="py-3 px-4 text-center font-bold text-gray-500">
                                    {{ number_format($item->target_stock) }}
                                </td>

                                {{-- 民間數量 --}}
                                <td class="py-3 px-4 text-center">
                                    @if($item->civilian_stock > 0)
                                        <span class="font-black text-xl text-amber-700">{{ number_format($item->civilian_stock) }}</span>
                                        <div class="text-xs text-amber-600/70 mt-0.5">流通中</div>
                                    @else
                                        <span class="text-gray-300 font-bold">—</span>
                                    @endif
                                </td>

                                {{-- 庫存狀態 --}}
                                <td class="py-3 px-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-black border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- 當前市價 --}}
                                <td class="py-3 px-4 text-right">
                                    <span class="font-black text-lg text-tungning-brown">{{ number_format($item->current_price) }}</span>
                                    <span class="text-xs text-gray-400"> 文</span>
                                    @if($item->current_price > $item->base_price)
                                        <div class="text-xs text-red-500 font-bold">▲ +{{ number_format($item->current_price - $item->base_price) }}</div>
                                    @elseif($item->current_price < $item->base_price)
                                        <div class="text-xs text-blue-500 font-bold">▼ {{ number_format($item->current_price - $item->base_price) }}</div>
                                    @else
                                        <div class="text-xs text-gray-400">= 基準</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($items->isEmpty())
                <div class="text-center py-16 text-tungning-wood/50 font-bold">
                    <div class="text-4xl mb-2">📦</div>
                    查無符合的物資
                </div>
            @endif

            {{-- 分頁 --}}
            @if($items->hasPages())
                <div class="p-4 border-t border-tungning-brown/10">
                    {{ $items->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
