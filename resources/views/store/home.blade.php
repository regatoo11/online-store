@extends('layouts.app')
@section('title', __('messages.store_name'))

@section('content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-primary via-primary-dark to-blue-900 text-white -mx-4 sm:-mx-6 lg:-mx-8 -mt-8 mb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="max-w-2xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                {{ __('messages.welcome') }}
            </h1>
            <p class="text-lg text-blue-100 mb-8">
                {{ __('messages.store_description') }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('store.products.index') }}" class="inline-flex items-center px-8 py-3 bg-white text-primary font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-lg">
                    {{ __('messages.products') }}
                    <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'mr-2 rotate-180' : 'ml-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @if($categories->count())
                    <a href="{{ route('store.categories.show', $categories->first()->slug) }}" class="inline-flex items-center px-8 py-3 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-colors">
                        {{ __('messages.categories') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
@if($categories->count())
<section class="mb-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.categories') }}</h2>
        <a href="{{ route('store.products.index') }}" class="text-sm text-primary hover:text-primary-dark font-medium">
            {{ __('messages.view_all') }} &rarr;
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('store.categories.show', $category->slug) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md hover:border-primary/20 transition-all group">
                @if($category->media->count())
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $category->media->first()->file_path) }}" alt="{{ $category->name_ar }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-16 h-16 mx-auto mb-3 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                @endif
                <span class="text-sm font-medium text-gray-900 group-hover:text-primary transition-colors">{{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- Featured Products --}}
@if($featuredProducts->count())
<section class="mb-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.featured') }} {{ __('messages.products') }}</h2>
        <a href="{{ route('store.products.index') }}" class="text-sm text-primary hover:text-primary-dark font-medium">
            {{ __('messages.view_all') }} &rarr;
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($featuredProducts as $product)
            @include('store.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- Sale Products --}}
@if($saleProducts->count())
<section class="mb-16">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.discount') }}</h2>
            <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full">SALE</span>
        </div>
        <a href="{{ route('store.products.index') }}" class="text-sm text-primary hover:text-primary-dark font-medium">
            {{ __('messages.view_all') }} &rarr;
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($saleProducts as $product)
            @include('store.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- Latest Products --}}
@if($latestProducts->count())
<section class="mb-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.latest_products') }}</h2>
        <a href="{{ route('store.products.index') }}" class="text-sm text-primary hover:text-primary-dark font-medium">
            {{ __('messages.view_all') }} &rarr;
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($latestProducts as $product)
            @include('store.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif
@endsection
