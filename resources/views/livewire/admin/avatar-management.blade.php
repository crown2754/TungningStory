<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md border-b-4 border-tungning-gold pb-2">
                人物圖鑑管理
            </h2>
        </div>

        <div class="bg-tungning-paper p-8 rounded-lg border-4 border-tungning-wood shadow-2xl mb-8 relative">
            <h3 class="text-2xl font-black text-tungning-brown mb-6 border-b-2 border-tungning-wood pb-2">
                繪製新像
            </h3>

            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                <div class="form-control w-full">
                    <label class="label font-bold text-tungning-brown">頭像名稱 (管理員識別用)</label>
                    <input type="text" wire:model="name" placeholder="例：紅巾海盜、長老..." class="input input-bordered w-full bg-[#e8e0d5] text-tungning-brown border-tungning-wood focus:border-tungning-gold" />
                    @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="form-control w-full">
                    <label class="label font-bold text-tungning-brown">畫像檔案</label>
                    <input type="file" wire:model="photo" class="file-input file-input-bordered w-full bg-[#e8e0d5] text-tungning-brown border-tungning-wood" />
                    @error('photo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-between items-center pb-1">
                    <div>
                        @if ($photo)
                        <span class="text-sm text-tungning-brown mr-2">預覽：</span>
                        <img src="{{ $photo->temporaryUrl() }}" class="h-12 w-12 object-cover rounded border-2 border-tungning-gold inline-block">
                        @endif
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="btn bg-red-800 text-tungning-paper border-2 border-tungning-gold hover:bg-red-900 shadow-lg px-6 w-full ml-4">
                        <span wire:loading.remove>入庫存檔</span>
                        <span wire:loading>繪製中...</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($avatars as $avatar)
            <div class="relative group bg-[#e8e0d5] border-2 border-tungning-wood rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all hover:scale-105 hover:border-tungning-gold">

                <div class="aspect-[3/4] w-full bg-gray-200">
                    <img src="{{ $avatar->path }}" class="w-full h-full object-cover" />
                </div>

                <div class="p-2 text-center bg-tungning-wood text-tungning-paper text-xs">
                    <div class="font-bold truncate">{{ $avatar->name }}</div>
                </div>

                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button wire:click="delete({{ $avatar->id }})"
                        wire:confirm="確定要銷毀這張畫像嗎？此操作無法復原。"
                        class="btn btn-xs btn-circle btn-error text-white shadow-md border-white">
                        ✕
                    </button>
                </div>
            </div>
            @endforeach
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
            <span class="font-bold tracking-widest" x-text="message"></span>
        </div>
    </div>
</div>