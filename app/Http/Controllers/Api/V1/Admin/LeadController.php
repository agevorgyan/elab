<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lead\UpdateLeadRequest;
use App\Http\Resources\Admin\LeadResource;
use App\Models\AuditLog;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Lead::with('notes.author');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $sortable = ['name', 'status', 'created_at', 'updated_at'];
        $sort = in_array($request->input('sort'), $sortable, true) ? $request->input('sort') : 'created_at';
        $direction = strtolower($request->input('direction')) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sort, $direction);

        $perPage = min(max((int) $request->input('per_page', 20), 1), 100);
        $leads = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => LeadResource::collection($leads),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
                'last_page' => $leads->lastPage(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $lead = Lead::with('notes.author')->find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new LeadResource($lead),
        ]);
    }

    public function update(UpdateLeadRequest $request, string $id): JsonResponse
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $data = $request->validated();
        $lead->update($data);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_LEAD',
            'entity_type' => 'Lead',
            'entity_id' => $lead->id,
            'resource' => '/api/v1/admin/leads/' . $id,
            'details' => "Updated lead status/details for: {$lead->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new LeadResource($lead->fresh('notes.author')),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $name = $lead->name;
        $lead->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_LEAD',
            'entity_type' => 'Lead',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/leads/' . $id,
            'details' => "Deleted lead: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully.',
        ]);
    }
}
