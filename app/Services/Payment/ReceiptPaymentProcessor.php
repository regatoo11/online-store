<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\Payment\Contracts\PaymentProcessorInterface;
use Illuminate\Support\Facades\Storage;

class ReceiptPaymentProcessor implements PaymentProcessorInterface
{
    public function process(Order $order, array $data): Payment
    {
        $method = PaymentMethod::where('code', $data['method_code'] ?? 'bank_transfer')->firstOrFail();

        $receiptPath = null;
        if (isset($data['receipt'])) {
            $receiptPath = $data['receipt']->store('receipts', 'public');
        }

        return Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
            'amount' => $order->total,
            'currency' => $order->currency,
            'status' => 'pending',
            'receipt_path' => $receiptPath,
            'metadata' => array_filter($data, fn ($value) => $value !== ($data['receipt'] ?? null)),
        ]);
    }

    public function getReceiptRequired(): bool
    {
        return true;
    }

    public function getInstructions(): ?string
    {
        $method = PaymentMethod::where('code', 'bank_transfer')->first();

        return $method?->instructions_en;
    }
}
