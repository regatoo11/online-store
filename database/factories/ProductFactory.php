<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $nameEn = fake()->unique()->words(3, true);

        return [
            'name_ar' => fake()->words(3, true),
            'name_en' => $nameEn,
            'description_ar' => fake()->paragraph(),
            'description_en' => fake()->paragraph(),
            'sku' => strtoupper(fake()->bothify('???-####')),
            'type' => 'simple',
            'price' => fake()->randomFloat(2, 10, 1000),
            'sale_price' => null,
            'category_id' => Category::factory(),
            'is_active' => true,
            'is_featured' => false,
            'track_stock' => true,
            'stock' => fake()->numberBetween(1, 100),
            'reserved_stock' => 0,
            'weight' => null,
            'length' => null,
            'width' => null,
            'height' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }

    public function withSalePrice(float $salePrice = null): static
    {
        return $this->state(fn () => ['sale_price' => $salePrice ?? fake()->randomFloat(2, 5, 500)]);
    }

    public function variable(): static
    {
        return $this->state(fn () => ['type' => 'variable']);
    }
}
