<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portfolio\CategoryRequest;
use App\Http\Resources\Admin\PortfolioCategoryResource;
use App\Models\AuditLog;
use App\Models\PortfolioCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = PortfolioCategory::orderBy('sort_order', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => PortfolioCategoryResource::collection($categories),
        ]);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        if (PortfolioCategory::where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $category = PortfolioCategory::create([
            'id' => (string) Str::uuid(),
            'slug' => $slug,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'active' => $data['active'] ?? true,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_PORTFOLIO_CATEGORY',
            'entity_type' => 'PortfolioCategory',
            'entity_id' => $category->id,
            'resource' => '/api/v1/admin/portfolio/categories',
            'details' => "Created portfolio category: {$category->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioCategoryResource($category),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $category = PortfolioCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PortfolioCategoryResource($category),
        ]);
    }

    public function update(CategoryRequest $request, string $id): JsonResponse
    {
        $category = PortfolioCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        if ($slug !== $category->slug && PortfolioCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $category->update([
            'slug' => $slug,
            'name' => $data['name'],
            'description' => $data['description'] ?? $category->description,
            'sort_order' => $data['sort_order'] ?? $category->sort_order,
            'active' => $data['active'] ?? $category->active,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_PORTFOLIO_CATEGORY',
            'entity_type' => 'PortfolioCategory',
            'entity_id' => $category->id,
            'resource' => '/api/v1/admin/portfolio/categories/' . $id,
            'details' => "Updated portfolio category: {$category->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioCategoryResource($category),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $category = PortfolioCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Prevent deletion if associated with projects
        if ($category->projects()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category associated with existing portfolio projects.',
            ], 409);
        }

        $name = $category->name;
        $category->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_PORTFOLIO_CATEGORY',
            'entity_type' => 'PortfolioCategory',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/portfolio/categories/' . $id,
            'details' => "Deleted portfolio category: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }
}
