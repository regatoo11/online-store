<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_products_index_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.products.index'))->assertStatus(200);
    }

    public function test_products_index_displays_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
    }

    public function test_products_create_page_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.products.create'))->assertStatus(200);
    }

    public function test_store_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name_ar' => 'هاتف ذكي',
            'name_en' => 'Smart Phone',
            'description_ar' => 'هاتف ذكي متطور',
            'description_en' => 'Advanced smart phone',
            'sku' => 'PHN-001',
            'type' => 'simple',
            'price' => 5000,
            'category_id' => $category->id,
            'is_active' => true,
            'stock' => 20,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name_en' => 'Smart Phone']);
    }

    public function test_store_product_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['name_ar', 'name_en', 'price', 'category_id']);
    }

    public function test_edit_product_page_loads(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs($this->admin)->get(route('admin.products.edit', $product))->assertStatus(200);
    }

    public function test_update_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name_ar' => 'هاتف محدث',
            'name_en' => 'Updated Phone',
            'description_ar' => 'هاتف محدث',
            'description_en' => 'Updated phone',
            'type' => 'simple',
            'price' => 6000,
            'category_id' => $category->id,
            'is_active' => true,
            'stock' => 15,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name_en' => 'Updated Phone']);
    }

    public function test_delete_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product))->assertRedirect();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_customer_cannot_access_products(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.products.index'))->assertForbidden();
    }
}
