<?php

namespace Tests\Feature\Store;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        PaymentMethod::factory()->create(['code' => 'cod', 'name_en' => 'Cash on Delivery']);
    }

    public function test_checkout_page_loads_with_cart_items(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->forUser($user)->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10, 'is_active' => true]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($user)->get(route('store.checkout.index'));

        $response->assertStatus(200);
    }

    public function test_checkout_redirects_when_cart_is_empty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('store.checkout.index'));

        $response->assertRedirect(route('store.cart.index'));
    }

    public function test_place_order(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->forUser($user)->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'is_active' => true,
            'price' => 100,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('store.checkout.store'), [
            'name' => 'أحمد محمد',
            'phone' => '01012345678',
            'governorate' => 'القاهرة',
            'city' => 'المعادي',
            'address' => 'شارع 9، المعادي',
            'shipping_method' => 'standard',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'currency' => 'EGP',
        ]);
    }

    public function test_place_order_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('store.checkout.store'), []);

        $response->assertSessionHasErrors(['name', 'phone', 'governorate', 'city', 'address', 'shipping_method', 'payment_method']);
    }

    public function test_order_clears_cart_after_placement(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->forUser($user)->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'is_active' => true,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)->post(route('store.checkout.store'), [
            'name' => 'أحمد محمد',
            'phone' => '01012345678',
            'governorate' => 'القاهرة',
            'city' => 'المعادي',
            'address' => 'شارع 9، المعادي',
            'shipping_method' => 'standard',
            'payment_method' => 'cod',
        ]);

        $this->assertDatabaseCount('cart_items', 0);
    }
}
