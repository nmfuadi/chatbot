<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                {{ __('Informasi Profil') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                {{ __("Perbarui nama lengkap dan alamat email utama akun Terabot.AI Anda.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-1.5">
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold uppercase tracking-wider text-gray-400" />
            <x-text-input id="name" name="name" type="text" 
                class="mt-1 block w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm py-3" 
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-xs font-medium" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-bold uppercase tracking-wider text-gray-400" />
            <x-text-input id="email" name="email" type="email" 
                class="mt-1 block w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm py-3" 
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-xs font-medium" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <p class="text-sm text-amber-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Email Anda belum terverifikasi.') }}
                    </p>

                    <button form="send-verification" class="mt-2 text-xs font-bold text-amber-600 underline hover:text-amber-800 focus:outline-none">
                        {{ __('Klik di sini untuk kirim ulang email verifikasi.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-green-600">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="bg-gray-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('Simpan Perubahan') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-sm text-green-600 font-bold bg-green-50 px-4 py-2 rounded-lg border border-green-100"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Profil Berhasil Disimpan.') }}
                </div>
            @endif
        </div>
    </form>
</section>