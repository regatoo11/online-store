<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(fake()->bothify('??########')),
            'status' => 'pending',
            'subtotal' => fake()->randomFloat(2, 50, 5000),
            'tax_amount' => 0,
            'discount' => 0,
            'shipping_cost' => 50,
            'total' => fake()->randomFloat(2, 100, 5100),
            'currency' => 'EGP',
            'coupon_code' => null,
            'shipping_method' => 'standard',
            'notes' => null,
            'shipping_address' => [
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'governorate' => 'القاهرة',
                'city' => fake()->city(),
                'address' => fake()->address(),
                'postal_code' => null,
            ],
            'billing_address' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function delivered(): static
    {
        return $this->state(fn () => ['status' => 'delivered']);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelled']);
    }
}
