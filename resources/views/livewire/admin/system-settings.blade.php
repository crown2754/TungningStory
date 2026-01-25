<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md border-b-4 border-tungning-gold pb-2">
                府庫規章設定
            </h2>
        </div>

        <div class="bg-tungning-paper p-8 rounded-lg border-4 border-tungning-wood shadow-2xl mb-8 relative">
            <h3 class="text-2xl font-black text-tungning-brown mb-6 border-b-2 border-tungning-wood pb-2">
                府邸官印 (Logo) 設定
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="text-center p-6 bg-tungning-wood/10 rounded border-2 border-dashed border-tungning-wood">
                        <span class="block text-sm font-bold text-tungning-brown mb-2">當前使用中</span>
                        <img src="{{ $settings['backend_logo_url'] ?? asset('favicon.ico') }}" class="h-20 mx-auto drop-shadow-lg" />
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-tungning-brown">鑄造新印 (上傳圖片)</label>
                        <input type="file" wire:model="newLogo" class="file-input file-input-bordered file-input-sm w-full bg-[#e8e0d5] text-tungning-brown border-tungning-wood" />
                        @error('newLogo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        
                        <button wire:click="uploadLogo" wire:loading.attr="disabled" class="btn btn-sm mt-2 bg-tungning-wood text-white border-none hover:bg-tungning-brown w-full">
                            <span wire:loading.remove>上傳並啟用</span>
                            <span wire:loading>鑄造中...</span>
                        </button>
                    </div>
                </div>

                <div>
                    <span class="block text-sm font-bold text-tungning-brown mb-2">歷史庫存 (點擊切換)</span>
                    <div class="grid grid-cols-4 gap-2 max-h-64 overflow-y-auto p-2 bg-[#e8e0d5] rounded border border-tungning-wood/30">
                        @foreach($logoHistory as $asset)
                            <div 
                                wire:click="selectLogo('{{ $asset->path }}')"
                                class="cursor-pointer relative group rounded overflow-hidden border-2 transition-all hover:scale-105 {{ (isset($settings['backend_logo_url']) && $settings['backend_logo_url'] == $asset->path) ? 'border-red-600 ring-2 ring-red-600/50' : 'border-transparent hover:border-tungning-gold' }}"
                            >
                                <img src="{{ $asset->path }}" class="w-full h-full object-cover aspect-square" />
                                
                                @if(isset($settings['backend_logo_url']) && $settings['backend_logo_url'] == $asset->path)
                                <div class="absolute top-0 right-0 bg-red-600 text-white text-xs p-0.5 rounded-bl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-tungning-paper p-8 rounded-lg border-4 border-tungning-wood shadow-2xl relative">

            <div class="absolute top-4 right-4 opacity-10 pointer-events-none transform rotate-12">
                <div class="w-32 h-32 border-4 border-red-800 rounded-full flex items-center justify-center">
                    <span class="text-red-800 font-black text-4xl">核定</span>
                </div>
            </div>

            <form wire:submit.prevent="update" class="space-y-8">

                @foreach($settingModels as $key => $model)
                    
                    {{-- [重要] 這裡跳過 logo 設定，因為上面已經有專屬區塊了 --}}
                    @if($key === 'backend_logo_url') @continue @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-tungning-wood/20 pb-6 last:border-0">
                        <div class="md:col-span-1">
                            <label class="block text-xl font-bold text-tungning-brown mb-1">
                                {{ $model->name }}
                            </label>
                            <p class="text-xs text-tungning-wood font-bold opacity-80">
                                {{ $model->description }}
                            </p>
                        </div>

                        <div class="md:col-span-2 relative">
                            <input type="{{ is_numeric($settings[$key]) ? 'number' : 'text' }}"
                                wire:model="settings.{{ $key }}"
                                class="input w-full bg-[#e8e0d5] text-tungning-brown text-xl font-black border-2 border-tungning-wood focus:border-tungning-gold shadow-inner text-right pr-12" />

                            <span class="absolute right-4 top-3 text-tungning-wood font-bold">
                                @if(str_contains($key, 'gold')) 文 
                                @elseif(str_contains($key, 'stamina')) 點 
                                @else &nbsp; @endif
                            </span>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end pt-4">
                    <button type="submit" class="btn bg-red-800 text-tungning-paper border-2 border-tungning-gold hover:bg-red-900 hover:border-white shadow-[0_0_15px_rgba(212,175,55,0.4)] text-lg px-8 font-black tracking-widest transition transform hover:scale-105">
                        修訂規章
                    </button>
                </div>
            </form>

        </div>
    </div>

    <div
        x-data="{ show: false, message: '' }"
        x-on:operation-success.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition.duration.500ms>
        <div class="alert bg-[#4A2C16] text-[#D4AF37] border-2 border-[#8B4513] shadow-lg flex items-center gap-4 rounded-md">
            <div class="p-2 bg-[#D4AF37] text-[#4A2C16] rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-lg tracking-widest drop-shadow-md font-serif">設定已更新</span>
                <span class="text-xs text-[#F5F5DC] opacity-80" x-text="message"></span>
            </div>
        </div>
    </div>
</div>