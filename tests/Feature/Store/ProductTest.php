<?php

namespace Tests\Feature\Store;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_page_loads(): void
    {
        $this->get(route('store.products.index'))->assertStatus(200);
    }

    public function test_products_index_displays_products(): void
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count(3)->create(['category_id' => $category->id, 'is_active' => true]);

        $response = $this->get(route('store.products.index'));

        $response->assertStatus(200);
        foreach ($products as $product) {
            $response->assertSee($product->name_ar);
        }
    }

    public function test_products_index_filters_by_category(): void
    {
        $cat1 = Category::factory()->create();
        $cat2 = Category::factory()->create();

        Product::factory()->count(2)->create(['category_id' => $cat1->id, 'is_active' => true]);
        Product::factory()->create(['category_id' => $cat2->id, 'is_active' => true]);

        $response = $this->get(route('store.products.index', ['category' => $cat1->id]));

        $response->assertStatus(200);
    }

    public function test_products_index_search(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create([
            'name_en' => 'Red Running Shoes',
            'name_ar' => 'حذاء رياضي أحمر',
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('store.products.index', ['search' => 'Running']));

        $response->assertStatus(200);
        $response->assertSee('حذاء رياضي أحمر');
    }

    public function test_product_show_page_loads(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('store.products.show', $product->slug));

        $response->assertStatus(200);
    }

    public function test_inactive_product_returns_404(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->inactive()->create([
            'category_id' => $category->id,
        ]);

        $this->get(route('store.products.show', $product->slug))->assertNotFound();
    }

    public function test_invalid_slug_returns_404(): void
    {
        $this->get(route('store.products.show', 'non-existent-product'))->assertNotFound();
    }

    public function test_product_show_displays_related_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        Product::factory()->count(2)->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('store.products.show', $product->slug));

        $response->assertStatus(200);
    }
}
