<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_settings_index_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.settings.index'))->assertStatus(200);
    }

    public function test_settings_displays_saved_values(): void
    {
        Setting::create(['key' => 'store_name', 'group' => 'general', 'value' => 'My Store']);

        $this->actingAs($this->admin)->get(route('admin.settings.index'))->assertStatus(200);
    }

    public function test_update_settings(): void
    {
        $response = $this->actingAs($this->admin)->patch(route('admin.settings.update'), [
            'general' => [
                'store_name' => 'Updated Store',
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', ['key' => 'general.store_name', 'value' => 'Updated Store']);
    }

    public function test_customer_cannot_access_settings(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.settings.index'))->assertForbidden();
    }
}
