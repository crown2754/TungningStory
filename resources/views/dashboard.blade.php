<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-tungning-brown leading-tight">
            {{ __('東寧府邸') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#2d1b0e] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-[#F5F5DC] overflow-hidden shadow-xl sm:rounded-lg border-2 border-[#D4AF37]">
                <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-6">
                    
                    <div class="flex items-center gap-4">
                        <div class="avatar">
                            <div class="w-20 rounded-full ring ring-tungning-wood ring-offset-base-100 ring-offset-2">
                                <img src="https://api.dicebear.com/7.x/pixel-art/svg?seed={{ Auth::user()->name }}" />
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-tungning-brown">{{ Auth::user()->name }}</h3>
                            <span class="badge badge-lg bg-tungning-wood text-white border-none">{{ Auth::user()->job }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-center gap-8">
                        <div class="text-center">
                            <div class="text-sm font-bold text-tungning-wood uppercase tracking-widest">目前資產</div>
                            <div class="text-3xl font-black text-tungning-brown">{{ number_format(Auth::user()->gold) }} <span class="text-base font-normal">文</span></div>
                        </div>

                        <div class="text-center w-64">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-bold text-tungning-wood">體力值</span>
                                <span class="text-sm font-bold text-tungning-brown">{{ Auth::user()->stamina }} / {{ Auth::user()->max_stamina }}</span>
                            </div>
                            <progress class="progress progress-error w-full h-4 border border-tungning-brown/20 shadow-inner" value="{{ Auth::user()->stamina }}" max="{{ Auth::user()->max_stamina }}"></progress>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card bg-tungning-paper border-2 border-tungning-wood/30 hover:border-tungning-gold transition-all shadow-md">
                    <div class="card-body items-center text-center">
                        <h2 class="card-title text-tungning-brown italic">⚓ 大員港口</h2>
                        <p class="text-sm text-tungning-wood">進行國際貿易與買賣道具</p>
                        <div class="card-actions mt-4">
                            <button class="btn btn-sm bg-tungning-brown text-white hover:bg-black">前往</button>
                        </div>
                    </div>
                </div>

                <div class="card bg-tungning-paper border-2 border-tungning-wood/30 hover:border-tungning-gold transition-all shadow-md">
                    <div class="card-body items-center text-center">
                        <h2 class="card-title text-tungning-brown italic">🌲 大員林場</h2>
                        <p class="text-sm text-tungning-wood">消耗體力採集木材與皮料</p>
                        <div class="card-actions mt-4">
                            <button class="btn btn-sm bg-tungning-brown text-white hover:bg-black">採集</button>
                        </div>
                    </div>
                </div>

                <div class="card bg-tungning-paper border-2 border-tungning-wood/30 hover:border-tungning-gold transition-all shadow-md">
                    <div class="card-body items-center text-center">
                        <h2 class="card-title text-tungning-brown italic">🏮 府城街市</h2>
                        <p class="text-sm text-tungning-wood">查看懸賞任務與開設店舖</p>
                        <div class="card-actions mt-4">
                            <button class="btn btn-sm bg-tungning-brown text-white hover:bg-black">進入</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>