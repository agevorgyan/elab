<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\FaqRequest;
use App\Http\Resources\Admin\FaqResource;
use App\Models\AuditLog;
use App\Models\FAQ;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = FAQ::query();

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('published')) {
            $query->where('published', filter_var($request->input('published'), FILTER_VALIDATE_BOOLEAN));
        }

        $sortable = ['question', 'category', 'sort_order', 'created_at'];
        $sort = in_array($request->input('sort'), $sortable, true) ? $request->input('sort') : 'sort_order';
        $direction = strtolower($request->input('direction')) === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        $perPage = min(max((int) $request->input('per_page', 20), 1), 100);
        $faqs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => FaqResource::collection($faqs),
            'meta' => [
                'current_page' => $faqs->currentPage(),
                'per_page' => $faqs->perPage(),
                'total' => $faqs->total(),
                'last_page' => $faqs->lastPage(),
            ],
        ]);
    }

    public function store(FaqRequest $request): JsonResponse
    {
        $data = $request->validated();
        $faq = FAQ::create([
            'id' => (string) Str::uuid(),
            'question' => $data['question'],
            'answer' => $data['answer'],
            'category' => $data['category'] ?? 'general',
            'sort_order' => $data['sort_order'] ?? 0,
            'published' => $data['published'] ?? true,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_FAQ',
            'entity_type' => 'FAQ',
            'entity_id' => $faq->id,
            'resource' => '/api/v1/admin/faqs',
            'details' => "Created FAQ item: {$faq->question}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new FaqResource($faq),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new FaqResource($faq),
        ]);
    }

    public function update(FaqRequest $request, string $id): JsonResponse
    {
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();
        $faq->update([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'category' => $data['category'] ?? $faq->category,
            'sort_order' => $data['sort_order'] ?? $faq->sort_order,
            'published' => $data['published'] ?? $faq->published,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_FAQ',
            'entity_type' => 'FAQ',
            'entity_id' => $faq->id,
            'resource' => '/api/v1/admin/faqs/' . $id,
            'details' => "Updated FAQ item: {$faq->question}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new FaqResource($faq),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $question = $faq->question;
        $faq->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_FAQ',
            'entity_type' => 'FAQ',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/faqs/' . $id,
            'details' => "Deleted FAQ item: {$question}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FAQ item deleted successfully.',
        ]);
    }
}
