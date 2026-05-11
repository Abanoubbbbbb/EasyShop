<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex items-center gap-8">

                <!-- LOGO -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">

                    @auth
                    @php
                    $company = auth()->user()->company;
                    @endphp

                    @if($company && $company->logo)

                    <img src="{{ asset('storage/' . $company->logo) }}"
                        class="h-9 w-9 rounded-full object-cover border"
                        alt="logo">

                    <div class="flex flex-col leading-tight">
                        <span class="font-semibold text-gray-800 text-sm">
                            {{ $company->name }}
                        </span>
                        <span class="text-xs text-gray-400">Company</span>
                    </div>

                    @else

                    <span class="font-bold text-gray-800 text-lg">
                        SaaS App
                    </span>

                    @endif
                    @else
                    <x-application-logo class="h-9 w-auto text-gray-800" />
                    @endauth

                </a>

                <!-- LINKS -->
                <div class="hidden sm:flex items-center space-x-6">

                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        🏠 Home
                    </x-nav-link>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="hidden sm:flex items-center gap-6">

                @auth
                @php
                $cartCount = 0;

                $cart = \App\Models\Cart::where('user_id', auth()->id())
                ->where('status', 'active')
                ->first();

                if ($cart) {
                $cartCount = $cart->items()->sum('quantity');
                }
                @endphp

                <!-- CART -->
                <a href="{{ route('cart.index') }}"
                    class="relative text-gray-700 hover:text-blue-600 font-medium transition">

                    🛒 Cart

                    @if($cartCount > 0)
                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs px-2 rounded-full">
                        {{ $cartCount }}
                    </span>
                    @endif

                </a>
                @endauth

                <!-- USER -->
                <x-dropdown align="right" width="48">

                    <!-- TRIGGER -->
                    <x-slot name="trigger">
                        @auth
                        <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                            👤 {{ auth()->user()->name }}
                        </button>
                        @else
                        <a href="{{ route('login') }}" class="text-blue-600 font-semibold">
                            Login
                        </a>
                        @endauth
                    </x-slot>

                    <!-- CONTENT -->
                    <x-slot name="content">

                        @auth

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <!-- LOGOUT -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                🚪 Logout
                            </button>
                        </form>

                        @endauth

                    </x-slot>

                </x-dropdown>

            </div>

            <!-- MOBILE BUTTON -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-600 hover:bg-gray-100">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path :class="{'hidden': open, 'block': !open}"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                        <path :class="{'hidden': !open, 'block': open}"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t bg-white">

        <div class="px-4 py-5 space-y-3">

            <x-nav-link :href="route('home')" class="block">
                🏠 Home
            </x-nav-link>

            @auth
            <x-nav-link :href="route('cart.index')" class="block">
                🛒 Cart ({{ $cartCount ?? 0 }})
            </x-nav-link>

            <x-nav-link :href="route('profile.edit')" class="block">
                👤 Profile
            </x-nav-link>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="w-full text-left px-3 py-2 text-red-600 hover:bg-gray-100 rounded-md">
                    🚪 Logout
                </button>
            </form>
            @endauth

        </div>

    </div>

</nav>