<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * The home page loads successfully.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * The default locale is Arabic.
     */
    public function test_default_locale_is_arabic(): void
    {
        $this->assertSame('ar', config('app.locale'));
    }
}
