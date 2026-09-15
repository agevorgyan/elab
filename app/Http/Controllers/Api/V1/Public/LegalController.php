<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicLegalPageResource;
use App\Models\LegalPage;
use Illuminate\Http\JsonResponse;

class LegalController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $page = LegalPage::query()
            ->where('slug', $slug)
            ->where('published', true)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PublicLegalPageResource($page),
        ]);
    }
}
