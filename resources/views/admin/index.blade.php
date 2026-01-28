<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-tungning-brown leading-tight tracking-widest" style="font-family: 'Noto Serif TC', serif;">
            {{ __('東寧府邸 ‧ 營運總署') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                <div class="col-span-1 space-y-4">
                    <div class="bg-tungning-wood p-1 rounded-lg shadow-lg">
                        <div class="bg-tungning-paper p-4 rounded border border-tungning-brown/30">
                            <h3 class="text-tungning-brown font-bold mb-4 border-b-2 border-tungning-brown pb-2">卷宗目錄</h3>
                            
                            <a href="{{ route('admin.index') }}" class="block p-3 mb-2 rounded hover:bg-tungning-gold/20 text-tungning-brown font-bold transition flex items-center">
                                <span class="mr-2">📜</span> 營運概況
                            </a>
                            
                            @if(Auth::user()->isOM())
                                <a href="{{ route('admin.users') }}" class="block p-3 mb-2 rounded hover:bg-tungning-gold/20 text-tungning-brown font-bold transition flex items-center">
                                    <span class="mr-2">👥</span> 戶籍名冊管理
                                </a>
                                
                                <a href="#" class="block p-3 rounded text-tungning-wood/50 cursor-not-allowed flex items-center">
                                    <span class="mr-2">文</span> 國庫賑災 (籌備中)
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-span-3">
                    <div class="bg-tungning-paper p-8 rounded-lg border-4 border-tungning-wood shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 opacity-5 pointer-events-none">
                            <svg width="200" height="200" viewBox="0 0 100 100" fill="red"><circle cx="50" cy="50" r="40" /></svg>
                        </div>

                        <h3 class="text-3xl font-black text-tungning-brown mb-6 border-b-4 border-double border-tungning-wood pb-3">
                            營運數據看板
                        </h3>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="bg-[#e8e0d5] p-6 rounded border border-tungning-wood/30 text-center shadow-inner">
                                <div class="text-sm text-tungning-wood font-bold mb-1">當前官階</div>
                                <div class="text-2xl font-black text-tungning-brown">{{ Auth::user()->role }}</div>
                            </div>
                            <div class="bg-[#e8e0d5] p-6 rounded border border-tungning-wood/30 text-center shadow-inner">
                                <div class="text-sm text-tungning-wood font-bold mb-1">大員府運作</div>
                                <div class="text-2xl font-black text-green-700">正常</div>
                            </div>
                            <div class="bg-[#e8e0d5] p-6 rounded border border-tungning-wood/30 text-center shadow-inner">
                                <div class="text-sm text-tungning-wood font-bold mb-1">今日登入</div>
                                <div class="text-2xl font-black text-tungning-brown">--</div>
                            </div>
                        </div>

                        <div class="mt-8 p-4 bg-red-100/50 border-l-4 border-red-800 text-red-900 rounded">
                            <p class="font-bold flex items-center">
                                <span class="text-xl mr-2">⚠️</span> 
                                諭令：管理員之一切操作皆受史官紀錄，切勿濫用職權。
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>