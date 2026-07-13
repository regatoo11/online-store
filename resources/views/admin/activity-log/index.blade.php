@extends('layouts.admin')

@section('title', __('messages.activity_log'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.activity_log') }}</h1>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <form method="GET" class="flex gap-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="{{ __('messages.search_activity') }}"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
            <select name="type"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                <option value="">{{ __('messages.all_types') }}</option>
                <option value="created" {{ request('type') === 'created' ? 'selected' : '' }}>{{ __('messages.created') }}</option>
                <option value="updated" {{ request('type') === 'updated' ? 'selected' : '' }}>{{ __('messages.updated') }}</option>
                <option value="deleted" {{ request('type') === 'deleted' ? 'selected' : '' }}>{{ __('messages.deleted') }}</option>
                <option value="login" {{ request('type') === 'login' ? 'selected' : '' }}>{{ __('messages.login') }}</option>
                <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>{{ __('messages.payment') }}</option>
                <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>{{ __('messages.order') }}</option>
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
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.user') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.type') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.subject') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.description') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.date') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.ip_address') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($activities as $activity)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold text-xs flex-shrink-0">
                                {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                            </span>
                            <span class="text-sm font-medium">{{ $activity->user->name ?? __('messages.system') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $typeColors = [
                                'created' => 'bg-green-100 text-green-800',
                                'updated' => 'bg-blue-100 text-blue-800',
                                'deleted' => 'bg-red-100 text-red-800',
                                'login' => 'bg-purple-100 text-purple-800',
                                'payment' => 'bg-yellow-100 text-yellow-800',
                                'order' => 'bg-indigo-100 text-indigo-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$activity->type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($activity->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        @if($activity->subject)
                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4 max-w-xs truncate text-gray-600">
                        {{ $activity->description ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                        {{ $activity->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                        {{ $activity->ip_address ?? '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        {{ __('messages.no_activity') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($activities, 'links'))
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $activities->links() }}
    </div>
    @endif
</div>
@endsection
