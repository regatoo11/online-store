<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadMediaRequest;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(
        private readonly MediaService $mediaService
    ) {}

    public function store(UploadMediaRequest $request): JsonResponse
    {
        $media = $this->mediaService->upload($request->file('file'), [
            'is_primary' => $request->boolean('is_primary'),
            'alt_text' => $request->input('alt_text'),
            'caption' => $request->input('caption'),
        ]);

        return response()->json([
            'data' => $media,
            'message' => 'Media uploaded successfully.',
        ]);
    }

    public function destroy(Media $media): JsonResponse
    {
        $this->mediaService->delete($media);

        return response()->json([
            'message' => 'Media deleted successfully.',
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:media,id'],
        ]);

        $this->mediaService->reorder($request->input('ids'));

        return response()->json([
            'message' => 'Media reordered successfully.',
        ]);
    }
}
