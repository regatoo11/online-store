@extends('layouts.app')
@section('title', $category->name_ar)
@section('content')
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
    <a href="{{ route('store.products.index') }}" class="hover:text-primary">{{ __('messages.home') }}</a>
    <span>/</span>
    <span class="text-gray-900">{{ $category->name_ar }}</span>
</nav>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name_ar }}</h1>
    @if($category->description_ar)
        <p class="text-gray-500 mt-2">{{ $category->description_ar }}</p>
    @endif
</div>

@if($category->children && $category->children->count())
    <div class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('messages.subcategories') }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($category->children as $subcategory)
                <a href="{{ route('store.categories.show', $subcategory->slug) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md transition-shadow group">
                    @if($subcategory->image)
                        <div class="w-16 h-16 mx-auto mb-3 bg-gray-100 rounded-full overflow-hidden">
                            <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name_ar }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-16 h-16 mx-auto mb-3 bg-primary/10 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    @endif
                    <span class="text-sm font-medium text-gray-900 group-hover:text-primary transition-colors">{{ $subcategory->name_ar }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($products as $product)
        <a href="{{ route('store.products.show', $product->slug) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
            <div class="aspect-square bg-gray-100 relative overflow-hidden">
                @if($product->primaryMedia)
                    <img src="{{ asset('storage/' . $product->primaryMedia->file_path) }}" alt="{{ $product->name_ar }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                @if($product->sale_price)
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">Sale</span>
                @endif
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $product->name_ar }}</h3>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-bold text-primary">{{ number_format($product->getDisplayPrice(), 2) }} {{ __('messages.currency') }}</span>
                    @if($product->sale_price)
                        <span class="text-sm text-gray-400 line-through">{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full text-center py-20 text-gray-500">{{ __('messages.no_products_in_category') }}</div>
    @endforelse
</div>

@if(method_exists($products, 'links'))
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
@endsection
