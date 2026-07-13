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
<body class="bg-gray-50 text-gray-900 min-h-screen antialiased">

    {{-- Navigation --}}
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Brand + Links --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-lg font-bold text-gray-900 hover:text-primary transition-colors">
                        {{ __('messages.store_name') }}
                    </a>
                    <div class="hidden sm:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            {{ __('messages.home') }}
                        </a>
                        @if(isset($categories))
                            @foreach($categories as $cat)
                                <a href="{{ route('store.categories.show', $cat->slug) }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                    {{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-4">
                    {{-- Cart icon (placeholder for Phase 3) --}}

                    {{-- Auth section --}}
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                {{ __('messages.dashboard') }}
                            </a>
                        @endif
                        <div class="relative group">
                            <button class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors focus:outline-none">
                                <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
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
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Flash messages --}}
        @if (session('success'))
            <div data-alert class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button data-dismiss class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div data-alert class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button data-dismiss class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-sm text-gray-500 text-center">&copy; {{ date('Y') }} {{ __('messages.store_name') }}</p>
        </div>
    </footer>

</body>
</html>
