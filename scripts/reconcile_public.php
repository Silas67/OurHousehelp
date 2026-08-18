<?php
/**
 * Reconcile the dead <base>/public tree into the real web root <base>/public_html.
 *
 * This host serves from public_html, but Laravel's public_path() used to resolve
 * to <base>/public, so uploads made before that was corrected were written to a
 * directory the web server never serves. Those files 404 - profile photos, ID
 * cards, receipts, guarantor documents.
 *
 * Also flattens the accidental build/assets/assets/ nesting created by an
 * upload that landed one level too deep.
 *
 * NON-DESTRUCTIVE BY DEFAULT:
 *   php scripts/reconcile_public.php              # report only
 *   php scripts/reconcile_public.php --move       # move files that are missing in public_html
 *   php scripts/reconcile_public.php --move --overwrite   # also replace existing ones
 *
 * A file present in BOTH trees is never overwritten unless --overwrite is given.
 */

$base = is_file(dirname(__DIR__) . '/artisan') ? dirname(__DIR__) : getcwd();

if (!is_file($base . '/artisan')) {
    fwrite(STDERR, "Could not locate the Laravel project root.\n");
    exit(1);
}

/*
 * Work out where the web server actually serves from. Two layouts:
 *   - application beside the document root  -> <base>/public_html
 *   - application inside the document root  -> <base> itself
 * The dead tree is <base>/public either way.
 */
$source = $base . '/public';

if (is_dir($base . '/public_html')) {
    $target = $base . '/public_html';
} elseif (is_file($base . '/index.php') && is_dir($base . '/build')) {
    $target = $base;
} else {
    fwrite(STDERR, "Could not identify the document root.
");
    fwrite(STDERR, "Expected either <base>/public_html or index.php + build/ at <base>.
");
    exit(1);
}

if (realpath($source) === realpath($target)) {
    echo "Nothing to do - source and target are the same directory.
";
    exit(0);
}

echo "base:   $base\n";
echo "source: $source\n";
echo "target: $target\n\n";

if (!is_dir($source)) {
    echo "Nothing to do - $source does not exist.\n";
    exit(0);
}

if (!is_dir($target)) {
    fwrite(STDERR, "Target web root not found: $target\n");
    fwrite(STDERR, "Run this on the server, not locally.\n");
    exit(1);
}

$move      = in_array('--move', $argv, true);
$overwrite = in_array('--overwrite', $argv, true);

$moved = $skipped = $collision = $failed = 0;
$bytes = 0;
$collisions = [];

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($it as $item) {
    if ($item->isDir()) {
        continue;
    }

    // chr(92) avoids embedding a literal backslash in this source.
    $sep = chr(92);
    $rel = str_replace($source, '', $item->getPathname());
    $rel = ltrim(strtr($rel, [$sep => '/']), '/');

    // Flatten build/assets/assets/... -> build/assets/...
    $relTarget = preg_replace('#^build/assets/assets/#', 'build/assets/', $rel);

    $dest = $target . '/' . $relTarget;

    if (is_file($dest) && !$overwrite) {
        $collision++;
        if (count($collisions) < 8) {
            $collisions[] = $relTarget;
        }
        continue;
    }

    $bytes += $item->getSize();

    if (!$move) {
        $moved++;
        if ($moved <= 15) {
            printf("  [report] %s\n", $relTarget);
        }
        continue;
    }

    $destDir = dirname($dest);
    if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
        $failed++;
        continue;
    }

    if (@rename($item->getPathname(), $dest)) {
        $moved++;
    } elseif (@copy($item->getPathname(), $dest)) {
        @unlink($item->getPathname());
        $moved++;
    } else {
        $failed++;
        fwrite(STDERR, "  failed: $relTarget\n");
    }
}

if (!$move && $moved > 15) {
    printf("  ... and %d more\n", $moved - 15);
}

printf(
    "\n%s%d file(s), %.1f MB\n",
    $move ? 'moved: ' : 'would move: ',
    $moved,
    $bytes / 1048576
);

if ($collision) {
    printf("already present in public_html (left alone): %d\n", $collision);
    foreach ($collisions as $c) {
        echo "    $c\n";
    }
    if ($collision > count($collisions)) {
        printf("    ... and %d more\n", $collision - count($collisions));
    }
    echo "  re-run with --overwrite if the public/ copies are the newer ones\n";
}

if ($failed) {
    printf("failed: %d\n", $failed);
}

if (!$move) {
    echo "\nReport only. Re-run with --move to actually move them.\n";
}
