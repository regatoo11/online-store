@extends('layouts.admin')

@section('title', __('messages.orders'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.orders') }}</h1>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <form method="GET" class="flex gap-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="{{ __('messages.search_orders') }}"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
            <select name="status"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                <option value="">{{ __('messages.all_statuses') }}</option>
                @foreach(\App\Enums\OrderStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->labelEn() }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                {{ __('messages.filter') }}
            </button>
        </form>
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
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-primary">
                        #{{ $order->order_number }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $order->user->name ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 font-medium">
                        {{ number_format($order->total, 2) }}
                        <span class="text-gray-400 text-xs">{{ $order->currency }}</span>
                    </td>
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
                    <td class="px-6 py-4 text-gray-500">
                        {{ $order->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-primary hover:text-primary-dark transition-colors">
                            {{ __('messages.view') }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        {{ __('messages.no_orders') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($orders, 'links'))
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
