<?php

namespace App\Support;

use Illuminate\Support\Facades\URL;

/**
 * Locations and URL generation for sensitive uploads.
 *
 * Identity documents, guarantor passports/signatures and payment receipts are
 * stored on the private `local` disk and streamed by SecureDocumentController
 * behind a signed, authenticated URL. They were previously written into the
 * webroot under public/build/assets/image/... with unix-timestamp filenames,
 * which made them enumerable by anyone.
 */
final class SecureDocument
{
    /**
     * Category => directory beneath storage/app/private/documents.
     *
     * Keys are what appears in URLs, so they must stay stable.
     */
    public const CATEGORIES = [
        'idcards'    => 'idcards',
        'passports'  => 'passports',
        'signatures' => 'signatures',
        'guarantors' => 'guarantors',
        'receipts'   => 'receipts',
    ];

    /**
     * Legacy webroot directories, relative to public/. Files that have not yet
     * been migrated are still read from here so historical records keep
     * rendering; nothing new is ever written to them.
     */
    public const LEGACY_PUBLIC_DIRS = [
        'idcards'    => ['build/assets/image/idcards', 'build/assets/image/image/idcards'],
        'passports'  => ['build/assets/image/passports', 'build/assets/image/guarantors/passports', 'build/assets/image/image/passports', 'build/assets/image/image/guarantors/passports'],
        'signatures' => ['build/assets/image/signatures', 'build/assets/image/guarantors/signatures', 'build/assets/image/image/signatures', 'build/assets/image/image/guarantors/signatures'],
        'guarantors' => ['build/assets/image/guarantors'],
        'receipts'   => ['build/assets/image/receipt', 'build2/assets/image/receipt'],
    ];

    public static function isValidCategory(string $category): bool
    {
        return array_key_exists($category, self::CATEGORIES);
    }

    /**
     * Path on the private disk where new uploads in this category are written.
     */
    public static function storageDirectory(string $category): string
    {
        return 'documents/' . self::CATEGORIES[$category];
    }

    /**
     * A signed, expiring URL to stream the given file.
     *
     * Accepts either a bare filename (files moved into the webroot) or a
     * disk-relative path such as "idcards/abc.jpg" (files put on a disk with
     * ->store()); only the basename is ever used to locate the file.
     */
    /**
     * Store an upload privately and return the filename to persist.
     *
     * The basename is random and the extension is derived from the detected
     * MIME type, never from the client-supplied filename.
     */
    public static function store(\Illuminate\Http\UploadedFile $file, string $category): string
    {
        if (!self::isValidCategory($category)) {
            throw new \InvalidArgumentException("Unknown document category [$category].");
        }

        $filename = \Illuminate\Support\Str::random(40) . '.' . $file->extension();

        $file->storeAs(self::storageDirectory($category), $filename, 'local');

        return $filename;
    }

    /**
     * Absolute filesystem path to the stored file, or null if it is missing.
     *
     * For rendering contexts that cannot carry a session - notably DomPDF,
     * which fetches assets itself and would receive a 403 from the signed
     * route - embed via this path rather than url().
     */
    public static function path(string $category, ?string $storedValue): ?string
    {
        if (blank($storedValue) || !self::isValidCategory($category)) {
            return null;
        }

        $filename = basename($storedValue);
        $private = storage_path('app/private/' . self::storageDirectory($category) . '/' . $filename);

        if (is_file($private)) {
            return $private;
        }

        foreach (self::LEGACY_PUBLIC_DIRS[$category] ?? [] as $dir) {
            $legacy = public_path($dir . '/' . $filename);
            if (is_file($legacy)) {
                return $legacy;
            }
        }

        return null;
    }

    public static function url(string $category, ?string $storedValue, int $minutes = 30): ?string
    {
        if (blank($storedValue) || !self::isValidCategory($category)) {
            return null;
        }

        return URL::temporarySignedRoute('documents.show', now()->addMinutes($minutes), [
            'category' => $category,
            'filename' => basename($storedValue),
        ]);
    }
}
