@extends('layouts.admin')

@section('title', __('messages.products'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.products') }}</h1>
@endsection

@section('header-right')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index', ['trashed' => 1]) }}"
           class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">
            {{ __('messages.trashed_products') }}
        </a>
        <a href="{{ route('admin.products.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
            {{ __('messages.add_product') }}
        </a>
    </div>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <form method="GET" class="flex gap-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="{{ __('messages.search_products') }}"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
            <select name="category_id"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                <option value="">{{ __('messages.all_categories') }}</option>
                @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_ar }}
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
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.name') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.category') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.type') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.price') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.stock') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-left font-medium">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $product->name_ar }}</div>
                        <div class="text-xs text-gray-400">{{ $product->sku }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category->name_ar ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($product->type === 'variable')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ __('messages.variable') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ __('messages.simple') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->sale_price)
                            <span class="text-gray-400 line-through text-xs">{{ number_format($product->price, 2) }}</span>
                            <span class="text-red-600 font-medium">{{ number_format($product->sale_price, 2) }}</span>
                        @else
                            <span class="font-medium">{{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->track_stock)
                            @if($product->stock > 0)
                                <span class="text-green-600">{{ $product->stock }}</span>
                            @else
                                <span class="text-red-600 font-medium">{{ __('messages.out_of_stock') }}</span>
                            @endif
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->is_active)
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
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-primary hover:text-primary-dark transition-colors">
                            {{ __('messages.edit') }}
                        </a>
                        <form method="POST"
                              action="{{ route('admin.products.destroy', $product) }}"
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
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        {{ __('messages.no_products') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($products, 'links'))
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
