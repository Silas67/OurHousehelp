<?php

namespace App\Http\Controllers;

use App\Support\SecureDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Streams sensitive uploads (identity documents, guarantor passports and
 * signatures, payment receipts) to authorised users.
 *
 * Access requires an authenticated session AND a valid signature, so a URL
 * cannot be shared onward indefinitely or guessed by iterating filenames.
 */
class SecureDocumentController extends Controller
{
    public function show(Request $request, string $category, string $filename): Response
    {
        if (!SecureDocument::isValidCategory($category)) {
            abort(404);
        }

        // Signed URLs carry only a basename, but never trust the segment:
        // strip any path the caller managed to smuggle in before touching disk.
        $filename = basename($filename);

        if ($filename === '' || str_starts_with($filename, '.')) {
            abort(404);
        }

        $disk = Storage::disk('local');
        $path = SecureDocument::storageDirectory($category) . '/' . $filename;

        if ($disk->exists($path)) {
            return $disk->response($path, $filename, $this->safeHeaders($disk->mimeType($path)));
        }

        // Fall back to the legacy webroot copies for records uploaded before
        // these files were moved onto the private disk.
        foreach (SecureDocument::LEGACY_PUBLIC_DIRS[$category] ?? [] as $dir) {
            $legacy = public_path($dir . '/' . $filename);

            if (is_file($legacy)) {
                return response()->file($legacy, $this->safeHeaders(mime_content_type($legacy) ?: 'application/octet-stream'));
            }
        }

        Log::info('Secure document not found', [
            'category' => $category,
            'filename' => $filename,
            'user_id' => $request->user()?->id,
        ]);

        abort(404);
    }

    /**
     * Serve inline but never let the browser re-interpret the content type -
     * these are user-supplied files.
     */
    private function safeHeaders(string $mime): array
    {
        return [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=0, no-store',
        ];
    }
}
