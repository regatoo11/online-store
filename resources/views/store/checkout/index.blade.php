@extends('layouts.app')
@section('title', __('messages.checkout'))
@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.checkout') }}</h1>
</div>

<form action="{{ route('store.checkout.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.shipping_address') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.full_name') }} *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.phone') }} *</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary" dir="ltr">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.governorate') }} *</label>
                        <select name="governorate" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">{{ __('messages.select_governorate') }}</option>
                            @foreach($governorates as $governorate)
                                <option value="{{ $governorate }}" {{ old('governorate') == $governorate ? 'selected' : '' }}>{{ $governorate }}</option>
                            @endforeach
                        </select>
                        @error('governorate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.city') }} *</label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.postal_code') }}</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary" dir="ltr">
                        @error('postal_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.address') }} *</label>
                        <textarea name="address" rows="3" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.shipping_method') }}</h2>
                <div class="space-y-3">
                    @foreach($shippingMethods as $method)
                        <label class="relative cursor-pointer block">
                            <input type="radio" name="shipping_method" value="{{ $method->id }}" {{ $loop->first ? 'checked' : '' }} class="peer sr-only">
                            <div class="border border-gray-300 rounded-lg p-4 peer-checked:border-primary peer-checked:bg-primary/5 transition-colors flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $method->name_ar }}</span>
                                    <p class="text-sm text-gray-500 mt-0.5">{{ $method->description_ar }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ __('messages.estimated') }}: {{ $method->estimated_days }}</p>
                                </div>
                                <span class="font-bold text-primary">
                                    @if($method->cost > 0)
                                        {{ number_format($method->cost, 2) }} {{ __('messages.currency') }}
                                    @else
                                        {{ __('messages.free') }}
                                    @endif
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('shipping_method')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.payment_method') }}</h2>
                <div class="space-y-3">
                    <label class="relative cursor-pointer block">
                        <input type="radio" name="payment_method" value="cod" checked class="peer sr-only">
                        <div class="border border-gray-300 rounded-lg p-4 peer-checked:border-primary peer-checked:bg-primary/5 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">{{ __('messages.cash_on_delivery') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('messages.cod_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer block">
                        <input type="radio" name="payment_method" value="instapay" class="peer sr-only">
                        <div class="border border-gray-300 rounded-lg p-4 peer-checked:border-primary peer-checked:bg-primary/5 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">InstaPay</span>
                                    <p class="text-sm text-gray-500">{{ __('messages.instapay_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer block">
                        <input type="radio" name="payment_method" value="wallet" class="peer sr-only">
                        <div class="border border-gray-300 rounded-lg p-4 peer-checked:border-primary peer-checked:bg-primary/5 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">{{ __('messages.mobile_wallets') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('messages.wallet_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                @error('payment_method')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.order_notes') }}</h2>
                <textarea name="notes" rows="3" placeholder="{{ __('messages.order_notes_placeholder') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.your_order') }}</h2>
                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                        <div class="flex gap-3">
                            <div class="w-14 h-14 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->product->primaryMedia)
                                    <img src="{{ asset('storage/' . $item->product->primaryMedia->file_path) }}" alt="{{ $item->product->name_ar }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name_ar }}</p>
                                @if($item->variant)
                                    <p class="text-xs text-gray-500">{{ $item->variant->name_ar }}</p>
                                @endif
                                <p class="text-xs text-gray-500">{{ $item->quantity }} x {{ number_format($item->price, 2) }}</p>
                            </div>
                            <span class="text-sm font-medium text-gray-900 whitespace-nowrap">{{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
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
                    @if($tax > 0)
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('messages.tax') }}</span>
                            <span>{{ number_format($tax, 2) }} {{ __('messages.currency') }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-base">
                        <span>{{ __('messages.total') }}</span>
                        <span>{{ number_format($total, 2) }} {{ __('messages.currency') }}</span>
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full bg-primary text-white py-3 rounded-xl font-semibold text-center hover:bg-primary-dark transition-colors">
                    {{ __('messages.place_order') }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
