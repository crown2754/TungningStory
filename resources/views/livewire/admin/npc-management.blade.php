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

        {{-- [修改] 將 lg:grid-cols-3 改為 lg:grid-cols-2 (兩欄)，讓卡片更寬 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($npcs as $npc)
            <div class="bg-tungning-paper border-4 border-tungning-wood rounded-lg p-4 shadow-xl relative group">
                <div class="flex gap-6">
                    {{-- [修改] 將圖片大小從 w-20 h-20 加大為 w-32 h-32 (128px)，讓頭像更清楚 --}}
                    <div class="w-32 h-32 flex-shrink-0 border-2 border-tungning-gold rounded bg-gray-300 overflow-hidden shadow-inner">
                        @if($npc->avatar)
                        <img src="{{ $npc->avatar->path }}" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-gray-500 bg-gray-200">無頭像</div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <h3 class="font-black text-2xl text-tungning-brown truncate mb-2">
                            {{ $npc->name }}
                            <span class="text-sm bg-tungning-wood text-white px-2 py-0.5 rounded ml-2 align-middle">{{ $npc->title }}</span>
                        </h3>
                        <p class="text-base text-gray-700 line-clamp-3 leading-relaxed mb-2">{{ $npc->description }}</p>
                        <div class="text-sm text-tungning-gold font-bold">
                            {{ $npc->is_active ? '🟢 活躍中' : '🔴 隱藏' }}
                        </div>
                    </div>
                </div>

                <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 rounded p-1 shadow-md backdrop-blur-sm">
                    <button wire:click="edit({{ $npc->id }})" class="text-blue-700 hover:text-blue-900 font-bold px-2 py-1">編輯</button>
                    <button wire:click="delete({{ $npc->id }})" wire:confirm="確定要刪除這位 NPC 嗎？" class="text-red-700 hover:text-red-900 font-bold px-2 py-1">刪除</button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $npcs->links() }}
        </div>

        @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 transition-opacity">
            <div class="bg-[#e8e0d5] border-4 border-tungning-brown rounded-lg shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all scale-100">

                <div class="bg-tungning-brown text-tungning-gold p-4 font-bold text-xl flex justify-between items-center shadow-md">
                    <span class="tracking-widest">{{ $editingNpcId ? '編輯 NPC 資料' : '招募新 NPC' }}</span>
                    <button wire:click="$set('showModal', false)" class="hover:text-white text-2xl leading-none">&times;</button>
                </div>

                <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label font-bold text-tungning-brown">姓名</label>
                            <input type="text" wire:model="name" class="input input-bordered bg-white border-tungning-wood focus:border-tungning-gold focus:ring-1 focus:ring-tungning-gold" placeholder="例：楊英">
                            @error('name') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-tungning-brown">稱號/職位</label>
                            <input type="text" wire:model="title" class="input input-bordered bg-white border-tungning-wood focus:border-tungning-gold focus:ring-1 focus:ring-tungning-gold" placeholder="例：戶部主事">
                            @error('title') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-tungning-brown">綁定頭像 (請先在人物圖鑑上傳)</label>
                        <select wire:model.live="avatar_id" class="select select-bordered bg-white border-tungning-wood w-full focus:border-tungning-gold focus:ring-1 focus:ring-tungning-gold">
                            <option value="">-- 請選擇頭像 --</option>
                            @foreach($avatars as $avatar)
                            <option value="{{ $avatar->id }}">{{ $avatar->name }}</option>
                            @endforeach
                        </select>
                        @if($avatar_id)
                        <div class="mt-3 flex items-center gap-3 p-3 bg-white/50 rounded border border-gray-300">
                            <span class="text-sm font-bold text-gray-600">預覽：</span>
                            @php
                            $selectedAvatar = $avatars->find($avatar_id);
                            @endphp
                            @if($selectedAvatar)
                            <img src="{{ $selectedAvatar->path }}" class="h-20 w-20 object-cover rounded border-2 border-tungning-gold shadow-sm">
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-tungning-brown">人物介紹</label>
                        <textarea wire:model="description" class="textarea textarea-bordered bg-white border-tungning-wood h-24 focus:border-tungning-gold focus:ring-1 focus:ring-tungning-gold text-base leading-relaxed" placeholder="這位 NPC 的背景故事..."></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-tungning-brown">預設問候語</label>
                        <input type="text" wire:model="greeting" class="input input-bordered bg-white border-tungning-wood focus:border-tungning-gold focus:ring-1 focus:ring-tungning-gold" placeholder="例：這位兄台，今日庫房盤點...">
                    </div>

                    <div class="flex items-center gap-4 mt-4 p-3 bg-white/40 rounded border border-gray-300">
                        <label class="cursor-pointer label gap-3">
                            <input type="checkbox" wire:model="is_active" class="checkbox checkbox-primary border-tungning-brown checked:bg-tungning-brown" />
                            <span class="label-text font-bold text-tungning-brown text-lg">啟用此 NPC</span>
                        </label>
                    </div>

                </div>

                <div class="p-4 bg-gray-200/80 border-t border-gray-300 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)" class="btn btn-ghost text-gray-600 hover:bg-gray-300">取消</button>
                    <button wire:click="save" class="btn bg-tungning-brown text-tungning-paper hover:bg-tungning-wood border-none px-6 text-lg shadow-md">
                        {{ $editingNpcId ? '更新資料' : '確認新增' }}
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Toast 通知 --}}
    <div
        x-data="{ show: false, message: '' }"
        x-on:operation-success.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2">
        <div class="alert bg-[#4A2C16] text-[#D4AF37] border-2 border-[#8B4513] shadow-xl flex items-center gap-3 rounded-lg px-6 py-4">
            <span class="text-2xl">📜</span>
            <span class="font-bold tracking-widest text-lg" x-text="message"></span>
        </div>
    </div>
</div>