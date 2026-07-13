<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Request $request, int $orderId): View
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->with(['items.product.primaryMedia', 'payments.method'])
            ->firstOrFail();

        return view('store.orders.show', compact('order'));
    }
}
