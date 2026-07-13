<?php

namespace App\Services;

use App\Models\Category;
use App\Search\Contracts\SearchEngineInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchService
{
    public function __construct(
        protected SearchEngineInterface $engine,
    ) {}

    /**
     * Search products across all fields with optional filters.
     */
    public function searchProducts(?string $term, array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return $this->engine->searchProducts((string) ($term ?? ''), $filters, $perPage);
    }

    /**
     * Search categories by name.
     */
    public function searchCategories(?string $term, int $limit = 10): Collection
    {
        if (empty($term)) {
            return Category::active()->root()->ordered()->limit($limit)->get();
        }

        $escapedTerm = '%' . addcslashes($term, '%_\\') . '%';

        return Category::active()
            ->where(function ($q) use ($escapedTerm) {
                $q->where('name_ar', 'LIKE', $escapedTerm)
                    ->orWhere('name_en', 'LIKE', $escapedTerm);
            })
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    /**
     * Get autocomplete suggestions for a search term.
     */
    public function suggestions(?string $term, int $limit = 5): Collection
    {
        return $this->engine->getSuggestions((string) ($term ?? ''), $limit);
    }

    /**
     * Get trending/popular products (placeholder for future analytics-driven ranking).
     */
    public function getTrending(int $limit = 8): Collection
    {
        return \App\Models\Product::active()
            ->with(['category', 'primaryMedia'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
