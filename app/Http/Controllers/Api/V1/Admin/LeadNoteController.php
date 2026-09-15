<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lead\StoreLeadNoteRequest;
use App\Http\Resources\Admin\LeadNoteResource;
use App\Models\AuditLog;
use App\Models\Lead;
use App\Models\LeadNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadNoteController extends Controller
{
    public function index(string $leadId): JsonResponse
    {
        $lead = Lead::find($leadId);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $notes = $lead->notes()->with('author')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => LeadNoteResource::collection($notes),
        ]);
    }

    public function store(StoreLeadNoteRequest $request, string $leadId): JsonResponse
    {
        $lead = Lead::find($leadId);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $note = LeadNote::create([
            'id' => (string) Str::uuid(),
            'lead_id' => $lead->id,
            'author_id' => $request->user()->id,
            'text' => $request->input('text'),
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'CREATE_LEAD_NOTE',
            'entity_type' => 'LeadNote',
            'entity_id' => $note->id,
            'resource' => "/api/v1/admin/leads/{$leadId}/notes",
            'details' => "Added note to lead: {$lead->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new LeadNoteResource($note->load('author')),
        ], 201);
    }

    public function update(StoreLeadNoteRequest $request, string $leadId, string $noteId): JsonResponse
    {
        $lead = Lead::find($leadId);
        $note = LeadNote::find($noteId);

        if (!$lead || !$note) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Ownership IDOR check: note MUST belong to this lead!
        if ($note->lead_id !== $lead->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Note does not belong to this lead.',
            ], 403);
        }

        $note->update([
            'text' => $request->input('text'),
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_LEAD_NOTE',
            'entity_type' => 'LeadNote',
            'entity_id' => $note->id,
            'resource' => "/api/v1/admin/leads/{$leadId}/notes/{$noteId}",
            'details' => "Updated note for lead: {$lead->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new LeadNoteResource($note->load('author')),
        ]);
    }

    public function destroy(Request $request, string $leadId, string $noteId): JsonResponse
    {
        $lead = Lead::find($leadId);
        $note = LeadNote::find($noteId);

        if (!$lead || !$note) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Ownership IDOR check
        if ($note->lead_id !== $lead->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Note does not belong to this lead.',
            ], 403);
        }

        $note->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_LEAD_NOTE',
            'entity_type' => 'LeadNote',
            'entity_id' => $noteId,
            'resource' => "/api/v1/admin/leads/{$leadId}/notes/{$noteId}",
            'details' => "Deleted note from lead: {$lead->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead note deleted successfully.',
        ]);
    }
}
