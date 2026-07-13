<?php

namespace Tests\Unit\Models;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;
    public function test_cart_get_subtotal(): void
    {
        $cart = Cart::factory()->create();
        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 200]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product1->id, 'quantity' => 2]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product2->id, 'quantity' => 1]);

        $cart->load('items.product');

        $this->assertEquals(400.0, $cart->getSubtotal());
    }

    public function test_cart_get_total_subtracts_discount(): void
    {
        $cart = Cart::factory()->create(['discount' => 50]);
        $product = Product::factory()->create(['price' => 200]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        $cart->load('items.product');

        $this->assertEquals(150.0, $cart->getTotal());
    }

    public function test_cart_item_count(): void
    {
        $cart = Cart::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product1->id, 'quantity' => 3]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product2->id, 'quantity' => 2]);

        $this->assertEquals(5, $cart->getItemCount());
    }
}
