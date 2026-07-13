<?php

namespace Tests\Feature\Store;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_show_page_loads(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('store.orders.show', $order->id));

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_other_users_order(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get(route('store.orders.show', $order->id));

        $response->assertNotFound();
    }

    public function test_guest_cannot_view_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->get(route('store.orders.show', $order->id))->assertRedirect(route('login'));
    }
}
