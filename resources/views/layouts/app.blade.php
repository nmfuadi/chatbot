<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased text-slate-800 bg-slate-50 overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <div class="flex h-screen w-full">
            
            @include('layouts.navigation')

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                
                <header class="bg-white shadow-sm border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 shrink-0">
                    
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 mr-2 text-slate-500 hover:text-blue-600 focus:outline-none rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <div class="flex-1 font-semibold text-lg text-slate-800">
                        @if (isset($header))
                            {{ $header }}
                        @endif
                    </div>

                    <div class="relative ml-4" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-2 px-3 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-600 bg-white hover:bg-slate-50 focus:outline-none transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>

                        <div x-show="profileOpen" x-transition.opacity style="display: none;" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profile Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @if(Auth::check() && Auth::user()->force_password_change)
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center z-[100]">
            <div class="bg-white p-6 rounded-2xl shadow-xl w-96 max-w-[90%]">
                <h2 class="text-xl font-bold mb-3 text-rose-600 flex items-center gap-2">⚠️ Perhatian!</h2>
                <p class="text-slate-600 mb-5 text-sm leading-relaxed">Anda wajib mengganti password untuk alasan keamanan sebelum melanjutkan akses ke Dashboard.</p>
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition">Ganti Password Sekarang</button>
                </form>
            </div>
        </div>
        @endif
        
    </body>
</html>