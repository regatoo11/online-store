<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const EGYPTIAN_GOVERNORATES = [
        'القاهرة', 'الجيزة', 'الإسكندرية', 'القليوبية', 'المنوفية',
        'الغربية', 'الدقهلية', 'الدمياط', 'كفر الشيخ', 'البحيرة',
        'الفيوم', 'بني سويف', 'المنيا', 'أسيوط', 'سوهاج',
        'قنا', 'الأقصر', 'أسوان', 'البحر الأحمر', 'الوادي الجديد',
        'مطروح', 'شمال سيناء', 'جنوب سيناء', 'الشرقية',
    ];

    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private PaymentService $paymentService,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->session()->getId(),
        );

        $cart->load('items.product.primaryMedia', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('store.cart.index')->with('error', __('Your cart is empty.'));
        }

        $cartItems = $cart->items;
        $subtotal = $cart->getSubtotal();
        $shippingCost = $subtotal > 500 ? 0 : 50;
        $discount = $cart->discount;
        $tax = 0;
        $total = $subtotal + $shippingCost - $discount + $tax;
        $governorates = self::EGYPTIAN_GOVERNORATES;

        $shippingMethods = collect([
            (object) [
                'id' => 'standard',
                'name_ar' => 'التوصيل العادي',
                'description_ar' => 'توصيل خلال 3-5 أيام عمل',
                'estimated_days' => '3-5 أيام',
                'cost' => $shippingCost,
            ],
        ]);

        return view('store.checkout.index', compact(
            'cartItems', 'subtotal', 'shippingCost', 'discount', 'tax', 'total',
            'governorates', 'shippingMethods',
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'governorate' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string|in:cod,instapay,wallet',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->session()->getId(),
        );

        $cart->load('items.product', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('store.cart.index')->with('error', __('Your cart is empty.'));
        }

        $shippingCost = $cart->getSubtotal() > 500 ? 0 : 50;

        $order = $this->orderService->createOrder($cart, [
            'shipping_address' => [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'governorate' => $validated['governorate'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'] ?? null,
                'address' => $validated['address'],
            ],
            'shipping_method' => $validated['shipping_method'],
            'shipping_cost' => $shippingCost,
            'currency' => 'EGP',
            'notes' => $validated['notes'] ?? null,
        ]);

        $paymentMethod = PaymentMethod::where('code', $validated['payment_method'])->first();

        if ($paymentMethod) {
            $this->paymentService->processPayment($order, $paymentMethod->id);
        }

        $this->cartService->clear($cart);

        return redirect()->route('store.orders.show', $order->id)
            ->with('success', __('Order placed successfully.'));
    }
}
