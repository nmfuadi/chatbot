<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                {{ __('Update Kata Sandi') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                {{ __('Pastikan akun Anda menggunakan kata sandi yang aman untuk melindungi data bisnis.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-1.5">
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" class="text-xs font-bold uppercase tracking-wider text-gray-400" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" 
                class="mt-1 block w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm py-3" 
                autocomplete="current-password" 
                placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs font-medium" />
        </div>

        <div class="space-y-1.5">
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" class="text-xs font-bold uppercase tracking-wider text-gray-400" />
            <x-text-input id="update_password_password" name="password" type="password" 
                class="mt-1 block w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm py-3" 
                autocomplete="new-password" 
                placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs font-medium" />
        </div>

        <div class="space-y-1.5">
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="text-xs font-bold uppercase tracking-wider text-gray-400" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="mt-1 block w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm py-3" 
                autocomplete="new-password" 
                placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs font-medium" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="bg-gray-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                {{ __('Simpan Kata Sandi') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
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
                    {{ __('Berhasil Diperbarui.') }}
                </div>
            @endif
        </div>
    </form>
</section>