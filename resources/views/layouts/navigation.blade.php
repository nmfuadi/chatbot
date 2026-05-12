<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-16 w-auto object-contain" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('profile.index')" :active="request()->routeIs('profile.*')">
                        {{ __('Profil & Status') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.members')" :active="request()->routeIs('admin.members')">
                            {{ __('Kelola Member') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                            {{ __('Manajemen Paket') }}
                        </x-nav-link>
                        <x-nav-link :href="route('wa.monitor')" :active="request()->routeIs('wa.monitor')">
                            {{ __('Status WhatsApp') }}
                        </x-nav-link>

                        <x-nav-link :href="route('server.monitor')" :active="request()->routeIs('server.monitor')">
                            {{ __('Monitoring Server') }}
                        </x-nav-link>

                        <x-nav-link :href="route('traffic.monitor')" :active="request()->routeIs('traffic.monitor')">
                             {{ __('n8n & AI Monitor') }}
                         </x-nav-link>

                         <x-nav-link :href="route('admin.monitor.logs')" :active="request()->routeIs('admin.monitor.logs')">
                             {{ __(' Log Monitoring') }}
                         </x-nav-link>

                        

                    @elseif(Auth::user()->role === 'member')
                        <x-nav-link :href="route('user.invoice.index')" :active="request()->routeIs('user.invoice.index')">
                            {{ __('Tagihan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('member.whatsapp.setup')" :active="request()->routeIs('member.whatsapp.setup')">
                            {{ __('Setup whatsapp') }}
                        </x-nav-link>
                        <x-nav-link :href="route('member.pk')" :active="request()->routeIs('member.pk')">
                            {{ __('SOP & Product') }}
                        </x-nav-link>
                        <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')">
                            Data Customer
                        </x-nav-link>
                        <x-nav-link :href="route('catalogs.index')" :active="request()->routeIs('catalogs.*')">
                            {{ __('Katalog Produk') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                Logout
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('profile.index')" :active="request()->routeIs('profile.*')">
                {{ __('Profil & Status') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.members')" :active="request()->routeIs('admin.members')">
                    {{ __('Kelola Member') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                    {{ __('Manajemen Paket') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('wa.monitor')" :active="request()->routeIs('wa.monitor')">
                     {{ __('Status WhatsApp') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('server.monitor')" :active="request()->routeIs('server.monitor')">
                     {{ __('Server Monitor') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('traffic.monitor')" :active="request()->routeIs('traffic.monitor')">
                     {{ __('n8n & AI Monitor') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.monitor.logs')" :active="request()->routeIs('admin.monitor.logs')">
                             {{ __(' Log Monitoring') }}
                </x-responsive-nav-link>

                

            @elseif(Auth::user()->role === 'member')
                <x-responsive-nav-link :href="route('user.invoice.index')" :active="request()->routeIs('user.invoice.index')">
                    {{ __('Tagihan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('member.whatsapp.setup')" :active="request()->routeIs('member.whatsapp.setup')">
                    {{ __('Setup whatsapp') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('member.pk')" :active="request()->routeIs('member.pk')">
                    {{ __('SOP & Product') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')">
                    Data Customer
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('catalogs.index')" :active="request()->routeIs('catalogs.*')">
                    {{ __('Katalog Produk') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>