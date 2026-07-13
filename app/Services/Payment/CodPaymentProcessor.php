<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\Payment\Contracts\PaymentProcessorInterface;

class CodPaymentProcessor implements PaymentProcessorInterface
{
    public function process(Order $order, array $data): Payment
    {
        $method = PaymentMethod::where('code', 'cod')->firstOrFail();

        return Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
            'amount' => $order->total,
            'currency' => $order->currency,
            'status' => 'pending',
            'paid_at' => now(),
            'metadata' => $data,
        ]);
    }

    public function getReceiptRequired(): bool
    {
        return false;
    }

    public function getInstructions(): ?string
    {
        return null;
    }
}
