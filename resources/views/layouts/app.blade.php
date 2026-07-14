<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('messages.store_name'))</title>
    @yield('meta')
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen antialiased flex flex-col">

    {{-- Navigation --}}
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Brand + Links --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-lg font-bold text-gray-900 hover:text-primary transition-colors">
                        {{ __('messages.store_name') }}
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            {{ __('messages.home') }}
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('store.categories.show', $cat->slug) }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                {{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <form action="{{ route('search') }}" method="GET" class="hidden sm:flex items-center">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search') }}..." class="w-48 lg:w-64 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </form>

                    {{-- Cart --}}
                    <a href="{{ route('store.cart.index') }}" class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors" aria-label="{{ __('messages.cart') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                        @endif
                    </a>

                    {{-- Auth section --}}
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                {{ __('messages.dashboard') }}
                            </a>
                        @endif
                        <div class="relative group">
                            <button class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors focus:outline-none">
                                <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden lg:inline">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    {{ __('messages.profile') }}
                                </a>
                                <hr class="my-1 border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            {{ __('messages.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-dark transition-colors">
                            {{ __('messages.register') }}
                        </a>
                    @endauth

                    {{-- Language switcher --}}
                    @if (app()->getLocale() === 'ar')
                        <a href="{{ route('lang.switch', 'en') }}" class="text-xs text-gray-500 hover:text-gray-700 border border-gray-200 rounded px-2 py-1 transition-colors">EN</a>
                    @else
                        <a href="{{ route('lang.switch', 'ar') }}" class="text-xs text-gray-500 hover:text-gray-700 border border-gray-200 rounded px-2 py-1 transition-colors">AR</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        {{-- Flash messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </span>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </span>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    @include('store.partials.footer')

    {{-- Mobile search bar --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-3 sm:hidden z-40">
        <form action="{{ route('search') }}" method="GET">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search') }}..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>
    </div>

</body>
</html>
