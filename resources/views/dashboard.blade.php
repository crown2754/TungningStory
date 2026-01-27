<x-app-layout>
    <div class="py-12 bg-[#2d1b0e] min-h-screen font-serif bg-opacity-95 relative" style="background-image: url('{{ asset('images/paper-texture.png') }}'); background-blend-mode: multiply;">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-tungning-brown text-tungning-gold border-2 border-tungning-gold p-4 rounded-t-lg shadow-lg flex justify-between items-center mb-0">
                <h2 class="font-black text-2xl tracking-widest flex items-center gap-2">
                    <span>📜</span>
                    {{ __('東寧府邸 ‧ 角色卷宗') }}
                </h2>
                <span class="text-sm opacity-80">{{ now()->format('Y年m月d日') }}</span>
            </div>

            <div class="bg-tungning-paper border-x-2 border-b-2 border-tungning-wood p-6 sm:p-8 shadow-2xl rounded-b-lg relative overflow-hidden">

                <div class="absolute top-10 right-10 opacity-5 pointer-events-none">
                    <img src="{{ \App\Models\GameSetting::get('backend_logo_url') ?? asset('favicon.ico') }}" class="w-64 h-64 grayscale">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">

                    <div class="md:col-span-1 flex flex-col items-center">
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-br from-tungning-gold to-tungning-wood rounded-lg blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                            <div class="relative p-1 bg-[#2d1b0e] rounded-lg">
                                <img src="{{ auth()->user()->avatar_url }}"
                                    class="w-full max-w-[280px] h-auto object-cover rounded shadow-inner border border-white/10"
                                    alt="Character Avatar">
                            </div>

                            <a href="{{ route('profile') }}" wire:navigate class="absolute bottom-4 right-4 bg-tungning-brown/80 text-tungning-gold p-2 rounded-full hover:bg-tungning-gold hover:text-tungning-brown transition shadow-lg backdrop-blur-sm" title="更換容貌">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                        </div>

                        <div class="mt-6 text-center">
                            <h3 class="text-3xl font-black text-tungning-brown tracking-widest drop-shadow-sm">
                                {{ auth()->user()->name }}
                            </h3>
                            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#e8e0d5] border border-tungning-wood/30 shadow-inner">
                                <span class="w-2 h-2 rounded-full bg-green-600 animate-pulse"></span>
                                <span class="text-sm font-bold text-tungning-wood">{{ auth()->user()->role === 'player' ? '開拓者' : '管理者' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-6">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-[#e8e0d5] p-4 rounded border-l-4 border-green-600 shadow-sm">
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-bold text-gray-600">體力 (Stamina)</span>
                                    <span class="text-xl font-black text-green-800">{{ auth()->user()->stamina }} / {{ auth()->user()->max_stamina }}</span>
                                </div>
                                <progress class="progress progress-success w-full h-3 border border-green-800/20" value="{{ auth()->user()->stamina }}" max="{{ auth()->user()->max_stamina }}"></progress>
                                <div class="text-xs text-right mt-1 text-gray-500">每15分鐘恢復</div>
                            </div>

                            <div class="bg-[#e8e0d5] p-4 rounded border-l-4 border-yellow-600 shadow-sm">
                                <div class="flex justify-between items-end">
                                    <span class="text-sm font-bold text-gray-600">通寶 (Gold)</span>
                                    <span class="text-xl font-black text-yellow-800">{{ number_format(auth()->user()->gold) }} 文</span>
                                </div>
                                <div class="mt-2 text-right">
                                    <a href="#" class="text-xs text-tungning-brown underline hover:text-red-800">前往錢莊 ></a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#3d2311] text-tungning-paper p-6 rounded shadow-inner border border-tungning-gold/30">
                            <h4 class="font-bold text-tungning-gold border-b border-white/10 pb-2 mb-4">身分背景</h4>
                            <div class="grid grid-cols-2 gap-y-4 text-sm">
                                <div>
                                    <span class="block text-gray-400 text-xs">當前職業</span>
                                    <span class="text-lg font-bold">{{ auth()->user()->job ?? '平民' }}</span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 text-xs">所屬商會</span>
                                    <span class="text-lg font-bold">-- 無 --</span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 text-xs">註冊日期</span>
                                    <span class="font-mono">{{ auth()->user()->created_at->format('Y-m-d') }}</span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 text-xs">上次補給</span>
                                    <span class="font-mono text-gray-300">{{ auth()->user()->last_stamina_restore_at ? auth()->user()->last_stamina_restore_at->diffForHumans() : '未曾' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/50 p-4 rounded border border-tungning-wood/20">
                            <h4 class="font-bold text-tungning-brown mb-2">📜 府邸公告</h4>
                            <p class="text-sm text-gray-700">
                                歡迎來到東寧物語！請先至「個人卷宗」確認您的容貌設定，隨後可前往「市集」或「碼頭」進行探索 (功能開發中)。
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>