@extends('layouts.app')
@section('title', __('messages.cart'))
@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.cart') }}</h1>
</div>

@if($cartItems && $cartItems->count())
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex gap-4">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        @if($item->product->primaryMedia)
                            <img src="{{ asset('storage/' . $item->product->primaryMedia->file_path) }}" alt="{{ $item->product->name_ar }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    <a href="{{ route('store.products.show', $item->product->slug) }}" class="hover:text-primary">{{ $item->product->name_ar }}</a>
                                </h3>
                                @if($item->variant)
                                    <p class="text-sm text-gray-500">{{ $item->variant->name_ar }}</p>
                                @endif
                            </div>
                            <form action="{{ route('store.cart.remove', $item->id) }}" method="POST" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="{{ __('messages.remove') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center gap-2" x-data="{ qty: {{ $item->quantity }} }">
                                <form action="{{ route('store.cart.update', $item->id) }}" method="POST" class="contents">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="quantity" :value="qty">
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="qty = Math.max(1, qty - 1); $el.closest('form').submit()" class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-sm">-</button>
                                        <span class="w-8 h-8 flex items-center justify-center text-sm font-medium" x-text="qty"></span>
                                        <button type="button" @click="qty++; $el.closest('form').submit()" class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-sm">+</button>
                                    </div>
                                </form>
                            </div>
                            <span class="font-bold text-gray-900">{{ number_format($item->price * $item->quantity, 2) }} {{ __('messages.currency') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.order_summary') }}</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>{{ __('messages.subtotal') }}</span>
                        <span>{{ number_format($subtotal, 2) }} {{ __('messages.currency') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>{{ __('messages.shipping') }}</span>
                        <span>{{ $shippingCost > 0 ? number_format($shippingCost, 2) . ' ' . __('messages.currency') : __('messages.free') }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>{{ __('messages.discount') }}</span>
                            <span>-{{ number_format($discount, 2) }} {{ __('messages.currency') }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-base">
                        <span>{{ __('messages.total') }}</span>
                        <span>{{ number_format($total, 2) }} {{ __('messages.currency') }}</span>
                    </div>
                </div>

                <form action="{{ route('store.cart.apply-coupon') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="coupon_code" placeholder="{{ __('messages.coupon_code') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors">{{ __('messages.apply') }}</button>
                    </div>
                    @if(session('coupon_error'))
                        <p class="text-red-500 text-xs mt-1">{{ session('coupon_error') }}</p>
                    @endif
                    @if(session('coupon_success'))
                        <p class="text-green-600 text-xs mt-1">{{ session('coupon_success') }}</p>
                    @endif
                </form>

                <a href="{{ route('store.checkout.index') }}" class="mt-6 block w-full bg-primary text-white py-3 rounded-xl font-semibold text-center hover:bg-primary-dark transition-colors">
                    {{ __('messages.checkout') }}
                </a>
            </div>
        </div>
    </div>
@else
    <div class="text-center py-20">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('messages.cart_empty') }}</h2>
        <p class="text-gray-500 mb-6">{{ __('messages.cart_empty_desc') }}</p>
        <a href="{{ route('store.products.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-colors">
            {{ __('messages.continue_shopping') }}
        </a>
    </div>
@endif
@endsection
