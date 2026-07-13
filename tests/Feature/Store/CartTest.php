<?php

namespace Tests\Feature\Store;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_loads(): void
    {
        $this->get(route('store.cart.index'))->assertStatus(200);
    }

    public function test_add_to_cart(): void
    {
        $user = User::factory()->create();
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10, 'is_active' => true]);

        $response = $this->actingAs($user)->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_add_to_cart_with_invalid_product_fails(): void
    {
        $response = $this->post(route('store.cart.add'), [
            'product_id' => 99999,
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    public function test_update_cart_quantity(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->forUser($user)->create();
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10]);
        $item = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->patch(route('store.cart.update', $item->id), [
            'quantity' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 5,
        ]);
    }

    public function test_remove_from_cart(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->forUser($user)->create();
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10]);
        $item = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->delete(route('store.cart.remove', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_add_to_cart_increments_quantity_for_existing_item(): void
    {
        $user = User::factory()->create();
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10, 'is_active' => true]);

        $this->actingAs($user)->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $cartItem = \App\Models\CartItem::where('product_id', $product->id)->first();
        $this->assertEquals(3, $cartItem->quantity);
    }
}
