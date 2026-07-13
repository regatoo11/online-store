<?php

namespace Tests\Feature\Store;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveCategory(string $nameEn, string $nameAr): Category
    {
        return Category::factory()->create([
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
            'is_active' => true,
            'parent_id' => null,
        ]);
    }

    private function createActiveProduct(array $overrides = []): Product
    {
        $category = $this->createActiveCategory('Test Category', 'فئة اختبار');

        return Product::factory()->create(array_merge([
            'category_id' => $category->id,
            'is_active' => true,
            'name_en' => 'Test Product',
            'name_ar' => 'منتج اختبار',
            'sku' => 'TP-001',
            'price' => 50.00,
            'description_en' => 'A great test product',
            'description_ar' => 'منتج اختبار رائع',
        ], $overrides));
    }

    // ── SearchController (GET /search) ──────────────────────────────────

    public function test_search_page_loads(): void
    {
        $response = $this->get(route('search'));

        $response->assertStatus(200);
        $response->assertViewIs('store.products.index');
    }

    public function test_search_with_empty_query_returns_products(): void
    {
        $this->createActiveProduct(['name_en' => 'Wireless Mouse', 'name_ar' => 'ماوس لاسلكي']);

        $response = $this->get(route('search', ['q' => '']));

        $response->assertStatus(200);
        $response->assertViewHas('products');
        $products = $response->viewData('products');
        $this->assertGreaterThanOrEqual(1, $products->total());
    }

    public function test_search_by_product_name_english(): void
    {
        $this->createActiveProduct(['name_en' => 'Wireless Keyboard', 'name_ar' => 'كيبورد لاسلكي']);

        $response = $this->get(route('search', ['q' => 'wireless']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertGreaterThanOrEqual(1, $products->total());
    }

    public function test_search_by_product_name_arabic(): void
    {
        $this->createActiveProduct(['name_en' => 'Smart Watch', 'name_ar' => 'ساعة ذكية']);

        $response = $this->get(route('search', ['q' => 'ساعة']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertGreaterThanOrEqual(1, $products->total());
    }

    public function test_search_by_sku(): void
    {
        $this->createActiveProduct(['sku' => 'WB-2024-XYZ']);

        $response = $this->get(route('search', ['q' => 'WB-2024']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertGreaterThanOrEqual(1, $products->total());
    }

    public function test_search_by_description(): void
    {
        $this->createActiveProduct([
            'name_en' => 'Plain Shirt',
            'name_ar' => 'قميص عادي',
            'description_en' => 'Premium cotton flannel material',
        ]);

        $response = $this->get(route('search', ['q' => 'flannel']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertGreaterThanOrEqual(1, $products->total());
    }

    public function test_search_excludes_inactive_products(): void
    {
        $this->createActiveProduct([
            'name_en' => 'Visible Product',
            'name_ar' => 'منتج مرئي',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => Category::factory()->create(['is_active' => true, 'parent_id' => null])->id,
            'name_en' => 'Hidden Product',
            'name_ar' => 'منتج مخفي',
            'is_active' => false,
            'sku' => 'HIDDEN-001',
            'price' => 100,
            'description_en' => 'This should not appear',
            'description_ar' => 'هذا لا يجب أن يظهر',
        ]);

        $response = $this->get(route('search', ['q' => 'Product']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $names = $products->pluck('name_en')->toArray();
        $this->assertContains('Visible Product', $names);
        $this->assertNotContains('Hidden Product', $names);
    }

    public function test_search_no_results_returns_empty(): void
    {
        $response = $this->get(route('search', ['q' => 'zzzznonexistent']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertEquals(0, $products->total());
    }

    public function test_search_passes_categories_to_view(): void
    {
        $this->createActiveCategory('Shirts', 'قمصان');
        $this->createActiveCategory('Pants', 'بنطلونات');

        $response = $this->get(route('search', ['q' => '']));

        $response->assertStatus(200);
        $categories = $response->viewData('categories');
        $this->assertGreaterThanOrEqual(2, $categories->count());
    }

    public function test_search_pagination_works(): void
    {
        $category = $this->createActiveCategory('Paginate', 'صفحة');

        for ($i = 0; $i < 15; $i++) {
            Product::factory()->create([
                'category_id' => $category->id,
                'is_active' => true,
                'name_en' => "Paginate Product {$i}",
                'name_ar' => "منتج صفحة {$i}",
                'sku' => "PG-{$i}",
                'price' => 10 + $i,
                'description_en' => 'paginated product',
                'description_ar' => 'منتج مقسم صفحات',
            ]);
        }

        $response = $this->get(route('search', ['q' => 'Paginate']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertEquals(15, $products->total());
        $this->assertEquals(12, $products->perPage());
    }

    // ── Search Suggestions (GET /search/suggestions) ────────────────────

    public function test_suggestions_returns_json(): void
    {
        $this->createActiveProduct(['name_en' => 'Wireless Earbuds', 'name_ar' => 'سماعات لاسلكية']);

        $response = $this->getJson(route('search.suggestions', ['q' => 'wire']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'suggestions' => [
                ['id', 'name', 'slug', 'price'],
            ],
        ]);
    }

    public function test_suggestions_empty_term_returns_empty(): void
    {
        $response = $this->getJson(route('search.suggestions', ['q' => '']));

        $response->assertStatus(200);
        $response->assertJson(['suggestions' => []]);
    }

    public function test_suggestions_limit_works(): void
    {
        $category = $this->createActiveCategory('Suggest', 'اقتراح');

        for ($i = 0; $i < 10; $i++) {
            Product::factory()->create([
                'category_id' => $category->id,
                'is_active' => true,
                'name_en' => "Suggest Item {$i}",
                'name_ar' => "عنصر اقتراح {$i}",
                'sku' => "SG-{$i}",
                'price' => 5 + $i,
                'description_en' => 'suggested',
                'description_ar' => 'مقترح',
            ]);
        }

        $response = $this->getJson(route('search.suggestions', ['q' => 'Suggest']));

        $response->assertStatus(200);
        $suggestions = $response->json('suggestions');
        $this->assertLessThanOrEqual(5, count($suggestions));
    }

    // ── Store ProductController (GET /store/products) ───────────────────

    public function test_store_products_index_loads(): void
    {
        $response = $this->get(route('store.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('store.products.index');
    }

    public function test_store_products_search_works(): void
    {
        $this->createActiveProduct(['name_en' => 'Searchable Laptop', 'name_ar' => 'لابتوب قابل للبحث']);

        $response = $this->get(route('store.products.index', ['search' => 'laptop']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        $this->assertGreaterThanOrEqual(1, $products->total());
    }

    public function test_store_products_show_loads(): void
    {
        $product = $this->createActiveProduct();

        $response = $this->get(route('store.products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertViewIs('store.products.show');
        $response->assertViewHas('product');
        $response->assertViewHas('relatedProducts');
    }

    public function test_store_products_show_404_for_inactive(): void
    {
        $product = $this->createActiveProduct(['is_active' => false]);

        $response = $this->get(route('store.products.show', $product->slug));

        $response->assertStatus(404);
    }

    public function test_store_products_show_404_for_invalid_slug(): void
    {
        $response = $this->get(route('store.products.show', 'non-existent-slug-xyz'));

        $response->assertStatus(404);
    }
}
