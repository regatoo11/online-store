<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        Setting::set($key, $value);
    }

    public function getGroup(string $group): Collection
    {
        return Setting::group($group);
    }

    public function updateGroup(string $group, array $data): void
    {
        Setting::updateGroup($group, $data);
    }
}
