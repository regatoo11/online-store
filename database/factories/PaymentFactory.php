<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'transaction_id' => fake()->uuid(),
            'amount' => fake()->randomFloat(2, 50, 5000),
            'currency' => 'EGP',
            'status' => 'pending',
            'receipt_path' => null,
            'admin_notes' => null,
            'verified_by' => null,
            'verified_at' => null,
            'rejected_at' => null,
            'rejected_reason' => null,
            'paid_at' => null,
            'metadata' => null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => [
            'status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_reason' => fake()->sentence(),
        ]);
    }
}
