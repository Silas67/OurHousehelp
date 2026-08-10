<?php
/**
 * Generate resized WebP derivatives for web-served images.
 *
 * NON-DESTRUCTIVE: originals are never modified, moved or deleted. Each source
 * file gets a sibling `<name>.webp`. If the derivative already exists and is
 * newer than the source it is skipped, so re-runs are cheap.
 *
 * Why: profile photos average 415 KB and include straight-from-the-phone
 * uploads at 1944x2592 and 1440x3200, all displayed as small avatars and
 * cards. On metered Nigerian mobile data that is the single most expensive
 * thing the app ships.
 *
 * Usage:
 *   php scripts/optimize_images.php --dry-run
 *   php scripts/optimize_images.php
 */

$root = is_file(dirname(__DIR__) . '/artisan') ? dirname(__DIR__) : getcwd();
if (!is_file($root . '/artisan')) {
    fwrite(STDERR, "Could not locate the Laravel project root.\n");
    exit(1);
}

if (!extension_loaded('gd')) {
    fwrite(STDERR, "The gd extension is required.\n");
    exit(1);
}
if (!function_exists('imagewebp')) {
    fwrite(STDERR, "This gd build has no WebP support.\n");
    exit(1);
}

$dryRun = in_array('--dry-run', $argv, true);

// directory => max edge in pixels
$targets = [
    'public/build/assets/image/profile_photo' => 800,
    // Gallery images render as grid cards, never full-bleed - the 1280px
    // sources were roughly double what the layout ever displays.
    'public/build/assets/image/gallery'       => 800,
    'public/build/assets/image/image'         => 1200,
    'public/build/assets/image/logo'          => 800,
];

const QUALITY = 82;

$made = 0;
$skipped = 0;
$failed = 0;
$srcBytes = 0;
$outBytes = 0;

foreach ($targets as $rel => $maxEdge) {
    $dir = $root . '/' . $rel;
    if (!is_dir($dir)) {
        echo "skip (missing): $rel\n";
        continue;
    }

    foreach (glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE) ?: [] as $src) {
        $dest = preg_replace('/\.(jpe?g|png)$/i', '', $src) . '.webp';

        if (is_file($dest) && filemtime($dest) >= filemtime($src)) {
            $skipped++;
            continue;
        }

        $info = @getimagesize($src);
        if (!$info) {
            $failed++;
            continue;
        }

        [$w, $h] = $info;
        $scale = min(1, $maxEdge / max($w, $h));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $srcBytes += filesize($src);

        if ($dryRun) {
            printf("  [dry] %-52s %dx%d -> %dx%d\n", basename($src), $w, $h, $nw, $nh);
            $made++;
            continue;
        }

        $im = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($src),
            IMAGETYPE_PNG  => @imagecreatefrompng($src),
            default        => null,
        };

        if (!$im) {
            $failed++;
            continue;
        }

        $out = imagecreatetruecolor($nw, $nh);

        // Preserve transparency for PNG sources.
        if ($info[2] === IMAGETYPE_PNG) {
            imagealphablending($out, false);
            imagesavealpha($out, true);
            imagefill($out, 0, 0, imagecolorallocatealpha($out, 0, 0, 0, 127));
        }

        imagecopyresampled($out, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);

        if (@imagewebp($out, $dest, QUALITY)) {
            $made++;
            $outBytes += filesize($dest);
        } else {
            $failed++;
        }

        imagedestroy($im);
        imagedestroy($out);
    }
}

printf(
    "\n%screated=%d skipped=%d failed=%d\n",
    $dryRun ? '[DRY RUN] ' : '',
    $made,
    $skipped,
    $failed
);

if (!$dryRun && $srcBytes > 0) {
    printf(
        "source %.1f MB -> webp %.1f MB (%.0f%% smaller)\n",
        $srcBytes / 1048576,
        $outBytes / 1048576,
        (1 - $outBytes / $srcBytes) * 100
    );
}
