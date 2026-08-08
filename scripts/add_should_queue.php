<?php
/**
 * Make every notification and mailable queueable.
 *
 * They already `use Queueable` but never implemented ShouldQueue, so each one
 * was delivered synchronously inside the web request.
 */

$root = dirname(__DIR__);

$targets = [
    [$root . '/app/Notifications/*.php', 'Notification'],
    [$root . '/app/Mail/*.php', 'Mailable'],
];

$changed = 0;
$skipped = [];

foreach ($targets as [$glob, $base]) {
    foreach (glob($glob) as $file) {
        $contents = $original = file_get_contents($file);

        if (str_contains($contents, 'implements ShouldQueue')) {
            $skipped[] = basename($file) . ' (already queued)';
            continue;
        }

        // class Foo extends Notification  ->  class Foo extends Notification implements ShouldQueue
        $contents = preg_replace(
            '/^(class\s+\w+\s+extends\s+' . $base . ')[ \t]*\r?$/m',
            '$1 implements ShouldQueue',
            $contents,
            1,
            $count
        );

        if (!$count) {
            $skipped[] = basename($file) . ' (class declaration not matched)';
            continue;
        }

        // Add the import after the last existing use statement.
        if (!str_contains($contents, 'use Illuminate\Contracts\Queue\ShouldQueue;')) {
            // \r? so this also matches files with CRLF line endings.
            if (preg_match_all('/^use [^;]+;\r?$/m', $contents, $m, PREG_OFFSET_CAPTURE)) {
                $last = end($m[0]);
                $insertAt = $last[1] + strlen($last[0]);
                $contents = substr($contents, 0, $insertAt)
                    . "\nuse Illuminate\\Contracts\\Queue\\ShouldQueue;"
                    . substr($contents, $insertAt);
            } else {
                $skipped[] = basename($file) . ' (no use block to extend)';
                continue;
            }
        }

        if ($contents !== $original) {
            file_put_contents($file, $contents);
            $changed++;
        }
    }
}

echo "queued: $changed\n";
if ($skipped) {
    echo "skipped:\n  " . implode("\n  ", $skipped) . "\n";
}
