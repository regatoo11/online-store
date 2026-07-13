<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService,
    ) {}

    public function index(Request $request): View
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->session()->getId(),
        );

        $cart->load('items.product.primaryMedia', 'items.variant');

        $cartItems = $cart->items;
        $subtotal = $cart->getSubtotal();
        $shippingCost = $subtotal > 500 ? 0 : 50;
        $discount = $cart->discount;
        $total = $subtotal + $shippingCost - $discount;

        return view('store.cart.index', compact('cartItems', 'subtotal', 'shippingCost', 'discount', 'total'));
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isAvailable()) {
            return redirect()->back()->with('error', __('Product is out of stock.'));
        }

        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->session()->getId(),
        );

        $this->cartService->addItem(
            $cart,
            $request->product_id,
            $request->quantity,
            $request->variant_id,
        );

        return redirect()->route('store.cart.index')->with('success', __('Item added to cart.'));
    }

    public function update(Request $request, int $cartItemId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->session()->getId(),
        );

        $this->cartService->updateQuantity($cart, $cartItemId, $request->quantity);

        return redirect()->route('store.cart.index')->with('success', __('Cart updated.'));
    }

    public function remove(Request $request, int $cartItemId): RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->session()->getId(),
        );

        $this->cartService->removeItem($cart, $cartItemId);

        return redirect()->route('store.cart.index')->with('success', __('Item removed from cart.'));
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->session()->getId(),
        );

        $applied = $this->cartService->applyCoupon($cart, $request->coupon_code);

        if ($applied) {
            return redirect()->route('store.cart.index')->with('coupon_success', __('Coupon applied successfully.'));
        }

        return redirect()->route('store.cart.index')->with('coupon_error', __('Invalid or expired coupon code.'));
    }
}
