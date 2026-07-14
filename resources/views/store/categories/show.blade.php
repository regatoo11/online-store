@extends('layouts.app')
@section('title', $category->name_ar)
@section('meta')
    @if($category->description_ar)
        <meta name="description" content="{{ Str::limit(strip_tags($category->description_ar), 160) }}">
    @endif
@endsection
@section('content')
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-primary">{{ __('messages.home') }}</a>
    <span>/</span>
    <span class="text-gray-900">{{ $category->name_ar }}</span>
</nav>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name_ar }}</h1>
    @if($category->description_ar)
        <p class="text-gray-500 mt-2 max-w-2xl">{{ $category->description_ar }}</p>
    @endif
</div>

{{-- Subcategories --}}
@if($category->children && $category->children->count())
    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.subcategories') }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($category->children as $subcategory)
                <a href="{{ route('store.categories.show', $subcategory->slug) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md hover:border-primary/20 transition-all group">
                    @if($subcategory->media->count())
                        <div class="w-16 h-16 mx-auto mb-3 bg-gray-100 rounded-full overflow-hidden">
                            <img src="{{ asset('storage/' . $subcategory->media->first()->file_path) }}" alt="{{ $subcategory->name_ar }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-16 h-16 mx-auto mb-3 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    @endif
                    <span class="text-sm font-medium text-gray-900 group-hover:text-primary transition-colors">{{ app()->getLocale() === 'ar' ? $subcategory->name_ar : $subcategory->name_en }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif

{{-- Products --}}
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    @forelse($products as $product)
        @include('store.partials.product-card', ['product' => $product])
    @empty
        <div class="col-span-full text-center py-20">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.no_products_in_category') }}</h3>
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
