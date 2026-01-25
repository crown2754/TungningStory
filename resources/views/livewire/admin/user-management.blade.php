<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                戶籍名冊管理
                <span class="text-sm bg-red-800 text-white px-2 py-1 rounded ml-2 align-middle">
                    @if(auth()->user()->role === 'OM')
                    運營總督
                    @elseif(auth()->user()->role === 'GM')
                    巡查官
                    @else
                    一般平民
                    @endif
                </span>
            </h2>
            <div class="relative w-full md:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="查閱姓名或信箱..."
                    class="input w-full bg-tungning-paper text-tungning-brown border-2 border-tungning-wood focus:border-tungning-gold placeholder-tungning-wood/50 font-bold shadow-inner" />
                <span class="absolute right-3 top-3 text-tungning-wood">🔍</span>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border-4 border-tungning-wood shadow-2xl bg-tungning-paper">
            <table class="table w-full text-tungning-brown">
                <thead class="bg-tungning-brown text-tungning-gold text-lg">
                    <tr>
                        <th class="border-b border-tungning-gold/20">編號</th>
                        <th class="border-b border-tungning-gold/20">開拓者資料</th>
                        <th class="border-b border-tungning-gold/20">身分職等</th>
                        <th class="border-b border-tungning-gold/20">職業</th>
                        <th class="border-b border-tungning-gold/20">資產 (文)</th>
                        <th class="border-b border-tungning-gold/20">體力狀況</th>
                        <th class="border-b border-tungning-gold/20">批示</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tungning-wood/20">
                    @foreach($users as $user)
                    <tr class="hover:bg-tungning-gold/10 transition duration-150">
                        <td class="font-mono text-tungning-wood/70 font-bold">#{{ $user->id }}</td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-black text-lg">{{ $user->name }}</span>
                                <span class="text-xs text-tungning-wood">{{ $user->email }}</span>
                            </div>
                        </td>
                        <td>
                            @if($user->role === 'OM')
                            <span class="badge bg-transparent border border-tungning-wood text-tungning-wood">運營總督</span>
                            @elseif($user->role === 'GM')
                            <span class="badge bg-transparent border border-tungning-wood text-tungning-wood">巡查官</span>
                            @else
                            <span class="badge bg-transparent border border-tungning-wood text-tungning-wood">平民</span>
                            @endif
                        </td>
                        <td class="font-bold">{{ $user->job }}</td>
                        <td class="font-mono font-bold text-lg">{{ number_format($user->gold) }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <progress class="progress progress-error w-16 h-3 bg-tungning-brown/20" value="{{ $user->stamina }}" max="{{ $user->max_stamina }}"></progress>
                                <span class="text-xs font-bold">{{ $user->stamina }}</span>
                            </div>
                        </td>
                        <td>
                            <button wire:click="edit({{ $user->id }})" class="btn btn-sm bg-tungning-wood text-tungning-paper border-none hover:bg-tungning-brown">
                                詳閱
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-4 bg-[#e8e0d5] border-t-2 border-tungning-wood">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-[2px]">
        <div class="bg-tungning-paper w-full max-w-lg p-1 rounded-lg shadow-[0_0_50px_rgba(0,0,0,0.8)] relative border-2 border-tungning-wood">

            <div class="border-2 border-dashed border-tungning-wood/50 p-6 rounded h-full relative">

                <h3 class="text-2xl font-black text-tungning-brown text-center mb-6 border-b-2 border-tungning-wood pb-2 tracking-[0.2em]">
                    人事異動派令
                </h3>

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2 bg-[#e8e0d5] p-3 rounded border border-tungning-wood/30">
                        <label class="block text-xs font-bold text-tungning-wood mb-1">受文者 (姓名/信箱)</label>
                        <div class="text-lg font-black text-tungning-brown">{{ $form['name'] }}</div>
                        <div class="text-sm text-tungning-brown/80">{{ $form['email'] }}</div>
                    </div>

                    <div class="col-span-2">
                        <label class="label">
                            <span class="label-text font-bold text-tungning-brown">
                                授予職權
                                @if(!auth()->user()->isOM())
                                <span class="text-xs text-red-600 ml-2">(僅運營總督可修改)</span>
                                @endif
                            </span>
                        </label>

                        <select wire:model="form.role"
                            class="select select-bordered w-full bg-white text-tungning-brown border-tungning-wood focus:border-tungning-gold font-bold disabled:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed"
                            @if(!auth()->user()->isOM()) disabled @endif>
                            <option value="Player">一般平民 (Player)</option>
                            <option value="GM">巡查官 (GM)</option>
                            <option value="OM">運營總督 (OM)</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-tungning-brown">職業身分</span></label>
                        <input type="text" wire:model="form.job" class="input input-bordered bg-white text-tungning-brown border-tungning-wood" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-tungning-brown">配給資產 (文)</span></label>
                        <input type="number" wire:model="form.gold" class="input input-bordered bg-white text-tungning-brown border-tungning-wood" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-tungning-brown">當前體力</span></label>
                        <input type="number" wire:model="form.stamina" class="input input-bordered bg-white text-tungning-brown border-tungning-wood" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-tungning-brown">體力上限</span></label>
                        <input type="number" wire:model="form.max_stamina" class="input input-bordered bg-white text-tungning-brown border-tungning-wood" />
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-tungning-wood/30">
                    <button wire:click="closeModal" class="btn btn-ghost text-tungning-wood hover:bg-tungning-wood/10 font-bold">
                        收回成命
                    </button>
                    <button wire:click="update" class="btn bg-red-800 text-white border-none hover:bg-red-900 shadow-lg font-bold">
                        用印生效
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif

    <div
        x-data="{ show: false, message: '' }"
        x-on:operation-success.window="
            message = $event.detail.message; 
            show = true; 
            setTimeout(() => show = false, 4000)
        "
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-10 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-90">
        <div class="alert bg-[#4A2C16] text-[#D4AF37] border-2 border-[#8B4513] shadow-[0_0_20px_rgba(0,0,0,0.5)] flex items-center gap-4 rounded-md">
            <div class="p-2 bg-[#D4AF37] text-[#4A2C16] rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div class="flex flex-col">
                <span class="font-black text-lg tracking-widest drop-shadow-md font-serif">
                    批示生效
                </span>
                <span class="text-xs text-[#F5F5DC] opacity-80" x-text="message"></span>
            </div>
        </div>
    </div>

    <div
        x-data="{ show: false, message: '' }"
        x-on:operation-error.window="
            message = $event.detail.message; 
            show = true; 
            setTimeout(() => show = false, 5000)
        "
        class="toast toast-bottom toast-end z-[9999]"
        style="display: none;"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-10"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-10">
        <div class="alert bg-[#7f1d1d] text-[#F5F5DC] border-2 border-[#D4AF37] shadow-[0_0_20px_rgba(255,0,0,0.6)] flex items-center gap-4 rounded-md">

            <div class="p-2 bg-[#D4AF37] text-[#7f1d1d] rounded-full animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <div class="flex flex-col">
                <span class="font-black text-lg tracking-widest drop-shadow-md font-serif border-b border-[#D4AF37]/50 pb-1 mb-1">
                    系統駁回
                </span>
                <span class="text-xs font-bold tracking-wide" x-text="message"></span>
            </div>
        </div>
    </div>

</div>