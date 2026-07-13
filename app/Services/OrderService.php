<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrder(Cart $cart, array $data): Order
    {
        return DB::transaction(function () use ($cart, $data) {
            $orderNumber = 'ORD-' . strtoupper(Str::random(10));

            $order = Order::create([
                'user_id' => $cart->user_id,
                'order_number' => $orderNumber,
                'status' => OrderStatus::Pending->value,
                'subtotal' => $cart->getSubtotal(),
                'tax_amount' => $data['tax_amount'] ?? 0,
                'discount' => $cart->discount,
                'shipping_cost' => $data['shipping_cost'] ?? 0,
                'total' => $cart->getTotal() + ($data['tax_amount'] ?? 0) + ($data['shipping_cost'] ?? 0),
                'currency' => $data['currency'] ?? 'EGP',
                'coupon_code' => $cart->coupon?->code,
                'shipping_method' => $data['shipping_method'] ?? null,
                'notes' => $data['notes'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'billing_address' => $data['billing_address'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $unitPrice = $item->getUnitPrice();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name_en ?? $item->product->name_ar,
                    'product_sku' => $item->product->sku ?? null,
                    'price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'total' => $unitPrice * $item->quantity,
                ]);
            }

            if ($cart->coupon_id) {
                $cart->coupon->increment('used_count');
            }

            return $order->load('items');
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        return $order;
    }

    public function cancelOrder(Order $order): bool
    {
        if (!$order->canBeCancelled()) {
            return false;
        }

        $order->update(['status' => OrderStatus::Cancelled->value]);

        return true;
    }

    public function getOrderStats(): array
    {
        $query = Order::query();

        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', OrderStatus::Pending->value)->count(),
            'confirmed' => (clone $query)->where('status', OrderStatus::Confirmed->value)->count(),
            'processing' => (clone $query)->where('status', OrderStatus::Processing->value)->count(),
            'shipped' => (clone $query)->where('status', OrderStatus::Shipped->value)->count(),
            'delivered' => (clone $query)->where('status', OrderStatus::Delivered->value)->count(),
            'cancelled' => (clone $query)->where('status', OrderStatus::Cancelled->value)->count(),
            'total_revenue' => (clone $query)->where('status', OrderStatus::Delivered->value)->sum('total'),
        ];
    }
}
