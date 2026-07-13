<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        return [
            'name_ar' => 'الدفع عند الاستلام',
            'name_en' => 'Cash on Delivery',
            'code' => fake()->unique()->bothify('??-####'),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
