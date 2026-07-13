<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\Payment\PaymentProcessorFactory;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function processPayment(Order $order, int $paymentMethodId, array $data = []): Payment
    {
        $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);

        $processor = PaymentProcessorFactory::make($paymentMethod->code);

        return $processor->process($order, $data);
    }

    public function verifyPayment(Payment $payment, ?int $verifiedBy = null): Payment
    {
        return DB::transaction(function () use ($payment, $verifiedBy) {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $verifiedBy ?? auth()->id(),
                'verified_at' => now(),
            ]);

            $this->updateOrderStatus($payment->order);

            return $payment;
        });
    }

    public function rejectPayment(Payment $payment, string $reason, ?int $verifiedBy = null): Payment
    {
        return DB::transaction(function () use ($payment, $reason, $verifiedBy) {
            $payment->update([
                'status' => 'rejected',
                'verified_by' => $verifiedBy ?? auth()->id(),
                'rejected_at' => now(),
                'rejected_reason' => $reason,
            ]);

            return $payment;
        });
    }

    public function getPendingPayments()
    {
        return Payment::where('status', 'pending')
            ->with(['order', 'method'])
            ->latest()
            ->get();
    }

    private function updateOrderStatus(Order $order): void
    {
        $verifiedTotal = $order->payments()
            ->where('status', 'verified')
            ->sum('amount');

        if ($verifiedTotal >= $order->total) {
            $order->update(['status' => 'confirmed']);
        }
    }
}
