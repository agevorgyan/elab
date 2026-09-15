<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Testimonial\TestimonialRequest;
use App\Http\Resources\Admin\TestimonialResource;
use App\Models\AuditLog;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Testimonial::query();

        if ($request->has('published')) {
            $query->where('published', filter_var($request->input('published'), FILTER_VALIDATE_BOOLEAN));
        }

        $sortable = ['name', 'rating', 'sort_order', 'created_at'];
        $sort = in_array($request->input('sort'), $sortable, true) ? $request->input('sort') : 'sort_order';
        $direction = strtolower($request->input('direction')) === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        $perPage = min(max((int) $request->input('per_page', 20), 1), 100);
        $testimonials = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => TestimonialResource::collection($testimonials),
            'meta' => [
                'current_page' => $testimonials->currentPage(),
                'per_page' => $testimonials->perPage(),
                'total' => $testimonials->total(),
                'last_page' => $testimonials->lastPage(),
            ],
        ]);
    }

    public function store(TestimonialRequest $request): JsonResponse
    {
        $data = $request->validated();
        $testimonial = Testimonial::create([
            'id' => (string) Str::uuid(),
            'name' => $data['name'],
            'company' => $data['company'] ?? null,
            'position' => $data['position'] ?? null,
            'content' => $data['content'],
            'photo' => $data['photo'] ?? null,
            'rating' => $data['rating'] ?? 5,
            'published' => $data['published'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_TESTIMONIAL',
            'entity_type' => 'Testimonial',
            'entity_id' => $testimonial->id,
            'resource' => '/api/v1/admin/testimonials',
            'details' => "Created testimonial by: {$testimonial->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new TestimonialResource($testimonial),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $testimonial = Testimonial::find($id);

        if (!$testimonial) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TestimonialResource($testimonial),
        ]);
    }

    public function update(TestimonialRequest $request, string $id): JsonResponse
    {
        $testimonial = Testimonial::find($id);

        if (!$testimonial) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();
        $testimonial->update([
            'name' => $data['name'],
            'company' => array_key_exists('company', $data) ? $data['company'] : $testimonial->company,
            'position' => array_key_exists('position', $data) ? $data['position'] : $testimonial->position,
            'content' => $data['content'],
            'photo' => array_key_exists('photo', $data) ? $data['photo'] : $testimonial->photo,
            'rating' => $data['rating'] ?? $testimonial->rating,
            'published' => $data['published'] ?? $testimonial->published,
            'sort_order' => $data['sort_order'] ?? $testimonial->sort_order,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_TESTIMONIAL',
            'entity_type' => 'Testimonial',
            'entity_id' => $testimonial->id,
            'resource' => '/api/v1/admin/testimonials/' . $id,
            'details' => "Updated testimonial by: {$testimonial->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new TestimonialResource($testimonial),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $testimonial = Testimonial::find($id);

        if (!$testimonial) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $name = $testimonial->name;
        $testimonial->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_TESTIMONIAL',
            'entity_type' => 'Testimonial',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/testimonials/' . $id,
            'details' => "Deleted testimonial by: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully.',
        ]);
    }
}
