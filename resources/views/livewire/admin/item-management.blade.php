<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6 border-b-4 border-tungning-gold pb-4">
            <h2 class="text-3xl font-black text-tungning-paper tracking-widest">📦 東寧物資總署</h2>
            <button wire:click="create" class="btn bg-tungning-brown text-tungning-paper border-tungning-gold hover:bg-tungning-wood">
                + 新增管制物資
            </button>
        </div>

        <div class="bg-white/95 rounded-lg shadow-xl overflow-hidden border-4 border-tungning-wood">
            <table class="table w-full">
                <thead class="bg-tungning-brown text-tungning-gold text-lg">
                    <tr>
                        <th>圖片</th>
                        <th>名稱 / 單位</th> {{-- [修改] --}}
                        <th>基準價 / 當前價</th>
                        <th>庫存狀況</th>
                        <th>波動率</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    @php
                        $price = $item->current_price; 
                        $isHigh = $price > $item->base_price;
                    @endphp
                    <tr class="hover:bg-gray-100 border-b border-gray-300">
                        <td>
                            <div class="avatar">
                                <div class="w-12 h-12 rounded border border-gray-400 bg-gray-200">
                                    @if($item->image_path)
                                        <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}" />
                                    @else
                                        <div class="flex items-center justify-center h-full text-xs">無圖</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="font-bold text-lg text-tungning-brown">
                            {{ $item->name }}
                            {{-- [新增] 顯示單位 --}}
                            <span class="text-sm text-gray-500 font-normal">({{ $item->unit }})</span>
                            <br><span class="text-xs text-gray-500 font-normal badge badge-ghost">{{ $item->type }}</span>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">基準: {{ $item->base_price }}</span>
                                <span class="text-xl font-black {{ $isHigh ? 'text-red-700' : 'text-green-700' }}">
                                    {{ $price }} 文
                                    <span class="text-xs">{{ $isHigh ? '▲' : ($price < $item->base_price ? '▼' : '-') }}</span>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-bold">{{ number_format($item->stock) }}</span>
                                <span class="text-xs text-gray-400">/ 目標 {{ number_format($item->target_stock) }}</span>
                            </div>
                        </td>
                        <td>{{ $item->volatility }}</td>
                        <td>
                            <button wire:click="edit({{ $item->id }})" class="btn btn-sm bg-tungning-gold text-tungning-brown border-none">調控</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 bg-[#e8e0d5]">
                {{ $items->links() }}
            </div>
        </div>

        {{-- Modal --}}
        @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div class="bg-[#f3f0eb] border-4 border-tungning-brown rounded-lg shadow-2xl w-full max-w-5xl flex flex-col md:flex-row overflow-hidden h-[85vh]">
                
                {{-- 左側表單 --}}
                <div class="w-full md:w-2/3 p-6 space-y-4 overflow-y-auto bg-white/50">
                    <h3 class="text-2xl font-black text-tungning-brown mb-4 border-b border-gray-400 pb-2">
                        {{ $editingItemId ? '調控物資參數' : '新增管制物資' }}
                    </h3>

                    {{-- 圖片上傳區 --}}
                    <div class="form-control mb-4">
                        <label class="label font-bold text-gray-700">商品圖片</label>
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center overflow-hidden bg-gray-100">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif ($current_image_path)
                                    <img src="{{ asset($current_image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-400 text-xs text-center">預覽</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" wire:model="image" class="file-input file-input-bordered file-input-sm w-full max-w-xs" accept="image/*" />
                                <div class="text-xs text-gray-500 mt-1" wire:loading wire:target="image">圖片上傳中...</div>
                                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- [修改] 讓名稱與單位併排 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="form-control col-span-2">
                            <label class="label font-bold text-gray-700">物資名稱</label>
                            <input type="text" wire:model="name" class="input input-bordered bg-white" placeholder="例：稻米">
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-gray-700">單位</label>
                            <input type="text" wire:model="unit" class="input input-bordered bg-white" placeholder="個, 斤, 瓶...">
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-gray-700">分類</label>
                        <select wire:model="type" class="select select-bordered bg-white">
                            <option value="food">糧食 (Food)</option>
                            <option value="material">資材 (Material)</option>
                            <option value="luxury">珍品 (Luxury)</option>
                            <option value="equipment">器物 (Equipment)</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-gray-700">描述</label>
                        <textarea wire:model="description" class="textarea textarea-bordered bg-white" rows="2"></textarea>
                    </div>

                    <div class="divider text-tungning-gold font-bold">市場經濟核心參數</div>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="form-control">
                            <label class="label font-bold text-blue-900">標準價格 (基價)</label>
                            <input type="number" wire:model.live="base_price" class="input input-bordered bg-white font-bold text-lg text-blue-900">
                        </div>
                        <div class="form-control">
                            <label class="label text-xs text-gray-500">崩盤下限</label>
                            <input type="number" wire:model.live="min_price" class="input input-sm input-bordered bg-white text-gray-500">
                        </div>
                        <div class="form-control">
                            <label class="label text-xs text-gray-500">炒作上限</label>
                            <input type="number" wire:model.live="max_price" class="input input-sm input-bordered bg-white text-gray-500">
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded border border-yellow-200">
                        <div class="flex justify-between mb-2">
                            <label class="font-bold text-gray-700">實際庫存</label>
                            <span class="font-mono text-xl">{{ $stock }}</span>
                        </div>
                        <input type="range" min="0" max="{{ $target_stock * 2 }}" step="10" wire:model.live="stock" class="range range-primary range-sm">
                        
                        <div class="flex justify-between mt-4 mb-2">
                            <label class="font-bold text-gray-700">目標平衡庫存</label>
                            <span class="font-mono text-xl">{{ $target_stock }}</span>
                        </div>
                        <input type="number" wire:model.live="target_stock" class="input input-sm w-full input-bordered">
                    </div>

                    <div class="bg-red-50 p-4 rounded border border-red-200">
                        <div class="flex justify-between mb-2">
                            <label class="font-bold text-red-900">波動率</label>
                            <span class="font-bold text-xl text-red-700">{{ $volatility }}</span>
                        </div>
                        <input type="range" min="0.1" max="2.0" step="0.1" wire:model.live="volatility" class="range range-error range-sm">
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button wire:click="$set('showModal', false)" class="btn btn-ghost">取消</button>
                        <button wire:click="save" class="btn bg-tungning-brown text-white shadow-lg">確認發布</button>
                    </div>
                </div>

                {{-- 右側模擬器 --}}
                <div class="w-full md:w-1/3 bg-[#1a1a1a] text-white p-6 flex flex-col items-center justify-center shadow-inner relative">
                    <div class="opacity-50 text-center">
                        <div class="text-sm">基準價格</div>
                        <div class="text-2xl font-mono">{{ $base_price }}</div>
                    </div>
                    <div class="text-2xl animate-bounce my-4">↓</div>
                    <div class="bg-gray-800 p-4 rounded-lg border border-gray-600 w-full text-center">
                        <div class="text-xs text-gray-400 mb-1">供需狀況</div>
                        @php
                            $diff = (int)$target_stock - (int)$stock;
                            $status = $diff > 0 ? '缺貨' : ($diff < 0 ? '滯銷' : '平衡');
                            $color = $diff > 0 ? 'text-red-400' : ($diff < 0 ? 'text-green-400' : 'text-gray-200');
                        @endphp
                        <div class="text-lg font-bold {{ $color }}">{{ $status }}</div>
                        <div class="text-sm mt-1">差額: {{ $diff }}</div>
                    </div>
                    <div class="text-2xl animate-bounce my-4">↓</div>
                    <div class="border-4 border-gray-500 p-6 rounded-xl w-full text-center">
                        <div class="text-sm text-gray-300 mb-2">最終市場成交價</div>
                        <div class="text-6xl font-black tracking-widest font-mono">{{ $previewPrice }}</div>
                        <div class="text-xl mt-2 text-gray-400">文</div>
                    </div>
                </div>

            </div>
        </div>
        @endif

        {{-- Toast --}}
        <div x-data="{ show: false, message: '' }"
            x-on:operation-success.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
            class="toast toast-bottom toast-end z-[9999]" style="display: none;" x-show="show" x-transition>
            <div class="alert bg-tungning-wood text-white border-2 border-tungning-gold">
                <span class="font-bold" x-text="message"></span>
            </div>
        </div>
    </div>
</div>