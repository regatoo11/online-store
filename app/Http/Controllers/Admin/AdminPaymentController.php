<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
    ) {}

    public function index(Request $request): View
    {
        $query = Payment::with(['order', 'method']);

        if ($search = $request->input('search')) {
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $payments = $query->latest()->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['order.items', 'method', 'verifier']);

        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Payment $payment): RedirectResponse
    {
        $this->paymentService->verifyPayment($payment, auth()->id());

        return redirect()->back()->with('success', __('Payment verified successfully.'));
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->paymentService->rejectPayment($payment, $validated['reason'], auth()->id());

        return redirect()->back()->with('success', __('Payment rejected.'));
    }
}
