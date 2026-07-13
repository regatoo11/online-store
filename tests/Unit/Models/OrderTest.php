<?php

namespace Tests\Unit\Models;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    public function test_order_get_status_label(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $this->assertEquals('Pending', $order->getStatusLabel());
    }

    public function test_order_can_be_cancelled_when_pending(): void
    {
        $order = Order::factory()->pending()->create();

        $this->assertTrue($order->canBeCancelled());
    }

    public function test_order_cannot_be_cancelled_when_delivered(): void
    {
        $order = Order::factory()->delivered()->create();

        $this->assertFalse($order->canBeCancelled());
    }

    public function test_order_has_items(): void
    {
        $order = Order::factory()->create();
        \App\Models\OrderItem::factory()->count(3)->create(['order_id' => $order->id]);

        $this->assertCount(3, $order->items);
    }

    public function test_order_has_payments(): void
    {
        $order = Order::factory()->create();
        $method = \App\Models\PaymentMethod::factory()->create();
        \App\Models\Payment::factory()->create([
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
        ]);

        $this->assertCount(1, $order->payments);
    }

    public function test_order_belongs_to_user(): void
    {
        $order = Order::factory()->create();

        $this->assertNotNull($order->user);
    }

    public function test_shipping_address_is_cast_to_array(): void
    {
        $order = Order::factory()->create();

        $this->assertIsArray($order->shipping_address);
        $this->assertArrayHasKey('name', $order->shipping_address);
    }
}
