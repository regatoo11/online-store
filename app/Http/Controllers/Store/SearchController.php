<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $search,
    ) {}

    public function __invoke(Request $request): View
    {
        $term = $request->input('q', '');
        $filters = $request->only(['category_id', 'type', 'min_price', 'max_price', 'sort_by', 'sort_dir']);

        $products = $this->search->searchProducts($term, $filters);
        $categories = $this->search->searchCategories('');

        return view('store.products.index', compact('products', 'categories', 'term'));
    }
}
