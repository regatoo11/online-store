<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function __construct(
        private SettingService $settingService,
    ) {}

    public function index(): View
    {
        $settings = [];
        foreach (['general', 'shipping', 'payment', 'maintenance'] as $group) {
            $settings[$group] = $this->settingService->getGroup($group)
                ->mapWithKeys(fn ($s) => [str_replace($group . '.', '', $s->key) => $s->value])
                ->toArray();
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->all();

        foreach (['general', 'shipping', 'payment', 'maintenance'] as $group) {
            if (isset($data[$group]) && is_array($data[$group])) {
                $this->settingService->updateGroup($group, $data[$group]);
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }
}
