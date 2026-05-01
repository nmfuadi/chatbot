<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-600 rounded-lg shadow-lg shadow-blue-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Pengaturan Akun') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Pribadi</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Perbarui informasi profil akun dan alamat email Anda untuk keperluan login.
                        </p>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div class="p-6 sm:p-10 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-[2rem]">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-bold text-gray-900">Keamanan Kata Sandi</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
                        </p>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div class="p-6 sm:p-10 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-[2rem]">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-bold text-red-600">Bahaya</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Menghapus akun akan menghapus seluruh data bisnis dan histori chat secara permanen.
                        </p>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div class="p-6 sm:p-10 bg-red-50/30 shadow-sm ring-1 ring-red-100 sm:rounded-[2rem]">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>