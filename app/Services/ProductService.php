<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected MediaService $mediaService,
    ) {}

    public function getPaginated(array $filters): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;

        return $this->productRepository->paginate($filters, $perPage);
    }

    public function create(array $data): Product
    {
        $images = $data['images'] ?? [];
        unset($data['images']);

        $product = $this->productRepository->create($data);

        $this->syncImages($product, $images);

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $images = $data['images'] ?? [];
        unset($data['images']);

        $product = $this->productRepository->update($product, $data);

        $this->syncImages($product, $images);

        return $product;
    }

    protected function syncImages(Product $product, array $images): void
    {
        $existingCount = $product->media()->count();

        foreach ($images as $index => $file) {
            $this->mediaService->upload($file, [
                'is_primary' => $existingCount === 0 && $index === 0,
                'sort_order' => $existingCount + $index,
            ], $product);
        }
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
