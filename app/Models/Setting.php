<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->castValue();
    }

    public static function set(string $key, mixed $value): static
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            $parts = explode('.', $key);
            $group = $parts[0] ?? 'general';

            $setting = static::create([
                'group' => $group,
                'key' => $key,
                'value' => $value,
            ]);
        }

        return $setting;
    }

    public static function group(string $group): Collection
    {
        return static::where('group', $group)->get();
    }

    public static function updateGroup(string $group, array $data): void
    {
        foreach ($data as $key => $value) {
            $fullKey = "{$group}.{$key}";
            static::set($fullKey, $value);
        }
    }

    private function castValue(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }
}
