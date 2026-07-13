@extends('layouts.app')
@section('title', $product->name_ar)
@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($product->description_ar ?? $product->name_ar), 160) }}">
    <meta property="og:title" content="{{ $product->name_ar }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description_ar ?? ''), 160) }}">
    @if($product->primaryMedia)
        <meta property="og:image" content="{{ asset('storage/' . $product->primaryMedia->file_path) }}">
    @endif
    <meta property="og:type" content="product">
    <meta property="product:price:amount" content="{{ $product->getDisplayPrice() }}">
    <meta property="product:price:currency" content="EGP">
@endsection
@section('content')
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
    <a href="{{ route('store.products.index') }}" class="hover:text-primary">{{ __('messages.home') }}</a>
    <span>/</span>
    @if($product->category)
        <a href="{{ route('store.categories.show', $product->category->slug) }}" class="hover:text-primary">{{ $product->category->name_ar }}</a>
        <span>/</span>
    @endif
    <span class="text-gray-900">{{ $product->name_ar }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    <div>
        <div class="bg-gray-100 rounded-2xl overflow-hidden aspect-square mb-4" x-data="{ activeImage: 0 }">
            @if($product->media->count())
                <img
                    :src="'{{ asset('storage/') }}/' + $product->media[activeImage].file_path"
                    alt="{{ $product->name_ar }}"
                    class="w-full h-full object-cover"
                >
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
        </div>
        @if($product->media->count() > 1)
            <div class="flex gap-2 overflow-x-auto pb-2" x-data="{ activeImage: 0 }">
                @foreach($product->media as $index => $media)
                    <button
                        @click="activeImage = {{ $index }}"
                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-colors"
                        :class="activeImage === {{ $index }} ? 'border-primary' : 'border-transparent'"
                    >
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name_ar }}</h1>
        @if($product->category)
            <a href="{{ route('store.categories.show', $product->category->slug) }}" class="text-sm text-primary hover:underline mb-4 inline-block">{{ $product->category->name_ar }}</a>
        @endif

        <div class="flex items-center gap-3 mb-6">
            <span class="text-3xl font-bold text-primary">{{ number_format($product->getDisplayPrice(), 2) }} {{ __('messages.currency') }}</span>
            @if($product->sale_price)
                <span class="text-lg text-gray-400 line-through">{{ number_format($product->price, 2) }} {{ __('messages.currency') }}</span>
                <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded">
                    -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                </span>
            @endif
        </div>

        @if($product->description_ar)
            <div class="prose prose-sm text-gray-600 mb-8">
                {!! $product->description_ar !!}
            </div>
        @endif

        <form action="{{ route('store.cart.add') }}" method="POST" class="mb-8">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            @if($product->variants && $product->variants->count())
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('messages.select_variant') }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($product->variants as $variant)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="variant_id" value="{{ $variant->id }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                <div class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary font-medium transition-colors">
                                    {{ $variant->name_ar }}
                                    @if($variant->price_adjustment)
                                        (+{{ number_format($variant->price_adjustment, 2) }} {{ __('messages.currency') }})
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('variant_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.quantity') }}</label>
                <div class="flex items-center gap-3" x-data="{ qty: {{ $product->min_order_qty ?? 1 }} }">
                    <button type="button" @click="qty = Math.max({{ $product->min_order_qty ?? 1 }}, qty - 1)" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <input type="number" name="quantity" x-model="qty" min="{{ $product->min_order_qty ?? 1 }}" max="{{ $product->stock_qty }}" value="{{ $product->min_order_qty ?? 1 }}" class="w-20 h-10 text-center border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <button type="button" @click="qty = Math.min({{ $product->stock_qty }}, qty + 1)" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    <span class="text-sm text-gray-500">
                        @if($product->stock_qty > 0)
                            {{ $product->stock_qty }} {{ __('messages.in_stock') }}
                        @else
                            <span class="text-red-500">{{ __('messages.out_of_stock') }}</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    @disabled($product->stock_qty <= 0)
                    class="flex-1 bg-primary text-white py-3 px-6 rounded-xl font-semibold hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    {{ __('messages.add_to_cart') }}
                </button>
            </div>
        </form>

        <div class="border-t border-gray-200 pt-6 space-y-3 text-sm text-gray-600">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('messages.free_shipping_over') }}
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('messages.returns_policy') }}
            </div>
        </div>
    </div>
</div>

@if($relatedProducts && $relatedProducts->count())
<div class="mt-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('messages.related_products') }}</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $related)
            <a href="{{ route('store.products.show', $related->slug) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                    @if($related->primaryMedia)
                        <img src="{{ asset('storage/' . $related->primaryMedia->file_path) }}" alt="{{ $related->name_ar }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $related->name_ar }}</h3>
                    <span class="text-lg font-bold text-primary">{{ number_format($related->getDisplayPrice(), 2) }} {{ __('messages.currency') }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection
