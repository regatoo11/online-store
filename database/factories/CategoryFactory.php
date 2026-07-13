<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $nameEn = fake()->unique()->words(2, true);

        return [
            'name_ar' => fake()->words(2, true),
            'name_en' => $nameEn,
            'description_ar' => fake()->sentence(),
            'description_en' => fake()->sentence(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function parent(Category $parent): static
    {
        return $this->state(fn () => ['parent_id' => $parent->id]);
    }
}
