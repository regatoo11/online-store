<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    public function test_product_get_display_price_returns_price_when_no_sale(): void
    {
        $product = Product::factory()->create(['price' => 100, 'sale_price' => null]);

        $this->assertEquals(100.0, (float) $product->getDisplayPrice());
    }

    public function test_product_get_display_price_returns_sale_price_when_set(): void
    {
        $product = Product::factory()->create(['price' => 100, 'sale_price' => 75]);

        $this->assertEquals(75.0, (float) $product->getDisplayPrice());
    }

    public function test_product_is_available_when_stock_positive(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'reserved_stock' => 0]);

        $this->assertTrue($product->isAvailable());
    }

    public function test_product_is_not_available_when_stock_zero(): void
    {
        $product = Product::factory()->create(['stock' => 0, 'reserved_stock' => 0]);

        $this->assertFalse($product->isAvailable());
    }

    public function test_product_get_available_stock(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'reserved_stock' => 3]);

        $this->assertEquals(7, $product->getAvailableStock());
    }

    public function test_product_has_variants(): void
    {
        $product = Product::factory()->variable()->create();

        $this->assertTrue($product->hasVariants());
    }

    public function test_product_does_not_have_variants_when_simple(): void
    {
        $product = Product::factory()->create(['type' => 'simple']);

        $this->assertFalse($product->hasVariants());
    }

    public function test_product_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_product_slug_is_auto_generated(): void
    {
        $product = Product::factory()->create(['name_en' => 'Test Product Name']);

        $this->assertEquals('test-product-name', $product->slug);
    }

    public function test_scope_active_filters_correctly(): void
    {
        Product::factory()->create(['is_active' => true]);
        Product::factory()->inactive()->create();

        $this->assertEquals(1, Product::active()->count());
    }

    public function test_scope_featured_filters_correctly(): void
    {
        Product::factory()->featured()->create();
        Product::factory()->create(['is_featured' => false]);

        $this->assertEquals(1, Product::featured()->count());
    }

    public function test_scope_in_stock_filters_correctly(): void
    {
        Product::factory()->create(['stock' => 5]);
        Product::factory()->outOfStock()->create();

        $this->assertEquals(1, Product::inStock()->count());
    }
}
