@extends('layouts.app')
@section('title', isset($term) && $term ? __('messages.search') . ': ' . $term : __('messages.products'))
@section('content')
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-primary">{{ __('messages.home') }}</a>
    <span>/</span>
    <span class="text-gray-900">{{ isset($term) && $term ? __('messages.search') : __('messages.products') }}</span>
</nav>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">
        @if(isset($term) && $term)
            {{ __('messages.search') }}: <span class="text-primary">{{ $term }}</span>
        @else
            {{ __('messages.products') }}
        @endif
    </h1>
    @if(method_exists($products, 'total'))
        <p class="text-sm text-gray-500 mt-1">{{ $products->total() }} {{ __('messages.products') }}</p>
    @endif
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-8">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <input type="text" name="search" value="{{ request('search', $term ?? '') }}" placeholder="{{ __('messages.search_products') }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <select name="category" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
            <option value="">{{ __('messages.all_categories') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</option>
            @endforeach
        </select>
        <select name="sort_by" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
            <option value="">{{ __('messages.sort_order') }}</option>
            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>{{ __('messages.price') }} ↑</option>
            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>{{ __('messages.price') }} ↓</option>
            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>{{ __('messages.latest_products') }}</option>
        </select>
        <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            {{ __('messages.search') }}
        </button>
    </form>
</div>

{{-- Products Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    @forelse($products as $product)
        @include('store.partials.product-card', ['product' => $product])
    @empty
        <div class="col-span-full text-center py-20">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.no_products') }}</h3>
            <p class="text-gray-500">{{ __('messages.no_results') }}</p>
            <a href="{{ route('store.products.index') }}" class="mt-4 inline-block text-primary hover:text-primary-dark font-medium text-sm">
                {{ __('messages.continue_shopping') }} &rarr;
            </a>
        </div>
    @endforelse
</div>

@if(method_exists($products, 'links'))
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
@endsection
