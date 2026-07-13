<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitors_can_switch_to_english(): void
    {
        $response = $this->get('/lang/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
    }

    public function test_visitors_can_switch_back_to_arabic(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/lang/ar');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'ar');
    }

    public function test_unsupported_locales_are_rejected(): void
    {
        $this->get('/lang/fr')->assertNotFound();
    }
}
