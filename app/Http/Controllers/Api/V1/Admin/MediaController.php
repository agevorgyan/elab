<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Media\UploadMediaRequest;
use App\Http\Resources\Admin\MediaResource;
use App\Models\AuditLog;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Media::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('alt', 'like', "%{$search}%");
            });
        }

        if ($request->has('mime_type')) {
            $query->where('mime_type', 'like', "%" . $request->input('mime_type') . "%");
        }

        $sortable = ['name', 'size_bytes', 'created_at'];
        $sort = in_array($request->input('sort'), $sortable, true) ? $request->input('sort') : 'created_at';
        $direction = strtolower($request->input('direction')) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sort, $direction);

        $perPage = min(max((int) $request->input('per_page', 20), 1), 100);
        $media = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => MediaResource::collection($media),
            'meta' => [
                'current_page' => $media->currentPage(),
                'per_page' => $media->perPage(),
                'total' => $media->total(),
                'last_page' => $media->lastPage(),
            ],
        ]);
    }

    public function store(UploadMediaRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        // Reject executable / script uploads
        $forbiddenExtensions = ['php', 'phtml', 'phar', 'exe', 'sh', 'bat', 'cmd', 'js', 'py', 'pl', 'cgi'];
        if (in_array($extension, $forbiddenExtensions, true) || str_contains($mimeType, 'executable') || str_contains($mimeType, 'php')) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden file type.',
            ], 422);
        }

        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
        $path = $file->storeAs('uploads', $filename, 'public');
        $url = Storage::url($path);

        $width = null;
        $height = null;

        if (str_starts_with($mimeType, 'image/') && $extension !== 'svg') {
            [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];
        }

        $media = Media::create([
            'id' => (string) Str::uuid(),
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'url' => $url,
            'mime_type' => $mimeType,
            'size_bytes' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'alt' => $request->input('alt'),
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPLOAD_MEDIA',
            'entity_type' => 'Media',
            'entity_id' => $media->id,
            'resource' => '/api/v1/admin/media',
            'details' => "Uploaded media: {$media->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new MediaResource($media),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new MediaResource($media),
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->has('name')) {
            $media->name = $request->input('name');
        }
        if ($request->has('alt')) {
            $media->alt = $request->input('alt');
        }

        $media->save();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_MEDIA',
            'entity_type' => 'Media',
            'entity_id' => $media->id,
            'resource' => '/api/v1/admin/media/' . $id,
            'details' => "Updated media metadata: {$media->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new MediaResource($media),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        Storage::disk('public')->delete($media->path);
        $name = $media->name;
        $media->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'DELETE_MEDIA',
            'entity_type' => 'Media',
            'entity_id' => $id,
            'resource' => '/api/v1/admin/media/' . $id,
            'details' => "Deleted media: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media file deleted successfully.',
        ]);
    }
}
