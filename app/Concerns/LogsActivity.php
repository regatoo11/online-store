<?php

namespace App\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            static::logActivity('created', $model, [
                'attributes' => $model->getAttributes(),
            ]);
        });

        static::updated(function (Model $model) {
            $dirty = $model->getDirty();
            $original = $model->getOriginal();

            $oldValues = [];
            $newValues = [];

            foreach ($dirty as $key => $newValue) {
                $oldValues[$key] = $original[$key] ?? null;
                $newValues[$key] = $newValue;
            }

            static::logActivity('updated', $model, [
                'old_values' => $oldValues,
                'new_values' => $newValues,
            ]);
        });

        static::deleted(function (Model $model) {
            static::logActivity('deleted', $model, [
                'attributes' => $model->getAttributes(),
            ]);
        });
    }

    public static function logActivity(string $type, Model $subject, array $data = []): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->getKey(),
            'description' => static::getActivityDescription($type, $subject),
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? $data['attributes'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    private static function getActivityDescription(string $type, Model $subject): string
    {
        $modelClass = class_basename($subject);

        return match ($type) {
            'created' => "{$modelClass} created",
            'updated' => "{$modelClass} updated",
            'deleted' => "{$modelClass} deleted",
            default => "{$modelClass} {$type}",
        };
    }
}
