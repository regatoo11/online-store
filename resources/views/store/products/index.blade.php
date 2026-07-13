@extends('layouts.app')
@section('title', __('messages.products'))
@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.products') }}</h1>
</div>
<div class="mb-6 flex gap-4">
    <form method="GET" class="flex-1 flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search') }}..." class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
        <select name="category" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
            <option value="">{{ __('messages.all') }} {{ __('messages.categories') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name_ar }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark">{{ __('messages.search') }}</button>
    </form>
</div>
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
            <p class="text-sm text-gray-500 mb-2">{{ $product->category->name_ar ?? '' }}</p>
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold text-primary">{{ number_format($product->getDisplayPrice(), 2) }} {{ __('messages.currency') }}</span>
                @if($product->sale_price)
                    <span class="text-sm text-gray-400 line-through">{{ number_format($product->price, 2) }}</span>
                @endif
            </div>
        </div>
    </a>
    @empty
    <div class="col-span-full text-center py-20 text-gray-500">{{ __('messages.no_results') }}</div>
    @endforelse
</div>
@if(method_exists($products, 'links'))
<div class="mt-8">
    {{ $products->links() }}
</div>
@endif
@endsection
