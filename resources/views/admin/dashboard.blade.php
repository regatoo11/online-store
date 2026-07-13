@extends('layouts.admin')

@section('title', __('messages.dashboard'))

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.dashboard') }}</h1>
    <p class="text-sm text-gray-500 mt-1">{{ __('messages.dashboard_welcome', ['name' => auth()->user()->name]) }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">{{ __('messages.today_orders') }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $widgets['today_orders'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">{{ __('messages.today_revenue') }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($widgets['today_revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">{{ __('messages.total_products') }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $widgets['total_products'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">{{ __('messages.pending_payments') }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $widgets['pending_payments'] }}</p>
    </div>
</div>

@if(isset($widgets['recent_orders']) && $widgets['recent_orders']->count())
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-900">{{ __('messages.recent_orders') }}</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.order_number') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.customer') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.total') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.date') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($widgets['recent_orders'] as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-primary">#{{ $order->order_number }}</td>
                    <td class="px-6 py-4">{{ $order->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-medium">{{ number_format($order->total, 2) }} {{ $order->currency }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-indigo-100 text-indigo-800',
                                'shipped' => 'bg-purple-100 text-purple-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
