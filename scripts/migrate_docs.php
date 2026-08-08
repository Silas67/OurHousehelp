<?php
/**
 * Move sensitive uploads out of the webroot onto the private disk.
 *
 * Run from the project root:  php migrate_docs.php [--dry-run]
 *
 * Files are MOVED (not copied) so they stop being web-reachable. Filenames are
 * preserved, because the database stores them and SecureDocument::url() looks
 * files up by basename.
 */

$root = __DIR__;
if (!is_file($root . '/artisan')) {
    fwrite(STDERR, "Run this from the Laravel project root.\n");
    exit(1);
}

$dryRun = in_array('--dry-run', $argv, true);

// category => [source dirs relative to public/]
$map = [
    'idcards'    => ['build/assets/image/idcards', 'build/assets/image/image/idcards', 'build2/assets/image/idcards'],
    'passports'  => ['build/assets/image/passports', 'build/assets/image/guarantors/passports', 'build/assets/image/image/passports', 'build/assets/image/image/guarantors/passports'],
    'signatures' => ['build/assets/image/signatures', 'build/assets/image/guarantors/signatures', 'build/assets/image/image/signatures', 'build/assets/image/image/guarantors/signatures'],
    'guarantors' => ['build/assets/image/guarantors'],
    'receipts'   => ['build/assets/image/receipt', 'build2/assets/image/receipt'],
];

/**
 * Same idea for the `public` disk (storage/app/public). Nothing there is
 * web-reachable today only because public/storage does not exist - running
 * `artisan storage:link` would expose every one of these. Profile photos are
 * deliberately left alone; they are meant to be displayed.
 */
$publicDiskMap = [
    'idcards'    => ['id_cards'],
    'passports'  => ['passports', 'guarantors/passports'],
    'signatures' => ['signatures', 'guarantors/signatures'],
    'guarantors' => ['guarantors'],
    'receipts'   => ['bank_receipts', 'receipts', 'booking-receipts', 'booking-receipts/whatsapp'],
];

$targetBase = $root . '/storage/app/private/documents';

$moved = $skipped = $collisions = 0;

foreach ($map as $category => $dirs) {
    $target = $targetBase . '/' . $category;

    if (!$dryRun && !is_dir($target) && !mkdir($target, 0755, true) && !is_dir($target)) {
        fwrite(STDERR, "Could not create $target\n");
        exit(1);
    }

    foreach ($dirs as $dir) {
        $source = $root . '/public/' . $dir;
        if (!is_dir($source)) {
            continue;
        }

        foreach (scandir($source) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $from = $source . '/' . $entry;
            if (!is_file($from)) {
                continue;
            }

            $to = $target . '/' . $entry;

            if (file_exists($to)) {
                // Same basename in two source dirs. Keep both: the DB only
                // stores the basename, so preserve whichever is larger and
                // park the other alongside for manual review.
                if (filesize($to) === filesize($from)) {
                    $dryRun || unlink($from);
                    $skipped++;
                    continue;
                }
                $to = $target . '/dup_' . substr(md5($dir), 0, 6) . '_' . $entry;
                $collisions++;
            }

            if ($dryRun) {
                $moved++;
                continue;
            }

            if (!rename($from, $to)) {
                fwrite(STDERR, "FAILED to move $from\n");
                continue;
            }
            $moved++;
        }
    }
}

foreach ($publicDiskMap as $category => $dirs) {
    $target = $targetBase . '/' . $category;

    if (!$dryRun && !is_dir($target) && !mkdir($target, 0755, true) && !is_dir($target)) {
        fwrite(STDERR, "Could not create $target\n");
        exit(1);
    }

    foreach ($dirs as $dir) {
        $source = $root . '/storage/app/public/' . $dir;
        if (!is_dir($source)) {
            continue;
        }

        foreach (scandir($source) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $from = $source . '/' . $entry;
            if (!is_file($from)) {
                continue;
            }

            $to = $target . '/' . $entry;

            if (file_exists($to)) {
                if (filesize($to) === filesize($from)) {
                    $dryRun || unlink($from);
                    $skipped++;
                    continue;
                }
                $to = $target . '/dup_' . substr(md5($dir), 0, 6) . '_' . $entry;
                $collisions++;
            }

            if ($dryRun) {
                $moved++;
                continue;
            }

            if (!rename($from, $to)) {
                fwrite(STDERR, "FAILED to move $from\n");
                continue;
            }
            $moved++;
        }
    }
}

printf(
    "%s moved=%d identical-duplicates-removed=%d name-collisions-renamed=%d\n",
    $dryRun ? '[DRY RUN]' : '[DONE]',
    $moved,
    $skipped,
    $collisions
);
