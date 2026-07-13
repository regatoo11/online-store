<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityService
{
    public function log(string $type, Model $subject, array $data = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->getKey(),
            'description' => $data['description'] ?? class_basename($subject) . " {$type}",
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getForSubject(Model $subject): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey())
            ->with('user')
            ->latest()
            ->get();
    }

    public function getRecent(int $days = 7): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::with('user')
            ->recent($days)
            ->latest()
            ->get();
    }

    public function getByType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::forType($type)
            ->with('user')
            ->latest()
            ->get();
    }
}
