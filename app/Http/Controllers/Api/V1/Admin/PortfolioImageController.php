<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portfolio\ImageRequest;
use App\Http\Resources\Admin\PortfolioImageResource;
use App\Models\AuditLog;
use App\Models\PortfolioImage;
use App\Models\PortfolioProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioImageController extends Controller
{
    public function index(string $projectId): JsonResponse
    {
        $project = PortfolioProject::find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $images = $project->images()->orderBy('sort_order', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => PortfolioImageResource::collection($images),
        ]);
    }

    public function store(ImageRequest $request, string $projectId): JsonResponse
    {
        $project = PortfolioProject::find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();
        $image = PortfolioImage::create([
            'id' => (string) Str::uuid(),
            'project_id' => $project->id,
            'url' => $data['url'],
            'alt' => $data['alt'] ?? null,
            'caption' => $data['caption'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_PORTFOLIO_IMAGE',
            'entity_type' => 'PortfolioImage',
            'entity_id' => $image->id,
            'resource' => "/api/v1/admin/portfolio/{$projectId}/images",
            'details' => "Added portfolio image to project: {$project->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioImageResource($image),
        ], 201);
    }

    public function update(ImageRequest $request, string $projectId, string $imageId): JsonResponse
    {
        $project = PortfolioProject::find($projectId);
        $image = PortfolioImage::find($imageId);

        if (!$project || !$image) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Ownership IDOR check: image MUST belong to this project!
        if ($image->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Image does not belong to this project.',
            ], 403);
        }

        $data = $request->validated();
        $image->update([
            'url' => $data['url'],
            'alt' => array_key_exists('alt', $data) ? $data['alt'] : $image->alt,
            'caption' => array_key_exists('caption', $data) ? $data['caption'] : $image->caption,
            'sort_order' => array_key_exists('sort_order', $data) ? $data['sort_order'] : $image->sort_order,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_PORTFOLIO_IMAGE',
            'entity_type' => 'PortfolioImage',
            'entity_id' => $image->id,
            'resource' => "/api/v1/admin/portfolio/{$projectId}/images/{$imageId}",
            'details' => "Updated image for project: {$project->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioImageResource($image),
        ]);
    }

    public function destroy(Request $request, string $projectId, string $imageId): JsonResponse
    {
        $project = PortfolioProject::find($projectId);
        $image = PortfolioImage::find($imageId);

        if (!$project || !$image) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Ownership IDOR check
        if ($image->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Image does not belong to this project.',
            ], 403);
        }

        $image->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_PORTFOLIO_IMAGE',
            'entity_type' => 'PortfolioImage',
            'entity_id' => $imageId,
            'resource' => "/api/v1/admin/portfolio/{$projectId}/images/{$imageId}",
            'details' => "Deleted image from project: {$project->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.',
        ]);
    }
}
