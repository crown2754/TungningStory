<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-black text-tungning-paper tracking-widest">承天府 · 政務管理</h2>
            <a href="{{ route('admin.index') }}" class="btn btn-sm bg-tungning-wood text-tungning-paper border-tungning-brown hover:bg-tungning-brown">
                ← 返回後台
            </a>
        </div>

        <div class="bg-tungning-paper rounded-lg border-4 border-tungning-wood shadow-2xl p-8 space-y-8 relative">
            <div class="absolute top-4 right-4 opacity-10 transform rotate-12 pointer-events-none">
                <div class="w-32 h-32 border-4 border-red-800 rounded-full flex items-center justify-center">
                    <span class="text-red-800 font-black text-2xl">承天府印</span>
                </div>
            </div>

            <form wire:submit.prevent="save" class="space-y-8">

                {{-- 稅率 --}}
                <div class="border-b border-tungning-wood/20 pb-6">
                    <label class="block text-xl font-black text-tungning-brown mb-1">🪙 市集稅率</label>
                    <p class="text-xs text-tungning-wood/60 mb-3">玩家每筆售出收入中，政府抽取的百分比（0~100%）。稅款自動流入國庫。</p>
                    <div class="flex items-center gap-4">
                        <input type="range" wire:model.live="tax_rate" min="0" max="100" step="0.5"
                               class="range range-xs flex-1 range-warning">
                        <div class="w-24 text-right">
                            <span class="text-2xl font-black text-red-800">{{ $tax_rate }}</span>
                            <span class="text-tungning-wood font-bold"> %</span>
                        </div>
                    </div>
                    @error('tax_rate') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 治安 --}}
                <div class="border-b border-tungning-wood/20 pb-6">
                    <label class="block text-xl font-black text-tungning-brown mb-1">⚔️ 社會治安</label>
                    <p class="text-xs text-tungning-wood/60 mb-3">影響百姓安居程度（1~100）。</p>
                    <div class="flex items-center gap-4">
                        <input type="range" wire:model.live="public_order" min="1" max="100"
                               class="range range-xs flex-1 range-success">
                        <div class="w-28 text-right">
                            <span class="text-2xl font-black text-tungning-brown">{{ $public_order }}</span>
                            <span class="text-tungning-wood font-bold"> / 100</span>
                        </div>
                    </div>
                    @error('public_order') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 開荒 --}}
                <div class="border-b border-tungning-wood/20 pb-6">
                    <label class="block text-xl font-black text-tungning-brown mb-1">🌾 土地開墾</label>
                    <p class="text-xs text-tungning-wood/60 mb-3">東寧開墾進度（1~100）。</p>
                    <div class="flex items-center gap-4">
                        <input type="range" wire:model.live="land_development" min="1" max="100"
                               class="range range-xs flex-1 range-success">
                        <div class="w-28 text-right">
                            <span class="text-2xl font-black text-tungning-brown">{{ $land_development }}</span>
                            <span class="text-tungning-wood font-bold"> / 100</span>
                        </div>
                    </div>
                    @error('land_development') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 數值欄位 --}}
                @foreach([
                    ['field' => 'treasury',       'label' => '🏛️ 承天府國庫', 'unit' => '文',  'desc' => '政府財政資金'],
                    ['field' => 'population',     'label' => '👥 東寧人口',   'unit' => '人',  'desc' => '在籍丁口總數'],
                    ['field' => 'military_count', 'label' => '🎌 在籍軍士',   'unit' => '人',  'desc' => '現役披甲將士'],
                    ['field' => 'military_food',  'label' => '🍚 軍糧儲備',   'unit' => '石',  'desc' => '軍倉存糧總量'],
                ] as $f)
                <div class="border-b border-tungning-wood/20 pb-6 last:border-0">
                    <label class="block text-xl font-black text-tungning-brown mb-1">{{ $f['label'] }}</label>
                    <p class="text-xs text-tungning-wood/60 mb-3">{{ $f['desc'] }}</p>
                    <div class="flex items-center gap-3">
                        <input type="number" wire:model="{{ $f['field'] }}" min="0"
                               class="input flex-1 bg-[#e8e0d5] text-tungning-brown text-xl font-black border-2 border-tungning-wood focus:border-tungning-gold text-right">
                        <span class="font-black text-tungning-wood text-lg w-8">{{ $f['unit'] }}</span>
                    </div>
                    @error($f['field']) <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn bg-red-800 text-tungning-paper border-2 border-tungning-gold hover:bg-red-900 text-lg px-8 font-black tracking-widest shadow-[0_0_15px_rgba(212,175,55,0.4)] transition transform hover:scale-105">
                        頒佈諭令
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Toast --}}
    <div
        x-data="{ show: false, message: '' }"
        x-on:operation-success.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition.duration.500ms>
        <div class="alert bg-[#4A2C16] text-[#D4AF37] border-2 border-[#8B4513] shadow-lg flex items-center gap-4 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-black text-lg tracking-widest font-serif" x-text="message"></span>
        </div>
    </div>
</div>
