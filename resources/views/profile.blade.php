<x-app-layout>
    <div class="py-12 bg-[#2d1b0e] min-h-screen font-serif bg-opacity-95" style="background-image: url('{{ asset('images/paper-texture.png') }}'); background-blend-mode: multiply;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="flex items-center justify-between border-b-4 border-tungning-gold pb-4 mb-8 mx-4 sm:mx-0">
                <h2 class="text-4xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                    {{ __('個人卷宗') }}
                </h2>
                <div class="px-4 py-1 bg-tungning-gold text-tungning-brown font-bold rounded shadow-inner">
                    {{ __('機密等級：絕密') }}
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1">
                    <div class="p-6 sm:p-8 bg-tungning-paper border-4 border-tungning-wood shadow-2xl rounded-lg relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-tungning-brown"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-tungning-brown"></div>

                        <livewire:profile.update-avatar-form />
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">

                    <div class="p-6 sm:p-8 bg-tungning-paper border-4 border-tungning-wood shadow-2xl rounded-lg relative">
                        <div class="max-w-xl">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 bg-tungning-paper border-4 border-tungning-wood shadow-2xl rounded-lg relative">
                        <div class="max-w-xl">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 bg-[#3d2311] border-4 border-red-900 shadow-2xl rounded-lg relative">
                        <div class="max-w-xl">
                            <livewire:profile.delete-user-form />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>