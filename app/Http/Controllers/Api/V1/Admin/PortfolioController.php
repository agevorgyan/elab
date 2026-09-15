<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portfolio\StoreProjectRequest;
use App\Http\Requests\Admin\Portfolio\UpdateProjectRequest;
use App\Http\Resources\Admin\PortfolioProjectResource;
use App\Models\AuditLog;
use App\Models\PortfolioImage;
use App\Models\PortfolioProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PortfolioProject::with(['categories', 'technologies', 'images']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($request->has('published')) {
            $query->where('published', filter_var($request->input('published'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('featured')) {
            $query->where('featured', filter_var($request->input('featured'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('portfolio_categories.id', $request->input('category'))
                  ->orWhere('portfolio_categories.slug', $request->input('category'));
            });
        }

        if ($request->has('technology')) {
            $query->whereHas('technologies', function ($q) use ($request) {
                $q->where('portfolio_technologies.id', $request->input('technology'))
                  ->orWhere('portfolio_technologies.slug', $request->input('technology'));
            });
        }

        $sortable = ['title', 'sort_order', 'year', 'created_at', 'updated_at'];
        $sort = in_array($request->input('sort'), $sortable, true) ? $request->input('sort') : 'sort_order';
        $direction = strtolower($request->input('direction')) === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        $perPage = min(max((int) $request->input('per_page', 20), 1), 100);
        $projects = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => PortfolioProjectResource::collection($projects),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'last_page' => $projects->lastPage(),
            ],
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        // Check for duplicate slug
        if (PortfolioProject::where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $project = DB::transaction(function () use ($data, $slug) {
            $project = PortfolioProject::create([
                'id' => (string) Str::uuid(),
                'slug' => $slug,
                'title' => $data['title'],
                'client' => $data['client'],
                'summary' => $data['summary'],
                'overview' => $data['overview'] ?? null,
                'challenge' => $data['challenge'],
                'solution' => $data['solution'],
                'services' => $data['services'] ?? [],
                'results' => $data['results'] ?? [],
                'year' => $data['year'],
                'live_url' => $data['live_url'] ?? null,
                'hero_image' => $data['hero_image'] ?? null,
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
                'featured' => $data['featured'] ?? false,
                'published' => $data['published'] ?? true,
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            if (!empty($data['categories'])) {
                $project->categories()->sync($data['categories']);
            }

            if (!empty($data['technologies'])) {
                $project->technologies()->sync($data['technologies']);
            }

            if (!empty($data['images'])) {
                foreach ($data['images'] as $idx => $img) {
                    PortfolioImage::create([
                        'id' => (string) Str::uuid(),
                        'project_id' => $project->id,
                        'url' => $img['url'],
                        'alt' => $img['alt'] ?? null,
                        'caption' => $img['caption'] ?? null,
                        'sort_order' => $img['sort_order'] ?? ($idx + 1),
                    ]);
                }
            }

            return $project->load(['categories', 'technologies', 'images']);
        });

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_PORTFOLIO_PROJECT',
            'entity_type' => 'PortfolioProject',
            'entity_id' => $project->id,
            'resource' => '/api/v1/admin/portfolio',
            'details' => "Created portfolio project: {$project->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioProjectResource($project),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $project = PortfolioProject::with(['categories', 'technologies', 'images'])->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PortfolioProjectResource($project),
        ]);
    }

    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        $project = PortfolioProject::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();

        if (isset($data['slug'])) {
            $newSlug = Str::slug($data['slug']);
            if ($newSlug !== $project->slug && PortfolioProject::where('slug', $newSlug)->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource already exists',
                ], 409);
            }
            $data['slug'] = $newSlug;
        }

        $project = DB::transaction(function () use ($project, $data) {
            $project->update(array_filter([
                'title' => $data['title'] ?? null,
                'slug' => $data['slug'] ?? null,
                'client' => $data['client'] ?? null,
                'summary' => $data['summary'] ?? null,
                'overview' => array_key_exists('overview', $data) ? $data['overview'] : $project->overview,
                'challenge' => $data['challenge'] ?? null,
                'solution' => $data['solution'] ?? null,
                'services' => $data['services'] ?? $project->services,
                'results' => $data['results'] ?? $project->results,
                'year' => $data['year'] ?? null,
                'live_url' => array_key_exists('live_url', $data) ? $data['live_url'] : $project->live_url,
                'hero_image' => array_key_exists('hero_image', $data) ? $data['hero_image'] : $project->hero_image,
                'seo_title' => array_key_exists('seo_title', $data) ? $data['seo_title'] : $project->seo_title,
                'seo_description' => array_key_exists('seo_description', $data) ? $data['seo_description'] : $project->seo_description,
                'featured' => array_key_exists('featured', $data) ? $data['featured'] : $project->featured,
                'published' => array_key_exists('published', $data) ? $data['published'] : $project->published,
                'sort_order' => array_key_exists('sort_order', $data) ? $data['sort_order'] : $project->sort_order,
            ], fn ($v) => $v !== null));

            if (isset($data['categories'])) {
                $project->categories()->sync($data['categories']);
            }

            if (isset($data['technologies'])) {
                $project->technologies()->sync($data['technologies']);
            }

            return $project->fresh(['categories', 'technologies', 'images']);
        });

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_PORTFOLIO_PROJECT',
            'entity_type' => 'PortfolioProject',
            'entity_id' => $project->id,
            'resource' => '/api/v1/admin/portfolio/' . $id,
            'details' => "Updated portfolio project: {$project->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new PortfolioProjectResource($project),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $project = PortfolioProject::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $title = $project->title;
        $project->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_PORTFOLIO_PROJECT',
            'entity_type' => 'PortfolioProject',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/portfolio/' . $id,
            'details' => "Deleted portfolio project: {$title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Portfolio project deleted successfully.',
        ]);
    }
}
