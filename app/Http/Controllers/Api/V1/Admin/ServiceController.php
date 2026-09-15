<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\StoreServiceRequest;
use App\Http\Requests\Admin\Service\UpdateServiceRequest;
use App\Http\Resources\Admin\ServiceResource;
use App\Models\AuditLog;
use App\Models\Service;
use App\Models\ServiceFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Service::with('features');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('published')) {
            $query->where('published', filter_var($request->input('published'), FILTER_VALIDATE_BOOLEAN));
        }

        $sortable = ['title', 'sort_order', 'created_at'];
        $sort = in_array($request->input('sort'), $sortable, true) ? $request->input('sort') : 'sort_order';
        $direction = strtolower($request->input('direction')) === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        $perPage = min(max((int) $request->input('per_page', 20), 1), 100);
        $services = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => ServiceResource::collection($services),
            'meta' => [
                'current_page' => $services->currentPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
                'last_page' => $services->lastPage(),
            ],
        ]);
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        if (Service::where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $service = DB::transaction(function () use ($data, $slug) {
            $service = Service::create([
                'id' => (string) Str::uuid(),
                'slug' => $slug,
                'title' => $data['title'],
                'price_amd' => $data['price_amd'],
                'price_currency' => $data['price_currency'] ?? 'AMD',
                'show_price' => $data['show_price'] ?? true,
                'price_label' => $data['price_label'] ?? 'Starting from',
                'popular' => $data['popular'] ?? false,
                'tagline' => $data['tagline'] ?? null,
                'description' => $data['description'],
                'icon' => $data['icon'] ?? null,
                'cta_text' => $data['cta_text'] ?? 'Order Service →',
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'published' => $data['published'] ?? true,
            ]);

            if (!empty($data['features'])) {
                foreach ($data['features'] as $idx => $feat) {
                    ServiceFeature::create([
                        'id' => (string) Str::uuid(),
                        'service_id' => $service->id,
                        'text' => $feat['text'],
                        'sort_order' => $feat['sort_order'] ?? ($idx + 1),
                    ]);
                }
            }

            return $service->load('features');
        });

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_SERVICE',
            'entity_type' => 'Service',
            'entity_id' => $service->id,
            'resource' => '/api/v1/admin/services',
            'details' => "Created service: {$service->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new ServiceResource($service),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $service = Service::with('features')->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ServiceResource($service),
        ]);
    }

    public function update(UpdateServiceRequest $request, string $id): JsonResponse
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();

        if (isset($data['slug'])) {
            $newSlug = Str::slug($data['slug']);
            if ($newSlug !== $service->slug && Service::where('slug', $newSlug)->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource already exists',
                ], 409);
            }
            $data['slug'] = $newSlug;
        }

        $service = DB::transaction(function () use ($service, $data) {
            $service->update(array_filter([
                'title' => $data['title'] ?? null,
                'slug' => $data['slug'] ?? null,
                'price_amd' => $data['price_amd'] ?? null,
                'price_currency' => $data['price_currency'] ?? null,
                'show_price' => array_key_exists('show_price', $data) ? $data['show_price'] : $service->show_price,
                'price_label' => array_key_exists('price_label', $data) ? $data['price_label'] : $service->price_label,
                'popular' => array_key_exists('popular', $data) ? $data['popular'] : $service->popular,
                'tagline' => array_key_exists('tagline', $data) ? $data['tagline'] : $service->tagline,
                'description' => $data['description'] ?? null,
                'icon' => array_key_exists('icon', $data) ? $data['icon'] : $service->icon,
                'cta_text' => array_key_exists('cta_text', $data) ? $data['cta_text'] : $service->cta_text,
                'seo_title' => array_key_exists('seo_title', $data) ? $data['seo_title'] : $service->seo_title,
                'seo_description' => array_key_exists('seo_description', $data) ? $data['seo_description'] : $service->seo_description,
                'sort_order' => array_key_exists('sort_order', $data) ? $data['sort_order'] : $service->sort_order,
                'published' => array_key_exists('published', $data) ? $data['published'] : $service->published,
            ], fn ($v) => $v !== null));

            if (isset($data['features'])) {
                $service->features()->delete();
                foreach ($data['features'] as $idx => $feat) {
                    ServiceFeature::create([
                        'id' => (string) Str::uuid(),
                        'service_id' => $service->id,
                        'text' => $feat['text'],
                        'sort_order' => $feat['sort_order'] ?? ($idx + 1),
                    ]);
                }
            }

            return $service->fresh('features');
        });

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_SERVICE',
            'entity_type' => 'Service',
            'entity_id' => $service->id,
            'resource' => '/api/v1/admin/services/' . $id,
            'details' => "Updated service: {$service->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new ServiceResource($service),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $title = $service->title;
        $service->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_SERVICE',
            'entity_type' => 'Service',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/services/' . $id,
            'details' => "Deleted service: {$title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.',
        ]);
    }
}
