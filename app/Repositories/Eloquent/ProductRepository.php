<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository implements ProductRepositoryInterface
{
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function paginate(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function (Builder $q) use ($term) {
                $q->where('name_ar', 'like', "%{$term}%")
                    ->orWhere('name_en', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        $query->with('category');

        if (!empty($filters['sort_by']) && !empty($filters['sort_dir'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir']);
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return $this->model->with('category', 'variants', 'primaryMedia')->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->with('category', 'variants', 'primaryMedia')
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function search(string $term, int $limit = 10)
    {
        return $this->model
            ->where('is_active', true)
            ->where(function (Builder $q) use ($term) {
                $q->where('name_ar', 'like', "%{$term}%")
                    ->orWhere('name_en', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            })
            ->limit($limit)
            ->get();
    }

    public function getFeatured(int $limit = 10)
    {
        return $this->model
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with('category', 'primaryMedia')
            ->limit($limit)
            ->get();
    }

    public function getInStock()
    {
        return $this->model
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();
    }
}
