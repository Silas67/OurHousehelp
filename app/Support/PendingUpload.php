<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Keeps a file upload alive across a failed validation round-trip.
 *
 * A browser cannot repopulate <input type="file">, so when a long form fails
 * validation the user has to find and re-select their photo every time - on the
 * staff application that meant re-uploading at step 1 because of a typo at
 * step 6.
 *
 * The file is parked on the private disk and remembered in the session, so the
 * redisplayed form can show a preview and the submit can go through without a
 * fresh upload.
 */
class PendingUpload
{
    private const DIR = 'pending-uploads';

    /**
     * Park a newly uploaded file, or return the one parked earlier.
     *
     * Called BEFORE validation so the file survives a validation failure.
     * Returns the stored filename, or null when there is nothing to work with.
     */
    public static function keep(Request $request, string $field, string $sessionKey): ?string
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);

            // Only park something that looks usable. A file failing these
            // checks is left for the validator to report on.
            if ($file->isValid()
                && str_starts_with((string) $file->getMimeType(), 'image/')
                && $file->getSize() <= 2 * 1024 * 1024) {

                self::forget($sessionKey);

                $name = Str::random(40) . '.' . $file->extension();
                $file->storeAs(self::DIR, $name, 'local');
                session([$sessionKey => $name]);

                return $name;
            }

            return null;
        }

        return self::stored($sessionKey);
    }

    /**
     * The parked filename, if it is still on disk.
     */
    public static function stored(string $sessionKey): ?string
    {
        $name = session($sessionKey);

        if (!is_string($name) || $name === '') {
            return null;
        }

        // basename() guards against a tampered session value escaping the
        // directory.
        $name = basename($name);

        return Storage::disk('local')->exists(self::DIR . '/' . $name) ? $name : null;
    }

    /**
     * Absolute path of the parked file, for moving it into place.
     */
    public static function path(string $sessionKey): ?string
    {
        $name = self::stored($sessionKey);

        return $name ? Storage::disk('local')->path(self::DIR . '/' . $name) : null;
    }

    /**
     * Move the parked file to its final home and clear the session.
     *
     * Returns the destination filename, or null if nothing was parked.
     */
    public static function commit(string $sessionKey, string $destinationDir, string $filename): ?string
    {
        $source = self::path($sessionKey);

        if (!$source) {
            return null;
        }

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (!@rename($source, rtrim($destinationDir, '/\\') . DIRECTORY_SEPARATOR . $filename)) {
            // rename() fails across filesystems; fall back to copy + unlink.
            if (!@copy($source, rtrim($destinationDir, '/\\') . DIRECTORY_SEPARATOR . $filename)) {
                return null;
            }
            @unlink($source);
        }

        self::forget($sessionKey);

        return $filename;
    }

    /**
     * Move the parked file onto a Laravel disk directory (used for the private
     * document store) and clear the session.
     */
    public static function commitToDisk(string $sessionKey, string $diskDirectory, string $filename, string $disk = 'local'): ?string
    {
        $source = self::path($sessionKey);

        if (!$source) {
            return null;
        }

        Storage::disk($disk)->putFileAs($diskDirectory, new \Illuminate\Http\File($source), $filename);

        self::forget($sessionKey);

        return $filename;
    }

    public static function forget(string $sessionKey): void
    {
        $name = session($sessionKey);

        if (is_string($name) && $name !== '') {
            Storage::disk('local')->delete(self::DIR . '/' . basename($name));
        }

        session()->forget($sessionKey);
    }
}
