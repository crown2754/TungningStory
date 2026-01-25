<nav class="-mx-3 flex flex-1 justify-end gap-3">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-md px-4 py-2 bg-tungning-wood text-tungning-paper font-bold shadow hover:bg-tungning-brown hover:text-white transition ring-2 ring-tungning-wood ring-offset-1 focus:outline-none"
        >
            返回府邸
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="rounded-md px-4 py-2 text-tungning-brown font-bold hover:text-red-700 transition focus:outline-none"
        >
            登入
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="rounded-md px-4 py-2 bg-tungning-red text-red-800 font-black border-2 border-red-800 hover:bg-red-800 hover:text-white transition focus:outline-none"
                style="background-image: url('data:image/svg+xml;base64,...');" {{-- 這裡可以用 CSS 做一些紙張紋理 --}}
            >
                簽署名冊
            </a>
        @endif
    @endauth
</nav>