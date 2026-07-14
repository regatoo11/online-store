@extends('layouts.app')
@section('title', __('messages.order_details') . ' #' . $order->order_number)
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('messages.order_confirmed') }}</h1>
        <p class="text-gray-500">{{ __('messages.order_number') }}: <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.order_items') }}</h2>
        <div class="divide-y divide-gray-100">
            @foreach($order->items as $item)
                <div class="flex gap-4 py-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->primaryMedia)
                            <img src="{{ asset('storage/' . $item->product->primaryMedia->file_path) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-gray-900">{{ $item->product_name }}</h3>
                        @if($item->variant?->name)
                            <p class="text-sm text-gray-500">{{ $item->variant->name }}</p>
                        @endif
                        <p class="text-sm text-gray-500">{{ __('messages.qty') }}: {{ $item->quantity }} x {{ number_format($item->price, 2) }} {{ __('messages.currency') }}</p>
                    </div>
                    <span class="font-semibold text-gray-900 whitespace-nowrap">{{ number_format($item->total, 2) }} {{ __('messages.currency') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-3">{{ __('messages.shipping_address') }}</h3>
            <div class="text-sm text-gray-600 space-y-1">
                <p class="font-medium text-gray-900">{{ $order->shipping_address['name'] ?? '' }}</p>
                <p>{{ $order->shipping_address['address'] ?? '' }}</p>
                <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['governorate'] ?? '' }}</p>
                @if($order->shipping_address['postal_code'] ?? null)
                    <p>{{ $order->shipping_address['postal_code'] }}</p>
                @endif
                <p class="mt-2">{{ __('messages.phone') }}: <span dir="ltr">{{ $order->shipping_address['phone'] ?? '' }}</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-3">{{ __('messages.order_info') }}</h3>
            <div class="text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('messages.status') }}</span>
                    <span class="px-2 py-0.5 rounded text-xs font-medium
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ __('messages.' . $order->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('messages.payment_method') }}</span>
                    <span class="text-gray-900">{{ $order->payments->first()?->method->name_en ?? __('messages.na') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('messages.payment_status') }}</span>
                    @php $payStatus = $order->payments->first()?->status ?? 'pending'; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium
                        {{ $payStatus === 'verified' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ ucfirst($payStatus) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('messages.date') }}</span>
                    <span class="text-gray-900">{{ $order->created_at->format('Y-m-d h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>{{ __('messages.subtotal') }}</span>
                <span>{{ number_format($order->subtotal, 2) }} {{ __('messages.currency') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>{{ __('messages.shipping') }}</span>
                <span>{{ number_format($order->shipping_cost, 2) }} {{ __('messages.currency') }}</span>
            </div>
            @if($order->discount > 0)
                <div class="flex justify-between text-green-600">
                    <span>{{ __('messages.discount') }}</span>
                    <span>-{{ number_format($order->discount, 2) }} {{ __('messages.currency') }}</span>
                </div>
            @endif
            <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-lg">
                <span>{{ __('messages.total') }}</span>
                <span>{{ number_format($order->total, 2) }} {{ __('messages.currency') }}</span>
            </div>
        </div>
    </div>

    @if($order->notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-bold text-gray-900 mb-2">{{ __('messages.order_notes') }}</h3>
            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
        </div>
    @endif

    <div class="text-center">
        <a href="{{ route('store.products.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-colors">
            {{ __('messages.continue_shopping') }}
        </a>
    </div>
</div>
@endsection
