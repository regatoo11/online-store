<?php

namespace App\Search\Engines;

use App\Models\Product;
use App\Search\Contracts\SearchEngineInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DatabaseSearchEngine implements SearchEngineInterface
{
    public function searchProducts(string $term, array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['category', 'primaryMedia']);

        if (!empty($term)) {
            $escapedTerm = '%' . addcslashes($term, '%_\\') . '%';

            $query->where(function (Builder $q) use ($escapedTerm) {
                $q->where('name_ar', 'LIKE', $escapedTerm)
                    ->orWhere('name_en', 'LIKE', $escapedTerm)
                    ->orWhere('description_ar', 'LIKE', $escapedTerm)
                    ->orWhere('description_en', 'LIKE', $escapedTerm)
                    ->orWhere('sku', 'LIKE', $escapedTerm);
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    public function getSuggestions(string $term, int $limit = 5): Collection
    {
        if (empty($term)) {
            return collect();
        }

        $escapedTerm = '%' . addcslashes($term, '%_\\') . '%';

        return Product::where('is_active', true)
            ->where(function (Builder $q) use ($escapedTerm) {
                $q->where('name_ar', 'LIKE', $escapedTerm)
                    ->orWhere('name_en', 'LIKE', $escapedTerm);
            })
            ->select('id', 'name_ar', 'name_en', 'slug', 'price', 'sale_price')
            ->limit($limit)
            ->get();
    }
}
