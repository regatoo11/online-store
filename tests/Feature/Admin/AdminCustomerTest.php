<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCustomerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_customers_index_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.customers.index'))->assertStatus(200);
    }

    public function test_customers_index_displays_customers(): void
    {
        User::factory()->count(3)->create(['role' => 'customer']);

        $this->actingAs($this->admin)->get(route('admin.customers.index'))->assertStatus(200);
    }

    public function test_customer_show_loads(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($this->admin)->get(route('admin.customers.show', $customer))->assertStatus(200);
    }

    public function test_search_customers(): void
    {
        User::factory()->create(['name' => 'Ahmed Hassan', 'role' => 'customer']);
        User::factory()->create(['name' => 'Sara Ali', 'role' => 'customer']);

        $this->actingAs($this->admin)->get(route('admin.customers.index', ['search' => 'Ahmed']))->assertStatus(200);
    }

    public function test_customer_cannot_access_customers(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)->get(route('admin.customers.index'))->assertForbidden();
    }
}
