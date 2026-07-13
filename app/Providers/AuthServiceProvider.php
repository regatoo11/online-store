<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class AuthServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
