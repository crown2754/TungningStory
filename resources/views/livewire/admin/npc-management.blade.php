<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8 border-b-4 border-tungning-gold pb-4">
            <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                NPC 名冊管理
            </h2>
            <button wire:click="create" class="btn bg-tungning-brown text-tungning-paper border-2 border-tungning-gold hover:bg-tungning-wood">
                + 新增 NPC
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($npcs as $npc)
            <div class="bg-tungning-paper border-4 border-tungning-wood rounded-lg p-4 shadow-xl relative group">
                <div class="flex gap-4">
                    <div class="w-20 h-20 flex-shrink-0 border-2 border-tungning-gold rounded bg-gray-300 overflow-hidden">
                        @if($npc->avatar)
                        <img src="{{ $npc->avatar->path }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-gray-500">無頭像</div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-black text-xl text-tungning-brown truncate">
                            {{ $npc->name }}
                            <span class="text-xs bg-tungning-wood text-white px-1 rounded ml-1">{{ $npc->title }}</span>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $npc->description }}</p>
                        <div class="mt-2 text-xs text-tungning-gold font-bold">
                            {{ $npc->is_active ? '🟢 活躍中' : '🔴 隱藏' }}
                        </div>
                    </div>
                </div>

                <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white/80 rounded p-1">
                    <button wire:click="edit({{ $npc->id }})" class="text-blue-600 hover:text-blue-800 font-bold">編輯</button>
                    <button wire:click="delete({{ $npc->id }})" wire:confirm="確定要刪除這位 NPC 嗎？" class="text-red-600 hover:text-red-800 font-bold">刪除</button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $npcs->links() }}
        </div>

        @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
            <div class="bg-[#e8e0d5] border-4 border-tungning-brown rounded-lg shadow-2xl w-full max-w-2xl overflow-hidden">

                <div class="bg-tungning-brown text-tungning-gold p-4 font-bold text-xl flex justify-between">
                    <span>{{ $editingNpcId ? '編輯 NPC 資料' : '招募新 NPC' }}</span>
                    <button wire:click="$set('showModal', false)" class="hover:text-white">✕</button>
                </div>

                <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label font-bold text-tungning-brown">姓名</label>
                            <input type="text" wire:model="name" class="input input-bordered bg-white border-tungning-wood" placeholder="例：楊英">
                            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-tungning-brown">稱號/職位</label>
                            <input type="text" wire:model="title" class="input input-bordered bg-white border-tungning-wood" placeholder="例：戶部主事">
                            @error('title') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-tungning-brown">綁定頭像 (請先在人物圖鑑上傳)</label>
                        <select wire:model.live="avatar_id" class="select select-bordered bg-white border-tungning-wood w-full">
                            <option value="">-- 請選擇頭像 --</option>
                            @foreach($avatars as $avatar)
                            <option value="{{ $avatar->id }}">{{ $avatar->name }}</option>
                            @endforeach
                        </select>
                        @if($avatar_id)
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs">預覽：</span>
                            <img src="{{ $avatars->find($avatar_id)->path }}" class="h-24 w-32 rounded border border-gray-400">
                        </div>
                        @endif
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-tungning-brown">人物介紹</label>
                        <textarea wire:model="description" class="textarea textarea-bordered bg-white border-tungning-wood h-20" placeholder="這位 NPC 的背景故事..."></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-tungning-brown">預設問候語</label>
                        <input type="text" wire:model="greeting" class="input input-bordered bg-white border-tungning-wood" placeholder="例：這位兄台，今日庫房盤點...">
                    </div>

                    <div class="flex items-center gap-4 mt-2">
                        <label class="cursor-pointer label gap-2">
                            <input type="checkbox" wire:model="is_active" class="checkbox checkbox-primary" />
                            <span class="label-text font-bold text-tungning-brown">啟用此 NPC</span>
                        </label>
                    </div>

                </div>

                <div class="p-4 bg-gray-200 flex justify-end gap-2">
                    <button wire:click="$set('showModal', false)" class="btn btn-ghost text-gray-600">取消</button>
                    <button wire:click="save" class="btn bg-tungning-brown text-tungning-paper hover:bg-tungning-wood border-none">
                        {{ $editingNpcId ? '更新資料' : '確認新增' }}
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>

    <div
        x-data="{ show: false, message: '' }"
        x-on:operation-success.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition>
        <div class="alert bg-[#4A2C16] text-[#D4AF37] border-2 border-[#8B4513] shadow-lg flex items-center gap-4 rounded-md">
            <span class="font-bold tracking-widest" x-text="message"></span>
        </div>
    </div>
</div>