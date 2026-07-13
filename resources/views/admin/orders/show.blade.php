@extends('layouts.admin')

@section('title', __('messages.order_details') . ' #' . $order->order_number)

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">
        {{ __('messages.order') }} #{{ $order->order_number }}
    </h1>
@endsection

@section('header-right')
    <a href="{{ route('admin.orders.index') }}"
       class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">
        &larr; {{ __('messages.back_to_list') }}
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Order Items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.order_items') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.product') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.price') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.quantity') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $item->product_name }}</div>
                                @if($item->product_sku)
                                    <div class="text-xs text-gray-400">SKU: {{ $item->product_sku }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 font-medium">{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Addresses --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($order->shipping_address)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('messages.shipping_address') }}</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>{{ $order->shipping_address['name'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['street'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['phone'] ?? '' }}</p>
                </div>
            </div>
            @endif

            @if($order->billing_address)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('messages.billing_address') }}</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>{{ $order->billing_address['name'] ?? '' }}</p>
                    <p>{{ $order->billing_address['street'] ?? '' }}</p>
                    <p>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }}</p>
                    <p>{{ $order->billing_address['country'] ?? '' }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Notes --}}
        @if($order->notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">{{ __('messages.notes') }}</h3>
            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.order_summary') }}</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.subtotal') }}</span>
                    <span>{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.tax') }}</span>
                    <span>{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between text-green-600">
                    <span>{{ __('messages.discount') }}</span>
                    <span>-{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.shipping') }}</span>
                    <span>{{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                @if($order->coupon_code)
                <div class="flex justify-between text-xs text-gray-400">
                    <span>{{ __('messages.coupon') }}</span>
                    <span>{{ $order->coupon_code }}</span>
                </div>
                @endif
                <div class="flex justify-between pt-3 border-t border-gray-100 font-semibold text-base">
                    <span>{{ __('messages.total') }}</span>
                    <span>{{ number_format($order->total, 2) }} {{ $order->currency }}</span>
                </div>
            </div>
        </div>

        {{-- Customer --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.customer') }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold text-xs">
                        {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                    </span>
                    <div>
                        <div class="font-medium">{{ $order->user->name ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</div>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.customers.show', $order->user_id) }}"
               class="mt-3 block text-center text-sm text-primary hover:text-primary-dark transition-colors">
                {{ __('messages.view_customer') }}
            </a>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.status') }}</h3>
            @php
                $currentStatus = \App\Enums\OrderStatus::tryFrom($order->status);
                $allStatuses = \App\Enums\OrderStatus::cases();
                $colorMap = [
                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'confirmed' => 'bg-blue-100 text-blue-800 border-blue-300',
                    'processing' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                    'shipped' => 'bg-purple-100 text-purple-800 border-purple-300',
                    'delivered' => 'bg-green-100 text-green-800 border-green-300',
                    'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                ];
            @endphp

            {{-- Timeline --}}
            <div class="space-y-3">
                @foreach($allStatuses as $s)
                @php
                    $isActive = $currentStatus && $currentStatus->value === $s->value;
                    $isPast = $currentStatus && array_search($currentStatus, $allStatuses) > array_search($s, $allStatuses);
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full flex-shrink-0 {{ $isActive ? 'bg-primary ring-2 ring-primary/20' : ($isPast ? 'bg-green-500' : 'bg-gray-300') }}"></div>
                    <span class="text-sm {{ $isActive ? 'font-semibold text-gray-900' : ($isPast ? 'text-gray-500 line-through' : 'text-gray-400') }}">
                        {{ $s->labelEn() }}
                    </span>
                </div>
                @endforeach
            </div>

            {{-- Update Status --}}
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4">
                @csrf
                @method('PATCH')
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary mb-3">
                    @if($currentStatus)
                        @foreach($currentStatus->nextStatuses() as $next)
                            <option value="{{ $next->value }}">{{ $next->labelEn() }}</option>
                        @endforeach
                    @endif
                </select>
                <button type="submit"
                        class="w-full bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors"
                        {{ $currentStatus && empty($currentStatus->nextStatuses()) ? 'disabled' : '' }}>
                    {{ __('messages.update_status') }}
                </button>
            </form>
        </div>

        {{-- Payments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.payments') }}</h3>
            @forelse($order->payments as $payment)
            <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div>
                    <div class="text-sm font-medium">{{ $payment->method->name_en ?? '—' }}</div>
                    <div class="text-xs text-gray-400">{{ $payment->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium">{{ number_format($payment->amount, 2) }}</div>
                    @php
                        $payStatus = \App\Enums\PaymentStatus::tryFrom($payment->status);
                    @endphp
                    <span class="text-xs {{ $payment->status === 'verified' ? 'text-green-600' : ($payment->status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">{{ __('messages.no_payments') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
