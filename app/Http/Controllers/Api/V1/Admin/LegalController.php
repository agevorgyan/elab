<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Legal\LegalPageRequest;
use App\Http\Resources\Admin\LegalPageResource;
use App\Models\AuditLog;
use App\Models\LegalPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalController extends Controller
{
    public function index(): JsonResponse
    {
        $pages = LegalPage::orderBy('title', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => LegalPageResource::collection($pages),
        ]);
    }

    public function store(LegalPageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        if (LegalPage::where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $page = LegalPage::create([
            'id' => (string) Str::uuid(),
            'slug' => $slug,
            'title' => $data['title'],
            'content' => $data['content'],
            'last_updated' => $data['last_updated'],
            'published' => $data['published'] ?? true,
            'version' => $data['version'] ?? 1,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_LEGAL_PAGE',
            'entity_type' => 'LegalPage',
            'entity_id' => $page->id,
            'resource' => '/api/v1/admin/legal',
            'details' => "Created legal page: {$page->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new LegalPageResource($page),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $page = LegalPage::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new LegalPageResource($page),
        ]);
    }

    public function update(LegalPageRequest $request, string $id): JsonResponse
    {
        $page = LegalPage::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();
        $slug = Str::slug($data['slug']);

        if ($slug !== $page->slug && LegalPage::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource already exists',
            ], 409);
        }

        $page->update([
            'slug' => $slug,
            'title' => $data['title'],
            'content' => $data['content'],
            'last_updated' => $data['last_updated'],
            'published' => $data['published'] ?? $page->published,
            'version' => $data['version'] ?? ($page->version + 1),
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_LEGAL_PAGE',
            'entity_type' => 'LegalPage',
            'entity_id' => $page->id,
            'resource' => '/api/v1/admin/legal/' . $id,
            'details' => "Updated legal page: {$page->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new LegalPageResource($page),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $page = LegalPage::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $title = $page->title;
        $page->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_LEGAL_PAGE',
            'entity_type' => 'LegalPage',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/legal/' . $id,
            'details' => "Deleted legal page: {$title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Legal page deleted successfully.',
        ]);
    }
}
