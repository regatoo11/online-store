@extends('layouts.admin')

@section('title', __('messages.customers'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.customers') }}</h1>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <form method="GET" class="flex gap-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="{{ __('messages.search_customers') }}"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
            <button type="submit"
                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                {{ __('messages.search') }}
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.name') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.email') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.phone') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.orders_count') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.joined') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold text-xs flex-shrink-0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </span>
                            <span class="font-medium">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $customer->email }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $customer->phone ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $customer->orders_count ?? $customer->orders()->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $customer->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="text-primary hover:text-primary-dark transition-colors">
                            {{ __('messages.view') }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        {{ __('messages.no_customers') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($customers, 'links'))
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
