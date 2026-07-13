<?php

namespace App\Concerns;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasMedia
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function primaryMedia(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('is_primary', true);
    }

    public function clearMedia(): void
    {
        $this->media->each(function (Media $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        });
    }

    public function addMediaFromFile(string $path, array $attributes = []): Media
    {
        $fileContents = file_get_contents($path);
        $fileName = basename($path);
        $fileType = mime_content_type($path);
        $fileSize = filesize($path);

        $uuid = (string) Str::uuid();
        $year = date('Y');
        $month = date('m');
        $storedPath = "media/{$year}/{$month}/{$uuid}.webp";

        Storage::disk('public')->put($storedPath, $fileContents);

        return $this->media()->create(array_merge([
            'uuid' => $uuid,
            'file_path' => $storedPath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
        ], $attributes));
    }
}
