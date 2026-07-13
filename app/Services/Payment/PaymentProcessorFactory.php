<?php

namespace App\Services\Payment;

use App\Models\PaymentMethod;
use App\Services\Payment\Contracts\PaymentProcessorInterface;
use InvalidArgumentException;

class PaymentProcessorFactory
{
    private static array $processors = [
        'cod' => CodPaymentProcessor::class,
        'bank_transfer' => ReceiptPaymentProcessor::class,
        'wallet' => ReceiptPaymentProcessor::class,
        'fawry' => ReceiptPaymentProcessor::class,
    ];

    public static function make(string $methodCode): PaymentProcessorInterface
    {
        $processorClass = self::$processors[$methodCode] ?? null;

        if (!$processorClass) {
            $method = PaymentMethod::where('code', $methodCode)->first();

            if ($method && $method->requires_receipt) {
                $processorClass = ReceiptPaymentProcessor::class;
            }
        }

        if (!$processorClass) {
            throw new InvalidArgumentException("No processor found for payment method: {$methodCode}");
        }

        return new $processorClass();
    }

    public static function register(string $methodCode, string $processorClass): void
    {
        self::$processors[$methodCode] = $processorClass;
    }
}
