<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->bothify('??####')),
            'type' => 'percentage',
            'value' => fake()->numberBetween(5, 50),
            'min_order_amount' => 0,
            'max_uses' => null,
            'used_count' => 0,
            'starts_at' => Carbon::now()->subDay(),
            'expires_at' => Carbon::now()->addMonth(),
            'is_active' => true,
        ];
    }

    public function fixed(): static
    {
        return $this->state(fn () => ['type' => 'fixed', 'value' => fake()->numberBetween(10, 100)]);
    }

    public function expired(): static
    {
        return $this->state(fn () => ['expires_at' => Carbon::now()->subDay()]);
    }
}
