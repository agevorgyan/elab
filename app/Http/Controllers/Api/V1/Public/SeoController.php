<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicSeoMetadataResource;
use App\Models\SeoMetadata;
use Illuminate\Http\JsonResponse;

class SeoController extends Controller
{
    public function show(?string $slug = null): JsonResponse
    {
        $cleanPath = $slug ? trim($slug) : '/';
        
        $possiblePaths = [
            $cleanPath,
            '/' . ltrim($cleanPath, '/'),
            ltrim($cleanPath, '/'),
        ];

        $seo = SeoMetadata::query()
            ->whereIn('path', array_unique($possiblePaths))
            ->first();

        if (!$seo) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PublicSeoMetadataResource($seo),
        ]);
    }
}
