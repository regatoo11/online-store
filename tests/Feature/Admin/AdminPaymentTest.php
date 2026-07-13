<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_payments_index_loads(): void
    {
        $this->actingAs($this->admin)->get(route('admin.payments.index'))->assertStatus(200);
    }

    public function test_payments_index_displays_payments(): void
    {
        $method = PaymentMethod::factory()->create();
        $order = Order::factory()->create();
        Payment::factory()->count(2)->create([
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
        ]);

        $this->actingAs($this->admin)->get(route('admin.payments.index'))->assertStatus(200);
    }

    public function test_payment_show_loads(): void
    {
        $method = PaymentMethod::factory()->create();
        $order = Order::factory()->create();
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
        ]);

        $this->actingAs($this->admin)->get(route('admin.payments.show', $payment))->assertStatus(200);
    }

    public function test_verify_payment(): void
    {
        $method = PaymentMethod::factory()->create();
        $order = Order::factory()->create(['total' => 100]);
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
            'amount' => 100,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.payments.verify', $payment));

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'verified']);
    }

    public function test_reject_payment(): void
    {
        $method = PaymentMethod::factory()->create();
        $order = Order::factory()->create();
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.payments.reject', $payment), [
            'reason' => 'Invalid receipt',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'rejected']);
    }

    public function test_customer_cannot_access_payments(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.payments.index'))->assertForbidden();
    }
}
