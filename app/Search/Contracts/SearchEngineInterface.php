<?php

namespace App\Search\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SearchEngineInterface
{
    /**
     * Search products with optional filters and pagination.
     */
    public function searchProducts(string $term, array $filters = [], int $perPage = 12): LengthAwarePaginator;

    /**
     * Get autocomplete suggestions for a search term.
     */
    public function getSuggestions(string $term, int $limit = 5): Collection;
}
