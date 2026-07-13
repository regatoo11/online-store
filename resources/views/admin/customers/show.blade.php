@extends('layouts.admin')

@section('title', $customer->name . ' — ' . __('messages.customer_details'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
@endsection

@section('header-right')
    <a href="{{ route('admin.customers.index') }}"
       class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">
        &larr; {{ __('messages.back_to_list') }}
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.recent_orders') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.order_number') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.total') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.status') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.date') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($customer->orders()->latest()->limit(10)->get() as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-primary">
                                <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-primary-dark transition-colors">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $status = \App\Enums\OrderStatus::tryFrom($order->status);
                                    $colorMap = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorMap[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $status?->labelEn() ?? ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-primary hover:text-primary-dark transition-colors">
                                    {{ __('messages.view') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                {{ __('messages.no_orders') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center mb-4">
                <span class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xl mx-auto mb-3">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </span>
                <h3 class="text-lg font-semibold text-gray-900">{{ $customer->name }}</h3>
                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between pt-3 border-t border-gray-100">
                    <span class="text-gray-500">{{ __('messages.phone') }}</span>
                    <span class="font-medium">{{ $customer->phone ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.joined') }}</span>
                    <span class="font-medium">{{ $customer->created_at->format('Y-m-d') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.email_verified') }}</span>
                    <span class="font-medium">
                        @if($customer->email_verified_at)
                            <span class="text-green-600">{{ __('messages.yes') }}</span>
                        @else
                            <span class="text-yellow-600">{{ __('messages.no') }}</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.statistics') }}</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('messages.total_orders') }}</span>
                    <span class="font-semibold">{{ $customer->orders()->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('messages.total_spent') }}</span>
                    <span class="font-semibold">{{ number_format($customer->orders()->sum('total'), 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('messages.average_order') }}</span>
                    <span class="font-semibold">
                        {{ number_format($customer->orders()->avg('total') ?? 0, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
