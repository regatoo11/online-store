<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_categories_index_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.categories.index'))->assertStatus(200);
    }

    public function test_categories_create_page_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.categories.create'))->assertStatus(200);
    }

    public function test_store_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name_ar' => 'إلكترونيات',
            'name_en' => 'Electronics',
            'description_ar' => 'أجهزة إلكترونية',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name_en' => 'Electronics']);
    }

    public function test_store_category_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), []);

        $response->assertSessionHasErrors(['name_ar', 'name_en']);
    }

    public function test_edit_category_page_loads(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)->get(route('admin.categories.edit', $category))->assertStatus(200);
    }

    public function test_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name_ar' => 'إلكترونيات محدث',
            'name_en' => 'Updated Electronics',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name_en' => 'Updated Electronics']);
    }

    public function test_delete_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category))->assertRedirect();

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_customer_cannot_access_categories(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.categories.index'))->assertForbidden();
    }
}
