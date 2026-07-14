<?php

namespace App\Providers;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Search\Contracts\SearchEngineInterface;
use App\Search\Engines\DatabaseSearchEngine;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(SearchEngineInterface::class, DatabaseSearchEngine::class);
    }

    public function boot(): void
    {
        View::composer(['layouts.app'], function ($view) {
            $categories = Category::active()->root()->ordered()->get();
            $view->with('categories', $categories);
        });

        View::composer(['layouts.app'], function ($view) {
            $cartService = app(CartService::class);
            $cart = $cartService->getOrCreateCart(
                auth()->id(),
                session()->getId(),
            );
            $cartCount = $cart->items()->sum('quantity');
            $view->with('cartCount', $cartCount);
        });
    }
}
