<section class="relative">
    <header>
        <h2 class="text-xl font-bold text-tungning-brown border-b-2 border-tungning-wood pb-2 mb-4">
            {{ __('更換容貌') }}
        </h2>
        <p class="mt-1 text-sm text-tungning-wood font-bold">
            {{ __('選擇一張最能代表您在東寧冒險的畫像。') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <div class="flex justify-center mb-6">
            <div class="relative w-32 h-40 border-4 border-tungning-gold rounded shadow-xl bg-gray-200 overflow-hidden">
                @if($selectedAvatarId)
                <img src="{{ $avatars->find($selectedAvatarId)->path }}" class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full text-tungning-wood font-bold">未選擇</div>
                @endif

                <div class="absolute bottom-0 w-full bg-tungning-brown/80 text-white text-xs text-center py-1">
                    目前容貌
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 max-h-64 overflow-y-auto p-2 border border-tungning-wood/30 rounded bg-[#e8e0d5]">
            @foreach($avatars as $avatar)
            <button
                wire:click="selectAvatar({{ $avatar->id }})"
                class="relative aspect-[3/4] rounded overflow-hidden border-2 transition-all transform hover:scale-105 hover:z-10 focus:outline-none 
                    {{ $selectedAvatarId == $avatar->id ? 'border-red-600 ring-2 ring-red-600 shadow-lg scale-105' : 'border-tungning-wood/50 hover:border-tungning-gold' }}">
                <img src="{{ $avatar->path }}" class="w-full h-full object-cover" loading="lazy">

                @if($selectedAvatarId == $avatar->id)
                <div class="absolute top-0 right-0 bg-red-600 text-white w-5 h-5 flex items-center justify-center rounded-bl">
                    ✓
                </div>
                @endif
            </button>
            @endforeach
        </div>

        <div class="flex items-center gap-4">
            <button wire:click="updateAvatar" class="btn bg-tungning-brown text-tungning-paper border-2 border-tungning-gold hover:bg-tungning-wood hover:border-white w-full">
                {{ __('確認更換') }}
            </button>

            <div x-data="{ show: false, message: '' }"
                x-on:operation-success.window="show = true; setTimeout(() => show = false, 2000)"
                x-show="show"
                x-transition
                class="text-sm text-green-700 font-bold"
                style="display: none;">
                {{ __('已保存') }}
            </div>
        </div>
    </div>
</section>