<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::create([
            'name_ar' => 'الدفع عند الاستلام',
            'name_en' => 'Cash on Delivery',
            'code' => 'cod',
            'requires_receipt' => false,
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_ar' => 'انستاباي',
            'name_en' => 'InstaPay',
            'code' => 'instapay',
            'requires_receipt' => true,
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_ar' => 'فودافون كاش',
            'name_en' => 'Vodafone Cash',
            'code' => 'vodafone_cash',
            'requires_receipt' => true,
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_ar' => 'أورانج كاش',
            'name_en' => 'Orange Cash',
            'code' => 'orange_cash',
            'requires_receipt' => true,
            'is_active' => true,
        ]);
    }
}
