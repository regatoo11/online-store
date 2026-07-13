<?php

namespace Tests\Unit\Services;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    use RefreshDatabase;
    protected SettingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SettingService();
    }

    public function test_get_returns_default_when_not_set(): void
    {
        $result = $this->service->get('nonexistent_key', 'default_value');

        $this->assertEquals('default_value', $result);
    }

    public function test_set_and_get(): void
    {
        $this->service->set('store_name', 'My Store');

        $this->assertEquals('My Store', $this->service->get('store_name'));
    }

    public function test_get_group(): void
    {
        Setting::create(['key' => 'name', 'group' => 'general', 'value' => 'Store']);
        Setting::create(['key' => 'email', 'group' => 'general', 'value' => 'store@example.com']);

        $group = $this->service->getGroup('general');

        $this->assertCount(2, $group);
    }

    public function test_update_group(): void
    {
        Setting::create(['key' => 'general.name', 'group' => 'general', 'value' => 'Old Store']);

        $this->service->updateGroup('general', ['name' => 'New Store']);

        $this->assertEquals('New Store', $this->service->get('general.name'));
    }
}
