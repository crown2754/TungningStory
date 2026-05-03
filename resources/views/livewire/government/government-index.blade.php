<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif" style="background-image: url('https://www.transparenttextures.com/patterns/paper.png'); background-blend-mode: multiply;">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        {{-- 標題 --}}
        <div class="text-center mb-10">
            <div class="inline-block bg-red-900/80 border-4 border-tungning-gold px-10 py-4 rounded shadow-2xl">
                <div class="text-tungning-gold/60 text-sm tracking-[0.5em] mb-1">大明永曆年間</div>
                <h2 class="text-4xl font-black text-tungning-gold tracking-[0.3em] drop-shadow-lg">承天府 · 國情告示</h2>
                <div class="text-tungning-gold/60 text-xs tracking-[0.5em] mt-1">東寧‧政事堂 頒佈</div>
            </div>
        </div>

        {{-- 鄭成功 NPC 區塊 --}}
        <div class="bg-[#f4f1ea] rounded-lg border-4 border-tungning-wood p-6 shadow-2xl mb-8">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <button type="button"
                    wire:click="talkToKoxinga"
                    class="w-28 h-28 rounded-full border-4 border-tungning-gold overflow-hidden shadow-lg bg-[#2d1b0e] shrink-0 hover:scale-105 active:scale-95 transition duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-tungning-gold/60"
                    title="點擊與鄭成功對話">
                    @if($koxinga?->avatar_url)
                        <img src="{{ asset($koxinga->avatar_url) }}" alt="鄭成功" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">🎖️</div>
                    @endif
                </button>
                <div class="flex-1 text-center md:text-left">
                    <div class="text-xs tracking-[0.3em] text-tungning-wood/60 font-bold">政事堂首席軍政領袖</div>
                    <h3 class="text-3xl font-black text-tungning-brown mt-1">
                        {{ $koxinga?->name ?? '鄭成功' }}
                        <span class="text-lg text-red-900">· {{ $koxinga?->title ?? '延平王' }}</span>
                    </h3>
                    <p class="mt-2 text-tungning-wood/80 font-bold leading-relaxed">
                        {{ $koxinga?->description ?? '驅除韃虜，恢復中原；立足東寧，整飭軍政。' }}
                    </p>
                    <div wire:key="koxinga-dialogue-{{ $bubbleShake }}" class="mt-3 border-l-4 border-red-900 pl-3 italic text-red-900 font-black transition-all duration-300">
                        「{{ $currentDialogue }}」
                    </div>
                </div>
            </div>
        </div>

        {{-- 主要資訊網格 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            {{-- 治安 --}}
            <div class="bg-[#f4f1ea] rounded-lg border-4 border-tungning-wood p-6 shadow-xl">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">⚔️</span>
                        <div>
                            <div class="text-xs text-tungning-wood/60 font-bold tracking-widest">社會治安</div>
                            <div class="text-2xl font-black text-tungning-brown">{{ $gov->public_order }} / 100</div>
                        </div>
                    </div>
                    <span class="px-4 py-1 rounded-full font-black text-sm border-2
                        @if($gov->public_order >= 80) bg-green-100 text-green-800 border-green-400
                        @elseif($gov->public_order >= 60) bg-blue-100 text-blue-700 border-blue-400
                        @elseif($gov->public_order >= 40) bg-yellow-100 text-yellow-700 border-yellow-400
                        @else bg-red-100 text-red-700 border-red-400 @endif">
                        {{ $gov->public_order_label }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all
                        @if($gov->public_order >= 80) bg-green-500
                        @elseif($gov->public_order >= 60) bg-blue-500
                        @elseif($gov->public_order >= 40) bg-yellow-500
                        @else bg-red-500 @endif"
                        style="width: {{ $gov->public_order }}%">
                    </div>
                </div>
            </div>

            {{-- 開荒 --}}
            <div class="bg-[#f4f1ea] rounded-lg border-4 border-tungning-wood p-6 shadow-xl">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🌾</span>
                        <div>
                            <div class="text-xs text-tungning-wood/60 font-bold tracking-widest">土地開墾</div>
                            <div class="text-2xl font-black text-tungning-brown">{{ $gov->land_development }} / 100</div>
                        </div>
                    </div>
                    <span class="px-4 py-1 rounded-full font-black text-sm border-2
                        @if($gov->land_development >= 80) bg-green-100 text-green-800 border-green-400
                        @elseif($gov->land_development >= 60) bg-lime-100 text-lime-700 border-lime-400
                        @elseif($gov->land_development >= 40) bg-yellow-100 text-yellow-700 border-yellow-400
                        @else bg-orange-100 text-orange-700 border-orange-400 @endif">
                        {{ $gov->land_development_label }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full bg-green-600 transition-all" style="width: {{ $gov->land_development }}%"></div>
                </div>
            </div>

        </div>

        {{-- 數據列表 --}}
        <div class="bg-[#f4f1ea] rounded-lg border-4 border-tungning-wood shadow-2xl overflow-hidden">
            <div class="bg-tungning-wood text-tungning-paper px-6 py-3 flex items-center gap-3">
                <span class="text-xl">📜</span>
                <span class="font-black tracking-widest text-lg">承天府各項統計</span>
            </div>
            <div class="divide-y divide-tungning-wood/20">

                {{-- 稅率 --}}
                <div class="flex items-center justify-between px-8 py-5 hover:bg-tungning-wood/5 transition">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl">🪙</span>
                        <div>
                            <div class="font-black text-xl text-tungning-brown">市集稅率</div>
                            <div class="text-xs text-tungning-wood/60">玩家售出商品時，政府抽取的比例</div>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-red-800">{{ $gov->tax_rate }}%</div>
                </div>

                {{-- 政府資金 --}}
                <div class="flex items-center justify-between px-8 py-5 hover:bg-tungning-wood/5 transition">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl">🏛️</span>
                        <div>
                            <div class="font-black text-xl text-tungning-brown">承天府國庫</div>
                            <div class="text-xs text-tungning-wood/60">政府財政資金（含稅收累積）</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-black text-tungning-brown">{{ number_format($gov->treasury) }}</div>
                        <div class="text-xs text-tungning-wood/60">文</div>
                    </div>
                </div>

                {{-- 人口 --}}
                <div class="flex items-center justify-between px-8 py-5 hover:bg-tungning-wood/5 transition">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl">👥</span>
                        <div>
                            <div class="font-black text-xl text-tungning-brown">東寧人口</div>
                            <div class="text-xs text-tungning-wood/60">承天府登籍在冊之丁口</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-black text-tungning-brown">{{ number_format($gov->population) }}</div>
                        <div class="text-xs text-tungning-wood/60">人</div>
                    </div>
                </div>

                {{-- 軍隊 --}}
                <div class="flex items-center justify-between px-8 py-5 hover:bg-tungning-wood/5 transition">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl">🎌</span>
                        <div>
                            <div class="font-black text-xl text-tungning-brown">在籍軍士</div>
                            <div class="text-xs text-tungning-wood/60">現役披甲將士人數</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-black text-tungning-brown">{{ number_format($gov->military_count) }}</div>
                        <div class="text-xs text-tungning-wood/60">人</div>
                    </div>
                </div>

                {{-- 軍糧 --}}
                <div class="flex items-center justify-between px-8 py-5 hover:bg-tungning-wood/5 transition">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl">🍚</span>
                        <div>
                            <div class="font-black text-xl text-tungning-brown">軍糧儲備</div>
                            <div class="text-xs text-tungning-wood/60">軍倉存糧，確保將士用命</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-black text-tungning-brown">{{ number_format($gov->military_food) }}</div>
                        <div class="text-xs text-tungning-wood/60">石</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 底部時間戳 --}}
        <div class="text-center mt-6 text-tungning-wood/50 text-xs font-bold tracking-widest">
            告示最後更新：{{ $gov->updated_at->format('Y 年 m 月 d 日 H:i') }}
        </div>

    </div>
</div>
