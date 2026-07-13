<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    public function test_category_slug_is_auto_generated(): void
    {
        $category = Category::factory()->create(['name_en' => 'Test Category']);

        $this->assertEquals('test-category', $category->slug);
    }

    public function test_category_has_children(): void
    {
        $parent = Category::factory()->create();
        Category::factory()->count(2)->create(['parent_id' => $parent->id]);

        $this->assertCount(2, $parent->children);
    }

    public function test_category_has_products(): void
    {
        $category = Category::factory()->create();
        \App\Models\Product::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->products);
    }

    public function test_scope_active_filters_correctly(): void
    {
        Category::factory()->create(['is_active' => true]);
        Category::factory()->inactive()->create();

        $this->assertEquals(1, Category::active()->count());
    }

    public function test_scope_root_filters_correctly(): void
    {
        $parent = Category::factory()->create();
        Category::factory()->create(['parent_id' => $parent->id]);

        $this->assertCount(1, Category::root()->get());
    }

    public function test_full_name_attribute_returns_arabic_in_arabic_locale(): void
    {
        $category = Category::factory()->create(['name_ar' => 'إلكترونيات', 'name_en' => 'Electronics']);

        app()->setLocale('ar');

        $this->assertEquals('إلكترونيات', $category->full_name);
    }

    public function test_full_name_attribute_returns_english_in_english_locale(): void
    {
        $category = Category::factory()->create(['name_ar' => 'إلكترونيات', 'name_en' => 'Electronics']);

        app()->setLocale('en');

        $this->assertEquals('Electronics', $category->full_name);
    }
}
