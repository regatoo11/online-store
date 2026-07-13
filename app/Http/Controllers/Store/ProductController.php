<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected SearchService $search,
    ) {}

    public function index(Request $request): View
    {
        $term = $request->input('search', '');
        $filters = $request->only(['category', 'type', 'min_price', 'max_price', 'sort_by', 'sort_dir']);

        if (!empty($filters['category'])) {
            $filters['category_id'] = $filters['category'];
            unset($filters['category']);
        }

        $products = $this->search->searchProducts($term, $filters);
        $categories = $this->search->searchCategories('');

        return view('store.products.index', compact('products', 'categories', 'term'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['category', 'media', 'variants'])
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryMedia'])
            ->take(4)
            ->get();

        return view('store.products.show', compact('product', 'relatedProducts'));
    }
}
