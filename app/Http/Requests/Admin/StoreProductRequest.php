<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description_ar' => ['required', 'string'],
            'description_en' => ['required', 'string'],
            'sku' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'in:simple,variable'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'track_stock' => ['boolean'],
            'stock' => ['required', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['required_with:variants', 'string'],
            'variants.*.sku' => ['nullable', 'string'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],
            'variants.*.attribute_values' => ['required_with:variants', 'array'],
            'variants.*.attribute_values.*' => ['exists:attribute_values,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'max:5120', 'mimes:jpeg,png,webp,gif'],
        ];
    }
}
