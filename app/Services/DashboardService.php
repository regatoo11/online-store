<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    public function getWidgets(): array
    {
        $today = Carbon::today();

        return [
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)
                ->where('status', OrderStatus::Delivered->value)
                ->sum('total'),
            'total_products' => Product::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', OrderStatus::Delivered->value)->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
        ];
    }
}
