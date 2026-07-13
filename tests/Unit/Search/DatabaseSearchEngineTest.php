<?php

namespace Tests\Unit\Search;

use App\Models\Category;
use App\Models\Product;
use App\Search\Engines\DatabaseSearchEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSearchEngineTest extends TestCase
{
    use RefreshDatabase;

    private DatabaseSearchEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new DatabaseSearchEngine();
    }

    private function createActiveProduct(array $overrides = []): Product
    {
        $category = Category::factory()->create(['is_active' => true, 'parent_id' => null]);

        return Product::factory()->create(array_merge([
            'category_id' => $category->id,
            'is_active' => true,
            'name_en' => 'Test Product',
            'name_ar' => 'منتج اختبار',
            'sku' => 'TP-001',
            'price' => 100.00,
            'description_en' => 'A test product description',
            'description_ar' => 'وصف منتج اختبار',
        ], $overrides));
    }

    public function test_search_returns_paginator(): void
    {
        $this->createActiveProduct();

        $result = $this->engine->searchProducts('');

        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
    }

    public function test_search_by_name_english(): void
    {
        $this->createActiveProduct(['name_en' => 'Laptop Pro', 'name_ar' => 'لابتوب برو']);

        $result = $this->engine->searchProducts('Laptop');

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Laptop Pro', $result->first()->name_en);
    }

    public function test_search_by_name_arabic(): void
    {
        $this->createActiveProduct(['name_en' => 'Gaming Mouse', 'name_ar' => 'ماوس ألعاب']);

        $result = $this->engine->searchProducts('ماوس');

        $this->assertEquals(1, $result->total());
    }

    public function test_search_by_sku(): void
    {
        $this->createActiveProduct(['sku' => 'LP-2024-PRO']);

        $result = $this->engine->searchProducts('LP-2024');

        $this->assertEquals(1, $result->total());
    }

    public function test_search_by_description(): void
    {
        $this->createActiveProduct([
            'name_en' => 'Shirt',
            'name_ar' => 'قميص',
            'description_en' => 'Bamboo fiber sustainable material',
        ]);

        $result = $this->engine->searchProducts('bamboo');

        $this->assertEquals(1, $result->total());
    }

    public function test_search_excludes_inactive_products(): void
    {
        $this->createActiveProduct(['name_en' => 'Active One', 'is_active' => true]);

        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'name_en' => 'Inactive One',
            'name_ar' => 'غير نشط',
            'is_active' => false,
            'sku' => 'INACT-001',
            'price' => 50,
            'description_en' => 'inactive product',
            'description_ar' => 'منتج غير نشط',
        ]);

        $result = $this->engine->searchProducts('One');

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Active One', $result->first()->name_en);
    }

    public function test_search_no_results(): void
    {
        $result = $this->engine->searchProducts('zzznonexistent');

        $this->assertEquals(0, $result->total());
    }

    public function test_search_with_category_filter(): void
    {
        $cat1 = Category::factory()->create(['is_active' => true, 'parent_id' => null]);
        $cat2 = Category::factory()->create(['is_active' => true, 'parent_id' => null]);

        Product::factory()->create([
            'category_id' => $cat1->id,
            'is_active' => true,
            'name_en' => 'Phone Case',
            'name_ar' => 'غطاء هاتف',
            'sku' => 'PC-001',
            'price' => 20,
            'description_en' => 'phone case',
            'description_ar' => 'غطاء هاتف',
        ]);

        Product::factory()->create([
            'category_id' => $cat2->id,
            'is_active' => true,
            'name_en' => 'Phone Charger',
            'name_ar' => 'شاحن هاتف',
            'sku' => 'PCH-001',
            'price' => 30,
            'description_en' => 'phone charger',
            'description_ar' => 'شاحن هاتف',
        ]);

        $result = $this->engine->searchProducts('phone', ['category_id' => $cat1->id]);

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Phone Case', $result->first()->name_en);
    }

    public function test_search_with_type_filter(): void
    {
        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'is_active' => true,
            'type' => 'simple',
            'name_en' => 'Simple Item',
            'name_ar' => 'عنصر بسيط',
            'sku' => 'SI-001',
            'price' => 10,
            'description_en' => 'simple',
            'description_ar' => 'بسيط',
        ]);

        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'is_active' => true,
            'type' => 'variable',
            'name_en' => 'Variable Item',
            'name_ar' => 'عنصر متغير',
            'sku' => 'VI-001',
            'price' => 20,
            'description_en' => 'variable',
            'description_ar' => 'متغير',
        ]);

        $result = $this->engine->searchProducts('Item', ['type' => 'simple']);

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Simple Item', $result->first()->name_en);
    }

    public function test_search_with_price_filters(): void
    {
        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'is_active' => true,
            'name_en' => 'Cheap Item',
            'name_ar' => 'عنصر رخيص',
            'sku' => 'CHEAP-001',
            'price' => 10,
            'description_en' => 'cheap',
            'description_ar' => 'رخيص',
        ]);

        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'is_active' => true,
            'name_en' => 'Expensive Item',
            'name_ar' => 'عنصر غالي',
            'sku' => 'EXP-001',
            'price' => 500,
            'description_en' => 'expensive',
            'description_ar' => 'غالي',
        ]);

        $result = $this->engine->searchProducts('Item', ['min_price' => 100]);

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Expensive Item', $result->first()->name_en);
    }

    public function test_search_with_sorting(): void
    {
        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'is_active' => true,
            'name_en' => 'Alpha Item',
            'name_ar' => 'عنصر ألفا',
            'sku' => 'ALPHA-001',
            'price' => 10,
            'description_en' => 'alpha',
            'description_ar' => 'ألفا',
            'created_at' => now()->subDay(),
        ]);

        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'is_active' => true,
            'name_en' => 'Beta Item',
            'name_ar' => 'عنصر بيتا',
            'sku' => 'BETA-001',
            'price' => 20,
            'description_en' => 'beta',
            'description_ar' => 'بيتا',
            'created_at' => now(),
        ]);

        $result = $this->engine->searchProducts('Item', ['sort_by' => 'price', 'sort_dir' => 'asc']);

        $items = $result->items();
        $this->assertEquals('Alpha Item', $items[0]->name_en);
        $this->assertEquals('Beta Item', $items[1]->name_en);
    }

    public function test_search_pagination_per_page(): void
    {
        $category = Category::factory()->create(['is_active' => true, 'parent_id' => null]);

        for ($i = 0; $i < 25; $i++) {
            Product::factory()->create([
                'category_id' => $category->id,
                'is_active' => true,
                'name_en' => "Page Item {$i}",
                'name_ar' => "عنصر صفحة {$i}",
                'sku' => "PG-{$i}",
                'price' => 5 + $i,
                'description_en' => 'page item',
                'description_ar' => 'عنصر صفحة',
            ]);
        }

        $result = $this->engine->searchProducts('Page Item', [], 10);

        $this->assertEquals(25, $result->total());
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(3, $result->lastPage());
    }

    // ── Suggestions ─────────────────────────────────────────────────────

    public function test_suggestions_returns_matching_products(): void
    {
        $this->createActiveProduct(['name_en' => 'Wireless Mouse', 'name_ar' => 'ماوس لاسلكي']);

        $result = $this->engine->getSuggestions('wire');

        $this->assertCount(1, $result);
        $this->assertEquals('Wireless Mouse', $result->first()->name_en);
    }

    public function test_suggestions_empty_term_returns_empty(): void
    {
        $this->createActiveProduct();

        $result = $this->engine->getSuggestions('');

        $this->assertCount(0, $result);
    }

    public function test_suggestions_limit_works(): void
    {
        $category = Category::factory()->create(['is_active' => true, 'parent_id' => null]);

        for ($i = 0; $i < 10; $i++) {
            Product::factory()->create([
                'category_id' => $category->id,
                'is_active' => true,
                'name_en' => "Suggest Pro {$i}",
                'name_ar' => "اقتراح برو {$i}",
                'sku' => "SG-{$i}",
                'price' => 10 + $i,
                'description_en' => 'suggest',
                'description_ar' => 'اقتراح',
            ]);
        }

        $result = $this->engine->getSuggestions('Suggest', 3);

        $this->assertCount(3, $result);
    }

    public function test_suggestions_only_returns_active_products(): void
    {
        $this->createActiveProduct(['name_en' => 'Active Suggest', 'name_ar' => 'اقتراح نشط', 'is_active' => true]);

        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'name_en' => 'Inactive Suggest',
            'name_ar' => 'اقتراح غير نشط',
            'is_active' => false,
            'sku' => 'IS-001',
            'price' => 10,
            'description_en' => 'inactive suggest',
            'description_ar' => 'اقتراح غير نشط',
        ]);

        $result = $this->engine->getSuggestions('Suggest');

        $this->assertCount(1, $result);
        $this->assertEquals('Active Suggest', $result->first()->name_en);
    }

    public function test_suggestions_returns_select_columns(): void
    {
        $this->createActiveProduct(['name_en' => 'Smart Speaker', 'name_ar' => 'مكبر صوت ذكي']);

        $result = $this->engine->getSuggestions('Smart');

        $product = $result->first();
        $this->assertArrayHasKey('id', $product->getAttributes());
        $this->assertArrayHasKey('name_ar', $product->getAttributes());
        $this->assertArrayHasKey('name_en', $product->getAttributes());
        $this->assertArrayHasKey('slug', $product->getAttributes());
        $this->assertArrayHasKey('price', $product->getAttributes());
    }
}
