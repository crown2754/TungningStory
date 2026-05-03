<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif" style="background-image: url('{{ asset('images/paper-texture.png') }}'); background-blend-mode: multiply;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- 頂部導航與狀態 --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 border-b-4 border-tungning-gold pb-4 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('shop.index') }}" wire:navigate class="btn bg-tungning-wood text-tungning-gold border-tungning-gold hover:bg-tungning-brown">
                    返回商號
                </a>
                <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                    {{ $shop->name }} - 貨架管理
                </h2>
            </div>
            <div class="flex items-center gap-6 text-tungning-paper font-bold bg-tungning-brown/50 px-6 py-2 rounded-full border border-tungning-gold/50 shadow-inner">
                <div>體力：<span class="text-red-400 text-lg">{{ auth()->user()->stamina }}</span></div>
                <div>銅錢：<span class="text-yellow-400 text-lg">{{ number_format(auth()->user()->gold) }}</span> 文</div>
                <div>營業稅率：<span class="text-amber-300 text-lg">{{ number_format($taxRate, 2) }}%</span></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- [左側] 玩家庫房 --}}
            <div class="bg-tungning-paper p-6 rounded-lg border-4 border-tungning-wood shadow-2xl relative">
                <div class="absolute top-0 right-0 p-2 opacity-10 pointer-events-none select-none">
                    <span class="text-6xl">📦</span>
                </div>
                <h3 class="text-2xl font-black text-tungning-brown mb-6 tracking-widest border-b-2 border-tungning-wood/30 pb-2">您的庫房</h3>
                
                @if($inventoryItems->isEmpty())
                    <div class="text-center py-12 text-tungning-wood/70 italic font-bold">
                        庫房空空如也，無貨可賣。
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($inventoryItems as $inv)
                            <div class="bg-white/80 p-4 rounded-lg border-2 border-tungning-wood shadow hover:shadow-lg hover:border-tungning-gold transition duration-200 group cursor-pointer flex flex-col items-center relative"
                                 wire:click="openListingModal({{ $inv->id }})">
                                
                                {{-- 物品圖片 (若無圖片則顯示 emoji 或預設圖) --}}
                                <div class="w-16 h-16 mb-2 bg-[#e8e0d5] rounded-full border border-tungning-brown/30 flex items-center justify-center text-2xl shadow-inner overflow-hidden">
                                    @if($inv->item->image_path)
                                        <img src="{{ asset($inv->item->image_path) }}" alt="{{ $inv->item->name }}" class="w-full h-full object-cover">
                                    @else
                                        📦
                                    @endif
                                </div>
                                
                                <div class="text-center w-full">
                                    <h4 class="font-black text-tungning-brown text-lg truncate" title="{{ $inv->item->name }}">{{ $inv->item->name }}</h4>
                                    <div class="text-sm text-tungning-wood mt-1 bg-[#e8e0d5] rounded-full px-2 py-0.5">
                                        庫存: <span class="font-bold text-red-800">{{ $inv->quantity }}</span> {{ $inv->item->unit ?? '個' }}
                                    </div>
                                </div>
                                
                                {{-- 懸停提示上架 --}}
                                <div class="absolute inset-0 bg-tungning-brown/90 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <span class="text-tungning-paper font-black text-xl tracking-wider">點擊上架</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- [右側] 店鋪貨架 --}}
            <div class="bg-[#e8e0d5] p-6 rounded-lg border-4 border-tungning-wood shadow-2xl relative">
                <div class="absolute top-0 right-0 p-2 opacity-10 pointer-events-none select-none">
                    <span class="text-6xl">🏪</span>
                </div>
                <h3 class="text-2xl font-black text-tungning-brown mb-6 tracking-widest border-b-2 border-tungning-wood/30 pb-2">店鋪貨架</h3>
                
                @if($shopProducts->isEmpty())
                    <div class="text-center py-12 text-tungning-wood/70 italic font-bold">
                        貨架上空無一物，請從庫房挑選商品上架。
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($shopProducts as $product)
                            <div class="flex items-center justify-between bg-white p-3 rounded shadow border border-tungning-wood/50 hover:border-tungning-gold transition duration-200">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-tungning-paper rounded border border-tungning-brown/30 flex items-center justify-center text-xl overflow-hidden">
                                        @if($product->item->image_path)
                                            <img src="{{ asset($product->item->image_path) }}" alt="{{ $product->item->name }}" class="w-full h-full object-cover">
                                        @else
                                            📦
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-black text-tungning-brown text-lg">{{ $product->item->name }}</h4>
                                        <div class="text-xs text-gray-500 font-bold mt-1">
                                            定價：<span class="text-yellow-600 text-sm">{{ $product->price }}</span> 文 / {{ $product->item->unit ?? '個' }}
                                        </div>
                                        @php
                                            $unitTax = (int) round($product->price * ($taxRate / 100));
                                            $unitNet = $product->price - $unitTax;
                                        @endphp
                                        <div class="text-xs text-gray-600 font-bold mt-1">
                                            稅率：<span class="text-amber-700">{{ number_format($taxRate, 2) }}%</span>
                                            ｜每件稅額：<span class="text-red-700">{{ number_format($unitTax) }}</span> 文
                                            ｜每件實收：<span class="text-green-700">{{ number_format($unitNet) }}</span> 文
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500 font-bold mb-1">貨架剩餘</div>
                                        <div class="font-black text-tungning-brown text-lg bg-gray-100 px-3 py-1 rounded">{{ $product->quantity }}</div>
                                    </div>
                                    
                                    <button wire:click="openUnlistingModal({{ $product->id }})" class="btn btn-sm bg-red-900 text-white hover:bg-red-800 border-none">
                                        下架
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- 上架 Modal --}}
    @if($isListingModalOpen)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-[#f4f1ea] w-full max-w-md rounded-xl shadow-2xl border-4 border-tungning-wood relative overflow-hidden">
                <div class="bg-tungning-brown text-tungning-gold p-4 text-center border-b border-tungning-gold/30">
                    <h3 class="text-2xl font-black tracking-widest">上架商品</h3>
                </div>
                
                <div class="p-6">
                    @php
                        $selectedItem = \App\Models\Inventory::find($selectedInventoryId);
                    @endphp
                    @if($selectedItem)
                        <div class="mb-6 flex items-center gap-4 bg-white p-3 rounded border border-gray-300">
                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-2xl border border-gray-200">
                                {{ $selectedItem->item->image_path ? '' : '📦' }}
                                @if($selectedItem->item->image_path)
                                    <img src="{{ asset($selectedItem->item->image_path) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-gray-800">{{ $selectedItem->item->name }}</h4>
                                <div class="text-sm text-gray-500">庫房剩餘：<span class="font-bold text-red-600">{{ $selectedItem->quantity }}</span></div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-tungning-brown font-bold mb-1">上架數量</label>
                                <input type="number" wire:model="listQuantity" min="1" max="{{ $selectedItem->quantity }}" class="input input-bordered w-full font-bold text-lg border-gray-400 focus:border-tungning-brown" />
                                @error('listQuantity') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-tungning-brown font-bold mb-1">單價 (文)</label>
                                <input type="number" wire:model="listPrice" min="1" class="input input-bordered w-full font-bold text-lg text-yellow-700 border-gray-400 focus:border-tungning-brown" />
                                <div class="text-xs text-gray-500 mt-1 font-bold">市場基礎參考價：{{ $selectedItem->item->base_price ?? 10 }} 文</div>
                                @php
                                    $previewTax = (int) round(((int) $listPrice) * ($taxRate / 100));
                                    $previewNet = ((int) $listPrice) - $previewTax;
                                @endphp
                                <div class="text-xs text-gray-600 mt-1 font-bold">
                                    目前稅率 {{ number_format($taxRate, 2) }}% ｜每件稅額 {{ number_format($previewTax) }} 文 ｜每件實收 {{ number_format($previewNet) }} 文
                                </div>
                                @error('listPrice') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-200/50 p-4 flex justify-end gap-3 border-t border-gray-300">
                    <button wire:click="closeListingModal" class="btn bg-white text-gray-700 border-gray-400 hover:bg-gray-100">取消</button>
                    <button wire:click="confirmListing" wire:loading.attr="disabled" class="btn bg-green-800 text-white hover:bg-green-700 border-none flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove target="confirmListing">確定上架</span>
                        <span wire:loading target="confirmListing">處理中...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- 下架 Modal --}}
    @if($isUnlistingModalOpen)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-[#f4f1ea] w-full max-w-md rounded-xl shadow-2xl border-4 border-tungning-wood relative overflow-hidden">
                <div class="bg-red-900 text-red-100 p-4 text-center border-b border-red-950">
                    <h3 class="text-2xl font-black tracking-widest">下架商品</h3>
                </div>
                
                <div class="p-6">
                    @php
                        $selectedProduct = \App\Models\ShopProduct::find($selectedShopProductId);
                    @endphp
                    @if($selectedProduct)
                        <div class="mb-6 flex items-center gap-4 bg-white p-3 rounded border border-gray-300">
                            <div>
                                <h4 class="font-bold text-lg text-gray-800">{{ $selectedProduct->item->name }}</h4>
                                <div class="text-sm text-gray-500">貨架剩餘：<span class="font-bold text-red-600">{{ $selectedProduct->quantity }}</span></div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-tungning-brown font-bold mb-1">要下架的數量</label>
                                <input type="number" wire:model="unlistQuantity" min="1" max="{{ $selectedProduct->quantity }}" class="input input-bordered w-full font-bold text-lg border-gray-400 focus:border-red-900" />
                                @error('unlistQuantity') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                            </div>
                            <p class="text-sm text-gray-500 italic font-bold">下架的商品將會退回您的庫房中。</p>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-200/50 p-4 flex justify-end gap-3 border-t border-gray-300">
                    <button wire:click="closeUnlistingModal" class="btn bg-white text-gray-700 border-gray-400 hover:bg-gray-100">取消</button>
                    <button wire:click="confirmUnlisting" wire:loading.attr="disabled" class="btn bg-red-900 text-white hover:bg-red-800 border-none flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove target="confirmUnlisting">確定下架</span>
                        <span wire:loading target="confirmUnlisting">處理中...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
