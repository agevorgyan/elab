<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portfolio\TechnologyRequest;
use App\Http\Resources\Admin\PortfolioTechnologyResource;
use App\Models\AuditLog;
use App\Models\PortfolioTechnology;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioTechnologyController extends Controller
{
    public function index(): JsonResponse
    {
        $technologies = PortfolioTechnology::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => PortfolioTechnologyResource::collection($technologies),
        ]);
    }

    public function store(TechnologyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        if (PortfolioTechnology::where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $tech = PortfolioTechnology::create([
            'id' => (string) Str::uuid(),
            'slug' => $slug,
            'name' => $data['name'],
            'icon' => $data['icon'] ?? null,
            'url' => $data['url'] ?? null,
            'active' => $data['active'] ?? true,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_PORTFOLIO_TECHNOLOGY',
            'entity_type' => 'PortfolioTechnology',
            'entity_id' => $tech->id,
            'resource' => '/api/v1/admin/portfolio/technologies',
            'details' => "Created portfolio technology: {$tech->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioTechnologyResource($tech),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $tech = PortfolioTechnology::find($id);

        if (!$tech) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PortfolioTechnologyResource($tech),
        ]);
    }

    public function update(TechnologyRequest $request, string $id): JsonResponse
    {
        $tech = PortfolioTechnology::find($id);

        if (!$tech) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        if ($slug !== $tech->slug && PortfolioTechnology::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $tech->update([
            'slug' => $slug,
            'name' => $data['name'],
            'icon' => array_key_exists('icon', $data) ? $data['icon'] : $tech->icon,
            'url' => array_key_exists('url', $data) ? $data['url'] : $tech->url,
            'active' => $data['active'] ?? $tech->active,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_PORTFOLIO_TECHNOLOGY',
            'entity_type' => 'PortfolioTechnology',
            'entity_id' => $tech->id,
            'resource' => '/api/v1/admin/portfolio/technologies/' . $id,
            'details' => "Updated portfolio technology: {$tech->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioTechnologyResource($tech),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $tech = PortfolioTechnology::find($id);

        if (!$tech) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        if ($tech->projects()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete technology associated with existing portfolio projects.',
            ], 409);
        }

        $name = $tech->name;
        $tech->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_PORTFOLIO_TECHNOLOGY',
            'entity_type' => 'PortfolioTechnology',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/portfolio/technologies/' . $id,
            'details' => "Deleted portfolio technology: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Technology deleted successfully.',
        ]);
    }
}
