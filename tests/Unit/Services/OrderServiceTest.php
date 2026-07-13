<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;
    protected OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrderService();
    }

    public function test_get_order_stats(): void
    {
        Order::factory()->pending()->count(2)->create();
        Order::factory()->delivered()->create();

        $stats = $this->service->getOrderStats();

        $this->assertEquals(3, $stats['total']);
        $this->assertEquals(2, $stats['pending']);
        $this->assertEquals(1, $stats['delivered']);
    }

    public function test_update_status(): void
    {
        $order = Order::factory()->pending()->create();

        $result = $this->service->updateStatus($order, 'confirmed');

        $this->assertEquals('confirmed', $result->status);
    }

    public function test_cancel_order_when_pending(): void
    {
        $order = Order::factory()->pending()->create();

        $result = $this->service->cancelOrder($order);

        $this->assertTrue($result);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }
}
