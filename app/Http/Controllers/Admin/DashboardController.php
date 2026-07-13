<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboard,
    ) {}

    public function index(): View
    {
        $widgets = $this->dashboard->getWidgets();

        return view('admin.dashboard', compact('widgets'));
    }
}
