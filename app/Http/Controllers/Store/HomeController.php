<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SearchService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected SearchService $search,
    ) {}

    public function __invoke(): View
    {
        $categories = $this->search->searchCategories('');

        $featuredProducts = Product::active()
            ->featured()
            ->with(['category', 'primaryMedia'])
            ->latest()
            ->limit(8)
            ->get();

        $latestProducts = Product::active()
            ->with(['category', 'primaryMedia'])
            ->latest()
            ->limit(8)
            ->get();

        $saleProducts = Product::active()
            ->whereNotNull('sale_price')
            ->with(['category', 'primaryMedia'])
            ->latest()
            ->limit(8)
            ->get();

        return view('store.home', compact('categories', 'featuredProducts', 'latestProducts', 'saleProducts'));
    }
}
