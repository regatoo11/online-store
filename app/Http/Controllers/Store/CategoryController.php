<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->with(['children', 'media'])
            ->firstOrFail();

        $products = Product::active()
            ->where('category_id', $category->id)
            ->with(['primaryMedia'])
            ->latest()
            ->paginate(12);

        return view('store.categories.show', compact('category', 'products'));
    }
}
