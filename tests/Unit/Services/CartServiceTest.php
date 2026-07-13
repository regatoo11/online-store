<?php

namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;
    protected CartService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CartService();
    }

    public function test_get_or_create_cart_for_user(): void
    {
        $user = \App\Models\User::factory()->create();

        $cart = $this->service->getOrCreateCart($user->id, null);

        $this->assertNotNull($cart);
        $this->assertEquals($user->id, $cart->user_id);
    }

    public function test_get_or_create_cart_for_session(): void
    {
        $sessionId = 'test-session-123';

        $cart = $this->service->getOrCreateCart(null, $sessionId);

        $this->assertNotNull($cart);
        $this->assertEquals($sessionId, $cart->session_id);
    }

    public function test_add_item_to_cart(): void
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $item = $this->service->addItem($cart, $product->id, 2);

        $this->assertNotNull($item);
        $this->assertEquals(2, $item->quantity);
        $this->assertEquals($product->id, $item->product_id);
    }

    public function test_add_item_increments_existing(): void
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->service->addItem($cart, $product->id, 1);
        $item = $this->service->addItem($cart, $product->id, 3);

        $this->assertEquals(4, $item->quantity);
    }

    public function test_update_quantity(): void
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        $item = CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        $this->service->updateQuantity($cart, $item->id, 5);

        $this->assertDatabaseHas('cart_items', ['id' => $item->id, 'quantity' => 5]);
    }

    public function test_update_quantity_zero_deletes_item(): void
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        $item = CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);

        $this->service->updateQuantity($cart, $item->id, 0);

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_remove_item(): void
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        $item = CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);

        $result = $this->service->removeItem($cart, $item->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_clear_cart(): void
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);

        $this->service->clear($cart);

        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('carts', ['id' => $cart->id, 'discount' => 0]);
    }

    public function test_apply_valid_coupon(): void
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create(['price' => 200]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);
        $cart->load('items.product');

        $coupon = Coupon::factory()->create([
            'code' => 'SAVE10',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 0,
        ]);

        $result = $this->service->applyCoupon($cart, 'SAVE10');

        $this->assertTrue($result);
    }

    public function test_apply_invalid_coupon_returns_false(): void
    {
        $cart = Cart::factory()->create();

        $result = $this->service->applyCoupon($cart, 'INVALID');

        $this->assertFalse($result);
    }
}
