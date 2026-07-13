@extends('layouts.admin')

@section('title', __('messages.categories'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.categories') }}</h1>
@endsection

@section('header-right')
    <a href="{{ route('admin.categories.create') }}"
       class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
        {{ __('messages.add_category') }}
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <form method="GET" class="flex gap-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="{{ __('messages.search') }}"
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
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.category_parent') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $category->name_ar }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $category->parent?->name_ar ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($category->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ __('messages.active') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ __('messages.inactive') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="text-primary hover:text-primary-dark transition-colors">
                            {{ __('messages.edit') }}
                        </a>
                        <form method="POST"
                              action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        {{ __('messages.no_categories') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($categories, 'links'))
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
