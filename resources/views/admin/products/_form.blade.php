@extends('layouts.admin')

@section('title', isset($product) ? __('messages.edit_product') : __('messages.add_product'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">
        {{ isset($product) ? __('messages.edit_product') : __('messages.add_product') }}
    </h1>
@endsection

@section('header-right')
    <a href="{{ route('admin.products.index') }}"
       class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">
        &larr; {{ __('messages.back_to_list') }}
    </a>
@endsection

@section('content')
<form method="POST"
      action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      id="productForm">
    @csrf
    @if(isset($product))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.basic_info') }}</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_ar') }} *</label>
                            <input type="text"
                                   id="name_ar"
                                   name="name_ar"
                                   value="{{ old('name_ar', $product->name_ar ?? '') }}"
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
                                   value="{{ old('name_en', $product->name_en ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('name_en') border-red-500 @enderror"
                                   required>
                            @error('name_en')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.slug') }}</label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old('slug', $product->slug ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror"
                               placeholder="{{ __('messages.auto_generated') }}">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.sku') }}</label>
                        <input type="text"
                               id="sku"
                               name="sku"
                               value="{{ old('sku', $product->sku ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('sku') border-red-500 @enderror">
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_ar') }}</label>
                        <textarea id="description_ar"
                                  name="description_ar"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('description_ar') border-red-500 @enderror">{{ old('description_ar', $product->description_ar ?? '') }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_en') }}</label>
                        <textarea id="description_en"
                                  name="description_en"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('description_en') border-red-500 @enderror">{{ old('description_en', $product->description_en ?? '') }}</textarea>
                        @error('description_en')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.pricing') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.price') }} *</label>
                        <input type="number"
                               id="price"
                               name="price"
                               value="{{ old('price', $product->price ?? '') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('price') border-red-500 @enderror"
                               required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.sale_price') }}</label>
                        <input type="number"
                               id="sale_price"
                               name="sale_price"
                               value="{{ old('sale_price', $product->sale_price ?? '') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('sale_price') border-red-500 @enderror">
                        @error('sale_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.cost_price') }}</label>
                        <input type="number"
                               id="cost_price"
                               name="cost_price"
                               value="{{ old('cost_price', $product->cost_price ?? '') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('cost_price') border-red-500 @enderror">
                        @error('cost_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.inventory') }}</h2>

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="track_stock" value="0">
                        <input type="checkbox"
                               id="track_stock"
                               name="track_stock"
                               value="1"
                               {{ old('track_stock', $product->track_stock ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="track_stock" class="text-sm font-medium text-gray-700">{{ __('messages.track_stock') }}</label>
                    </div>

                    <div id="stockField">
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.stock_quantity') }}</label>
                        <input type="number"
                               id="stock"
                               name="stock"
                               value="{{ old('stock', $product->stock ?? 0) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('stock') border-red-500 @enderror">
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Dimensions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.dimensions') }}</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.weight') }} (kg)</label>
                        <input type="number"
                               id="weight"
                               name="weight"
                               value="{{ old('weight', $product->weight ?? '') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('weight') border-red-500 @enderror">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="length" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.length') }} (cm)</label>
                        <input type="number"
                               id="length"
                               name="length"
                               value="{{ old('length', $product->length ?? '') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('length') border-red-500 @enderror">
                        @error('length')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="width" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.width') }} (cm)</label>
                        <input type="number"
                               id="width"
                               name="width"
                               value="{{ old('width', $product->width ?? '') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('width') border-red-500 @enderror">
                        @error('width')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.height') }} (cm)</label>
                        <input type="number"
                               id="height"
                               name="height"
                               value="{{ old('height', $product->height ?? '') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('height') border-red-500 @enderror">
                        @error('height')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Images --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.images') }}</h2>

                <div id="dropZone"
                     class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-600 mb-1">{{ __('messages.drag_drop_images') }}</p>
                    <p class="text-xs text-gray-400">{{ __('messages.or_click_to_upload') }}</p>
                    <input type="file"
                           id="images"
                           name="images[]"
                           multiple
                           accept="image/*"
                           class="hidden">
                </div>

                <div id="imagePreview" class="mt-4 grid grid-cols-4 gap-3"></div>
                @error('images')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Variants --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" id="variantsSection"
                 style="{{ (old('type', $product->type ?? 'simple') === 'variable') ? '' : 'display:none' }}">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.variants') }}</h2>
                    <button type="button"
                            id="addVariantBtn"
                            class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                        + {{ __('messages.add_variant') }}
                    </button>
                </div>

                <div id="variantsContainer" class="space-y-4">
                    @if(isset($product))
                        @foreach($product->variants as $variant)
                        <div class="variant-row border border-gray-200 rounded-lg p-4 space-y-3" data-index="{{ $loop->index }}">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">{{ __('messages.variant') }} #{{ $loop->index + 1 }}</span>
                                <button type="button" class="remove-variant text-red-600 hover:text-red-800 text-sm transition-colors">
                                    {{ __('messages.remove') }}
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                @foreach($attributes as $attribute)
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">{{ $attribute->name_ar }}</label>
                                    <select name="variants[{{ $loop->parent->index }}][attribute_values][]"
                                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                        <option value="">{{ __('messages.select') }}</option>
                                        @foreach($attribute->values as $value)
                                            <option value="{{ $value->id }}"
                                                {{ in_array($value->id, $variant->attributes->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $value->value_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endforeach
                            </div>

                            <div class="grid grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.name') }}</label>
                                    <input type="text"
                                           name="variants[{{ $loop->index }}][name]"
                                           value="{{ $variant->name }}"
                                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.sku') }}</label>
                                    <input type="text"
                                           name="variants[{{ $loop->index }}][sku]"
                                           value="{{ $variant->sku }}"
                                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.price') }}</label>
                                    <input type="number"
                                           name="variants[{{ $loop->index }}][price]"
                                           value="{{ $variant->price }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.stock') }}</label>
                                    <input type="number"
                                           name="variants[{{ $loop->index }}][stock]"
                                           value="{{ $variant->stock }}"
                                           min="0"
                                           class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
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
                               value="{{ old('meta_title', $product->meta_title ?? '') }}"
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
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.meta_keywords') }}</label>
                        <input type="text"
                               id="meta_keywords"
                               name="meta_keywords"
                               value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}"
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
                               value="{{ old('canonical', $product->canonical ?? '') }}"
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
            {{-- Status --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.settings') }}</h2>

                <div class="space-y-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.product_type') }}</label>
                        <select id="type"
                                name="type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                            <option value="simple" {{ old('type', $product->type ?? 'simple') === 'simple' ? 'selected' : '' }}>
                                {{ __('messages.simple') }}
                            </option>
                            <option value="variable" {{ old('type', $product->type ?? '') === 'variable' ? 'selected' : '' }}>
                                {{ __('messages.variable') }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.category') }} *</label>
                        <select id="category_id"
                                name="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary @error('category_id') border-red-500 @enderror"
                                required>
                            <option value="">{{ __('messages.select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="is_active" class="text-sm font-medium text-gray-700">{{ __('messages.active') }}</label>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox"
                               id="is_featured"
                               name="is_featured"
                               value="1"
                               {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="is_featured" class="text-sm font-medium text-gray-700">{{ __('messages.featured') }}</label>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <button type="submit"
                        class="w-full bg-primary text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    {{ isset($product) ? __('messages.update_product') : __('messages.create_product') }}
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const variantsSection = document.getElementById('variantsSection');
    const trackStock = document.getElementById('track_stock');
    const stockField = document.getElementById('stockField');
    const addVariantBtn = document.getElementById('addVariantBtn');
    const variantsContainer = document.getElementById('variantsContainer');
    const dropZone = document.getElementById('dropZone');
    const imagesInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    let variantIndex = {{ isset($product) ? $product->variants->count() : 0 }};

    typeSelect.addEventListener('change', function () {
        variantsSection.style.display = this.value === 'variable' ? '' : 'none';
    });

    trackStock.addEventListener('change', function () {
        stockField.style.display = this.checked ? '' : 'none';
    });
    stockField.style.display = trackStock.checked ? '' : 'none';

    addVariantBtn.addEventListener('click', function () {
        const html = `
            <div class="variant-row border border-gray-200 rounded-lg p-4 space-y-3" data-index="${variantIndex}">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">{{ __('messages.variant') }} #${variantIndex + 1}</span>
                    <button type="button" class="remove-variant text-red-600 hover:text-red-800 text-sm transition-colors">
                        {{ __('messages.remove') }}
                    </button>
                </div>
                <div class="grid grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.name') }}</label>
                        <input type="text" name="variants[${variantIndex}][name]" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.sku') }}</label>
                        <input type="text" name="variants[${variantIndex}][sku]" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.price') }}</label>
                        <input type="number" name="variants[${variantIndex}][price]" step="0.01" min="0" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('messages.stock') }}</label>
                        <input type="number" name="variants[${variantIndex}][stock]" min="0" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>
        `;
        variantsContainer.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    variantsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variant')) {
            e.target.closest('.variant-row').remove();
        }
    });

    dropZone.addEventListener('click', () => imagesInput.click());
    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-primary', 'bg-primary/5'); });
    dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('border-primary', 'bg-primary/5'); });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-primary/5');
        imagesInput.files = e.dataTransfer.files;
        handleFiles(imagesInput.files);
    });
    imagesInput.addEventListener('change', function () { handleFiles(this.files); });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg"><button type="button" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 text-xs opacity-0 group-hover:opacity-100 transition-opacity" onclick="this.parentElement.remove()">×</button>`;
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endpush
@endsection
