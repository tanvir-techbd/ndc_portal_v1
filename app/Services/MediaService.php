<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * See LARAVEL-DYNAMIZATION-PLAN.md Part 5. Validates by magic bytes (finfo),
 * not just file extension.
 */
class MediaService
{
    private const ACCEPTED_MIME = [
        'image/jpeg', 'image/png', 'image/svg+xml',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    private const MAX_SIZE_BYTES = 20 * 1024 * 1024; // 20 MB

    public function __construct(private AuditService $audit)
    {
    }

    public function store(UploadedFile $file, string $category, ?User $actor = null): MediaAsset
    {
        $mime = $file->getMimeType();

        if (! in_array($mime, self::ACCEPTED_MIME, true)) {
            throw ValidationException::withMessages(['file' => 'Unsupported file type.']);
        }

        if ($file->getSize() > self::MAX_SIZE_BYTES) {
            throw ValidationException::withMessages(['file' => 'File exceeds the 20MB limit.']);
        }

        $folder = 'media/' . now()->format('Y/m');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');

        $asset = MediaAsset::create([
            'original_filename' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'public_url' => Storage::disk('public')->url($path),
            'mime_type' => $mime,
            'size_bytes' => $file->getSize(),
            'category' => $category,
            'uploaded_by' => $actor?->id,
        ]);

        $this->audit->record($actor, 'media.upload', $asset, ['filename' => $asset->original_filename]);

        return $asset;
    }

    public function delete(MediaAsset $asset, ?User $actor = null): void
    {
        $referenced = \App\Models\Notice::where('attachment_media_id', $asset->id)->exists()
            || \App\Models\TeamMember::where('photo_media_id', $asset->id)->exists();

        if ($referenced) {
            throw ValidationException::withMessages(['file' => 'This file is still referenced by a notice or team member — remove that reference first.']);
        }

        Storage::disk('public')->delete($asset->storage_path);
        $this->audit->record($actor, 'media.delete', $asset, ['filename' => $asset->original_filename]);
        $asset->delete();
    }

    public function getPublicUrl(MediaAsset $asset): string
    {
        return $asset->resolvedUrl();
    }
}
