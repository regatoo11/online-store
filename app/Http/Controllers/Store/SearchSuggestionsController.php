<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchSuggestionsController extends Controller
{
    public function __construct(
        protected SearchService $search,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $term = $request->input('q', '');

        $suggestions = $this->search->suggestions($term, 5);

        return response()->json([
            'suggestions' => $suggestions->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name_ar,
                'slug' => $product->slug,
                'price' => $product->getDisplayPrice(),
            ]),
        ]);
    }
}
