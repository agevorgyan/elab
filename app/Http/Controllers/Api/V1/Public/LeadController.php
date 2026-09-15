<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $data = $request->validated();

        // 1. Honeypot check: If any honeypot field is filled, silently ignore bot submission
        if (
            !empty($data['honeypot']) ||
            !empty($data['website_url']) ||
            !empty($data['hp_field'])
        ) {
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Your message has been received.',
                ],
            ], 201);
        }

        // 2. Duplicate submission prevention (Within 60 seconds)
        $duplicateWindowTime = now()->subSeconds(60);
        $phone = trim($data['phone']);
        $email = !empty($data['email']) ? strtolower(trim($data['email'])) : null;

        $existingRecentLead = Lead::query()
            ->where('created_at', '>=', $duplicateWindowTime)
            ->where(function ($query) use ($phone, $email) {
                $query->where('phone', $phone);
                if ($email) {
                    $query->orWhere('email', $email);
                }
            })
            ->first();

        if ($existingRecentLead) {
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Your message has been received.',
                ],
            ], 201);
        }

        // 3. Database transaction for lead creation
        DB::transaction(function () use ($data, $phone, $email) {
            Lead::create([
                'name' => strip_tags(trim($data['name'])),
                'phone' => strip_tags($phone),
                'email' => $email ?? 'no-email@elab.am',
                'company' => !empty($data['company']) ? strip_tags(trim($data['company'])) : null,
                'project_type' => $data['project_type'] ?? $data['service'] ?? 'corporate-website',
                'budget' => $data['budget'] ?? 'Unspecified',
                'message' => !empty($data['message']) ? strip_tags(trim($data['message'])) : 'Contact inquiry',
                'source' => $data['source'] ?? 'Website Form',
                'status' => 'NEW',
            ]);
        });

        // 4. Safe generic success response without exposing Lead ID or admin metadata
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Your message has been received.',
            ],
        ], 201);
    }
}
