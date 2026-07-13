<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_orders_index_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.orders.index'))->assertStatus(200);
    }

    public function test_orders_index_displays_orders(): void
    {
        Order::factory()->count(3)->create();

        $this->actingAs($this->admin)->get(route('admin.orders.index'))->assertStatus(200);
    }

    public function test_order_show_loads(): void
    {
        $order = Order::factory()->create();

        $this->actingAs($this->admin)->get(route('admin.orders.show', $order))->assertStatus(200);
    }

    public function test_filter_orders_by_status(): void
    {
        Order::factory()->pending()->count(2)->create();
        Order::factory()->delivered()->create();

        $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'pending']))->assertStatus(200);
    }

    public function test_search_orders(): void
    {
        Order::factory()->create(['order_number' => 'ORD-TEST12345']);

        $this->actingAs($this->admin)->get(route('admin.orders.index', ['search' => 'TEST12345']))->assertStatus(200);
    }

    public function test_update_order_status(): void
    {
        $order = Order::factory()->pending()->create();

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'confirmed']);
    }

    public function test_customer_cannot_access_orders(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.orders.index'))->assertForbidden();
    }
}
