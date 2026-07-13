<?php

namespace Tests\Feature\Store;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_show_page_loads(): void
    {
        $category = Category::factory()->create(['is_active' => true]);

        $response = $this->get(route('store.categories.show', $category->slug));

        $response->assertStatus(200);
    }

    public function test_category_shows_products(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('store.categories.show', $category->slug));

        $response->assertStatus(200);
    }

    public function test_category_shows_subcategories(): void
    {
        $parent = Category::factory()->create(['is_active' => true]);
        $child = Category::factory()->create(['is_active' => true, 'parent_id' => $parent->id]);

        $response = $this->get(route('store.categories.show', $parent->slug));

        $response->assertStatus(200);
        $response->assertSee($child->name_ar);
    }

    public function test_inactive_category_returns_404(): void
    {
        $category = Category::factory()->inactive()->create();

        $this->get(route('store.categories.show', $category->slug))->assertNotFound();
    }

    public function test_invalid_category_slug_returns_404(): void
    {
        $this->get(route('store.categories.show', 'non-existent-category'))->assertNotFound();
    }
}
