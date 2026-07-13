<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Search\Contracts\SearchEngineInterface;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeEngine()
    {
        return Mockery::mock(SearchEngineInterface::class);
    }

    private function createProduct(array $overrides = []): Product
    {
        $category = Category::factory()->create(['is_active' => true, 'parent_id' => null]);

        return Product::factory()->create(array_merge([
            'category_id' => $category->id,
            'is_active' => true,
            'name_en' => 'Blue T-Shirt',
            'name_ar' => 'تيشيرت أزرق',
            'sku' => 'TS-001',
            'price' => 99.99,
            'description_en' => 'A comfortable blue cotton t-shirt',
        ], $overrides));
    }

    public function test_search_products_delegates_to_engine(): void
    {
        $term = 'shirt';
        $filters = ['category_id' => 1];
        $perPage = 8;

        $engine = $this->makeEngine();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            collect(), 0, $perPage, 1, ['path' => '/search']
        );

        $engine->shouldReceive('searchProducts')
            ->once()
            ->with($term, $filters, $perPage)
            ->andReturn($paginator);

        $service = new SearchService($engine);
        $result = $service->searchProducts($term, $filters, $perPage);

        $this->assertEquals($paginator, $result);
    }

    public function test_suggestions_delegates_to_engine(): void
    {
        $term = 'blue';
        $limit = 3;

        $engine = $this->makeEngine();
        $suggestions = collect(['product1', 'product2']);

        $engine->shouldReceive('getSuggestions')
            ->once()
            ->with($term, $limit)
            ->andReturn($suggestions);

        $service = new SearchService($engine);
        $result = $service->suggestions($term, $limit);

        $this->assertEquals($suggestions, $result);
    }

    public function test_search_categories_returns_active_root_categories_when_no_term(): void
    {
        $this->createProduct();
        $category = Category::active()->root()->first();

        $engine = $this->makeEngine();
        $service = new SearchService($engine);

        $result = $service->searchCategories('');

        $this->assertGreaterThanOrEqual(1, $result->count());
        $this->assertTrue($result->contains('id', $category->id));
    }

    public function test_search_categories_filters_by_term(): void
    {
        Category::factory()->create([
            'name_en' => 'Electronics',
            'name_ar' => 'إلكترونيات',
            'is_active' => true,
            'parent_id' => null,
        ]);

        Category::factory()->create([
            'name_en' => 'Fashion',
            'name_ar' => 'أزياء',
            'is_active' => true,
            'parent_id' => null,
        ]);

        $engine = $this->makeEngine();
        $service = new SearchService($engine);

        $result = $service->searchCategories('electro');

        $this->assertEquals(1, $result->count());
        $this->assertEquals('Electronics', $result->first()->name_en);
    }

    public function test_get_trending_returns_active_products(): void
    {
        $this->createProduct();
        $this->createProduct(['name_en' => 'Red T-Shirt', 'name_ar' => 'تيشيرت أحمر', 'sku' => 'TS-002']);

        $engine = $this->makeEngine();
        $service = new SearchService($engine);

        $result = $service->getTrending(2);

        $this->assertCount(2, $result);
        $this->assertTrue($result->every(fn ($p) => $p->is_active));
    }
}
