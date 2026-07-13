<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function getPaginated(array $filters): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;

        return $this->productRepository->paginate($filters, $perPage);
    }

    public function create(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }

    public function delete(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }

    public function syncVariants(Product $product, array $variantsData): void
    {
        foreach ($variantsData as $variantData) {
            if (!empty($variantData['id'])) {
                $variant = $product->variants()->find($variantData['id']);
                if ($variant) {
                    $attributes = $variantData['attributes'] ?? [];
                    unset($variantData['id'], $variantData['attributes']);
                    $variant->update($variantData);
                    if (isset($attributes)) {
                        $variant->attributes()->sync($attributes);
                    }
                }
            } else {
                $attributes = $variantData['attributes'] ?? [];
                unset($variantData['attributes']);
                $variant = $product->variants()->create($variantData);
                if (isset($attributes)) {
                    $variant->attributes()->sync($attributes);
                }
            }
        }

        if (isset($variantsData['sync'])) {
            $product->variants()->sync($variantsData['sync']);
        }
    }

    public function search(string $term): Collection
    {
        return $this->productRepository->search($term);
    }
}
