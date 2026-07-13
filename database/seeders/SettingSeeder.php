<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('general/site_name', 'المتجر الإلكتروني');
        Setting::set('general/site_email', 'info@store.test');
        Setting::set('general/site_phone', '01000000000');
        Setting::set('general/currency', 'EGP');
        Setting::set('general/currency_symbol', 'ج.م');
        Setting::set('shipping/standard_cost', '50');
        Setting::set('shipping/express_cost', '100');
        Setting::set('shipping/free_above', '500');
        Setting::set('maintenance/enabled', 'false');
        Setting::set('maintenance/message', 'الموقع في وضع الصيانة حالياً');
    }
}
