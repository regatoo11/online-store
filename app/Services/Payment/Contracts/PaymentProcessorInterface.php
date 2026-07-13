<?php

namespace App\Services\Payment\Contracts;

use App\Models\Order;
use App\Models\Payment;

interface PaymentProcessorInterface
{
    public function process(Order $order, array $data): Payment;

    public function getReceiptRequired(): bool;

    public function getInstructions(): ?string;
}
