@extends('layouts.admin')

@section('title', isset($category) ? __('messages.edit_category') : __('messages.add_category'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">
        {{ isset($category) ? __('messages.edit_category') : __('messages.add_category') }}
    </h1>
@endsection

@section('header-right')
    <a href="{{ route('admin.categories.index') }}"
       class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">
        &larr; {{ __('messages.back_to_list') }}
    </a>
@endsection

@section('content')
<form method="POST"
      action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($category))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.basic_info') }}</h2>

                <div class="space-y-4">
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_ar') }} *</label>
                        <input type="text"
                               id="name_ar"
                               name="name_ar"
                               value="{{ old('name_ar', $category->name_ar ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('name_ar') border-red-500 @enderror"
                               required>
                        @error('name_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_en') }} *</label>
                        <input type="text"
                               id="name_en"
                               name="name_en"
                               value="{{ old('name_en', $category->name_en ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('name_en') border-red-500 @enderror"
                               required>
                        @error('name_en')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.slug') }}</label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old('slug', $category->slug ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror"
                               placeholder="{{ __('messages.auto_generated') }}">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_ar') }}</label>
                        <textarea id="description_ar"
                                  name="description_ar"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('description_ar') border-red-500 @enderror">{{ old('description_ar', $category->description_ar ?? '') }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_en') }}</label>
                        <textarea id="description_en"
                                  name="description_en"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('description_en') border-red-500 @enderror">{{ old('description_en', $category->description_en ?? '') }}</textarea>
                        @error('description_en')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.seo') }}</h2>

                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.meta_title') }}</label>
                        <input type="text"
                               id="meta_title"
                               name="meta_title"
                               value="{{ old('meta_title', $category->meta_title ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('meta_title') border-red-500 @enderror">
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.meta_description') }}</label>
                        <textarea id="meta_description"
                                  name="meta_description"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.meta_keywords') }}</label>
                        <input type="text"
                               id="meta_keywords"
                               name="meta_keywords"
                               value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('meta_keywords') border-red-500 @enderror"
                               placeholder="{{ __('messages.comma_separated') }}">
                        @error('meta_keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="canonical" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.canonical') }}</label>
                        <input type="url"
                               id="canonical"
                               name="canonical"
                               value="{{ old('canonical', $category->canonical ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('canonical') border-red-500 @enderror"
                               placeholder="https://">
                        @error('canonical')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status & Parent --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.settings') }}</h2>

                <div class="space-y-4">
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.category_parent') }}</label>
                        <select id="parent_id"
                                name="parent_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('parent_id') border-red-500 @enderror">
                            <option value="">{{ __('messages.no_parent') }}</option>
                            @foreach(\App\Models\Category::where('id', '!=', $category->id ?? null)->get() as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.sort_order') }}</label>
                        <input type="number"
                               id="sort_order"
                               name="sort_order"
                               value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="is_active" class="text-sm font-medium text-gray-700">{{ __('messages.active') }}</label>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <button type="submit"
                        class="w-full bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    {{ isset($category) ? __('messages.update_category') : __('messages.create_category') }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
