<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use Livewire\Attributes\On; // 引入監聽屬性
use App\Models\GameSetting; // 引入設定模型

new class extends Component
{
    public $logoUrl;

    public function mount()
    {
        // 初始化時讀取 Logo 設定
        $this->refreshLogo();
    }

    /**
     * 監聽 'logo-updated' 事件
     * 當後台 SystemSettings 更新圖片時，這裡會自動被觸發
     */
    #[On('logo-updated')]
    public function refreshLogo()
    {
        $this->logoUrl = GameSetting::get('backend_logo_url');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-tungning-brown border-b-4 border-tungning-gold shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ request()->is('admin*') ? route('admin.index') : route('dashboard') }}" wire:navigate>
                        @if($logoUrl)
                        <img src="{{ $logoUrl }}"
                            class="block h-10 w-auto drop-shadow-md hover:scale-105 transition duration-300 object-contain"
                            alt="府邸官印" />
                        @else
                        <x-application-logo class="block h-10 w-auto fill-current text-tungning-gold drop-shadow-md" />
                        @endif
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    @if(request()->is('admin*'))
                    {{-- [後台模式 - 桌面版] --}}
                    <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" wire:navigate>
                        {{ __('營運總署') }}
                    </x-nav-link>

                    @if(auth()->user()->isGM())
                    <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" wire:navigate>
                        {{ __('戶籍名冊') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" wire:navigate>
                        {{ __('府庫規章') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.items')" :active="request()->routeIs('admin.items')" wire:navigate>
                        {{ __('物資總署') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.avatars')" :active="request()->routeIs('admin.avatars')" wire:navigate>
                        {{ __('人物圖鑑') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.npcs')" :active="request()->routeIs('admin.npcs')" wire:navigate>
                        {{ __('NPC名冊') }}
                    </x-nav-link>
                    @endif

                    {{-- 分隔線 --}}
                    <div class="flex items-center ml-4">
                        <span class="text-tungning-gold/30">|</span>
                    </div>

                    <x-nav-link :href="route('dashboard')" :active="false" wire:navigate class="opacity-70 hover:opacity-100">
                        {{ __('⬅ 返回府邸') }}
                    </x-nav-link>

                    @else
                    {{-- [前台模式 - 桌面版] --}}

                    {{-- 1. 本家選單 --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-tungning-paper/70 hover:text-white hover:border-tungning-gold/50 focus:outline-none focus:text-white transition duration-150 ease-in-out h-16">
                                    <span>{{ __('本家') }}</span>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="bg-tungning-paper border border-tungning-wood rounded-md overflow-hidden">
                                    <x-dropdown-link :href="route('dashboard')" wire:navigate class="text-tungning-brown hover:bg-tungning-gold/20 font-bold">
                                        {{ __('據點總覽') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('shop.index')" wire:navigate class="text-tungning-brown hover:bg-tungning-gold/20 font-bold">
                                        {{ __('我的商號') }}
                                    </x-dropdown-link>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- 2. [新增] 承天府 (地圖) 選單 --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-tungning-paper/70 hover:text-white hover:border-tungning-gold/50 focus:outline-none focus:text-white transition duration-150 ease-in-out h-16">
                                    <span>{{ __('承天府') }}</span>
                                    <div class="ms-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M15 21h-2v-2h2v2zm4 0h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2v-2h2v2zm-4 4h-2v-2h2v2zm-4 4H9v-2h2v2zm-4 0H5v-2h2v2zm0-4H5v-2h2v2zm0-4H5v-2h2v2zm4 4H9v-2h2v2zm8-11.17V5.83V3H5v2.83v1.34l7 3.5 7-3.5zM5 19v-2h14v2H5z" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="bg-tungning-paper border border-tungning-wood rounded-md overflow-hidden">
                                    <x-dropdown-link :href="route('market')" wire:navigate class="text-tungning-brown hover:bg-tungning-gold/20 font-bold flex items-center gap-2">
                                        <span>🏮</span> {{ __('大井頭市集') }}
                                    </x-dropdown-link>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    @if(auth()->user()->isGM())
                    <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')" wire:navigate>
                        {{ __('進入營運總署') }}
                    </x-nav-link>
                    @endif
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-tungning-gold/30 text-sm leading-4 font-bold rounded-md text-tungning-paper bg-[#3d2311] hover:text-white hover:border-tungning-gold focus:outline-none transition ease-in-out duration-150 shadow-inner">

                            <img src="{{ auth()->user()->avatar_url }}"
                                class="h-8 w-8 rounded object-cover border border-tungning-gold mr-2 bg-gray-800"
                                alt="Avatar">

                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            @if(auth()->user()->isOM())
                            <span class="ml-2 text-xs bg-red-800 text-white px-1 rounded border border-red-600">總督</span>
                            @elseif(auth()->user()->isGM())
                            <span class="ml-2 text-xs bg-tungning-wood text-white px-1 rounded border border-tungning-gold/50">巡查</span>
                            @endif

                            <div class="ms-1 text-tungning-gold">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-tungning-paper border border-tungning-wood rounded-md overflow-hidden">
                            <x-dropdown-link :href="route('profile')" wire:navigate class="text-tungning-brown hover:bg-tungning-gold/20 font-bold">
                                {{ __('個人卷宗') }}
                            </x-dropdown-link>

                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link class="text-tungning-brown hover:bg-tungning-gold/20 font-bold">
                                    {{ __('告老還鄉 (登出)') }}
                                </x-dropdown-link>
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-tungning-gold hover:text-white hover:bg-tungning-wood focus:outline-none focus:bg-tungning-wood focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 手機版選單區域 --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#3d2311] border-t border-tungning-gold/30">
        <div class="pt-2 pb-3 space-y-1">

            @if(request()->is('admin*'))
            {{-- [後台模式 - 手機版] --}}
            <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')" wire:navigate>
                {{ __('營運總署') }}
            </x-responsive-nav-link>

            @if(auth()->user()->isGM())
            <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" wire:navigate>
                {{ __('戶籍名冊') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" wire:navigate>
                {{ __('府庫規章') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.items')" :active="request()->routeIs('admin.items')" wire:navigate>
                {{ __('物資總署') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.avatars')" :active="request()->routeIs('admin.avatars')" wire:navigate>
                {{ __('人物圖鑑') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.npcs')" :active="request()->routeIs('admin.npcs')" wire:navigate>
                {{ __('NPC名冊') }}
            </x-responsive-nav-link>
            @endif

            <div class="border-t border-tungning-gold/20 my-2"></div>

            <x-responsive-nav-link :href="route('dashboard')" :active="false" wire:navigate class="text-tungning-gold/70">
                {{ __('⬅ 返回府邸') }}
            </x-responsive-nav-link>

            @else
            {{-- [前台模式 - 手機版] --}}

            <div class="border-l-4 border-tungning-gold ml-2 pl-2 my-2">
                <div class="text-xs text-tungning-gold/50 font-bold mb-1 ml-2">本家</div>

                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('據點總覽') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')" wire:navigate>
                    {{ __('我的商號') }}
                </x-responsive-nav-link>
            </div>

            {{-- [新增] 承天府 (地圖) 手機版 --}}
            <div class="border-l-4 border-red-800 ml-2 pl-2 my-2">
                <div class="text-xs text-red-800/70 font-bold mb-1 ml-2">承天府 (地圖)</div>

                <x-responsive-nav-link :href="route('market')" :active="request()->routeIs('market')" wire:navigate>
                    🏮 {{ __('大井頭市集') }}
                </x-responsive-nav-link>
            </div>

            @if(auth()->user()->isGM())
            <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')" wire:navigate>
                {{ __('進入營運總署') }}
            </x-responsive-nav-link>
            @endif
            @endif

        </div>

        <div class="pt-4 pb-1 border-t border-tungning-gold/30 bg-[#2d1b0e]">
            <div class="px-4">
                <div class="font-medium text-base text-tungning-gold" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-400">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('個人卷宗') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('登出') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>