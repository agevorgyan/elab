<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicPortfolioProjectResource;
use App\Models\PortfolioProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PortfolioProject::query()
            ->where('published', true)
            ->with(['categories', 'technologies', 'images' => fn($q) => $q->orderBy('sort_order', 'asc')])
            ->orderBy('sort_order', 'asc');

        // Whitelisted Filters
        if ($request->has('category') && !empty($request->query('category'))) {
            $categorySlug = $request->query('category');
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($request->has('technology') && !empty($request->query('technology'))) {
            $techSlug = $request->query('technology');
            $query->whereHas('technologies', function ($q) use ($techSlug) {
                $q->where('slug', $techSlug);
            });
        }

        if ($request->has('featured')) {
            $isFeatured = filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN);
            $query->where('featured', $isFeatured);
        }

        // Pagination handling
        if ($request->has('per_page') || $request->has('page')) {
            $perPage = min(max((int) $request->query('per_page', 12), 1), 100);
            $paginator = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => PublicPortfolioProjectResource::collection($paginator->items()),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ],
            ]);
        }

        $projects = $query->get();

        return response()->json([
            'success' => true,
            'data' => PublicPortfolioProjectResource::collection($projects),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $project = PortfolioProject::query()
            ->where('slug', $slug)
            ->where('published', true)
            ->with(['categories', 'technologies', 'images' => fn($q) => $q->orderBy('sort_order', 'asc')])
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PublicPortfolioProjectResource($project),
        ]);
    }
}
