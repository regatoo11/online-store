<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function upload(UploadedFile $file, array $options = [], ?Model $model = null): Media
    {
        $uuid = (string) Str::uuid();
        $year = date('Y');
        $month = date('m');
        $path = "media/{$year}/{$month}/{$uuid}.webp";

        $file->store($path, 'public');

        $media = Media::create([
            'uuid' => $uuid,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'is_primary' => $options['is_primary'] ?? false,
            'sort_order' => $options['sort_order'] ?? 0,
            'alt_text' => $options['alt_text'] ?? null,
            'caption' => $options['caption'] ?? null,
        ]);

        if ($model) {
            $model->media()->save($media);
        }

        return $media;
    }

    public function delete(Media $media): void
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Media::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    public function getForModel(Model $model): Collection
    {
        return $model->media()->ordered()->get();
    }
}
