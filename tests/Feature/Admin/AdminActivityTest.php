<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActivityTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_activity_log_index_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.activity-log.index'))->assertStatus(200);
    }

    public function test_activity_log_displays_activities(): void
    {
        ActivityLog::factory()->count(3)->create(['user_id' => $this->admin->id]);

        $this->actingAs($this->admin)->get(route('admin.activity-log.index'))->assertStatus(200);
    }

    public function test_filter_activity_by_type(): void
    {
        ActivityLog::factory()->create(['user_id' => $this->admin->id, 'type' => 'created']);
        ActivityLog::factory()->create(['user_id' => $this->admin->id, 'type' => 'updated']);

        $this->actingAs($this->admin)->get(route('admin.activity-log.index', ['type' => 'created']))->assertStatus(200);
    }

    public function test_customer_cannot_access_activity_log(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.activity-log.index'))->assertForbidden();
    }
}
