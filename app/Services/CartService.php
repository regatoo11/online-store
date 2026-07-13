<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getOrCreateCart(?int $userId, ?string $sessionId): Cart
    {
        if ($userId) {
            return Cart::firstOrCreate(
                ['user_id' => $userId],
                ['discount' => 0]
            );
        }

        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['discount' => 0]
        );
    }

    public function addItem(Cart $cart, int $productId, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $existingItem = $cart->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($existingItem) {
            $existingItem->update(['quantity' => $existingItem->quantity + $quantity]);

            return $existingItem;
        }

        return $cart->items()->create([
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(Cart $cart, int $cartItemId, int $quantity): CartItem
    {
        $item = $cart->items()->findOrFail($cartItemId);

        if ($quantity <= 0) {
            $item->delete();

            return $item;
        }

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    public function removeItem(Cart $cart, int $cartItemId): bool
    {
        return $cart->items()->where('id', $cartItemId)->delete() > 0;
    }

    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update(['coupon_id' => null, 'discount' => 0]);
    }

    public function applyCoupon(Cart $cart, string $code): bool
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return false;
        }

        $subtotal = $cart->getSubtotal();
        $discount = $coupon->calculateDiscount($subtotal);

        $cart->update([
            'coupon_id' => $coupon->id,
            'discount' => $discount,
        ]);

        return true;
    }

    public function removeCoupon(Cart $cart): void
    {
        $cart->update([
            'coupon_id' => null,
            'discount' => 0,
        ]);
    }

    public function mergeGuestCart(Cart $guestCart, int $userId): Cart
    {
        $userCart = $this->getOrCreateCart($userId, null);

        foreach ($guestCart->items as $item) {
            $existingItem = $userCart->items()
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $item->quantity,
                ]);
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        if ($guestCart->coupon_id) {
            $coupon = Coupon::find($guestCart->coupon_id);
            if ($coupon && $coupon->isValid()) {
                $this->applyCoupon($userCart, $coupon->code);
            }
        }

        $guestCart->forceDelete();

        return $userCart;
    }

    public function validateAvailability(Cart $cart): array
    {
        $errors = [];

        foreach ($cart->items as $item) {
            $product = $item->product;

            if (!$product || !$product->is_active) {
                $errors[] = "Product #{$item->product_id} is no longer available";
                continue;
            }

            if ($product->stock_quantity < $item->quantity) {
                $errors[] = "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}";
            }
        }

        return $errors;
    }
}
