<?php
/**
 * Generate PWA / push-notification icons from the site logo.
 *
 * Produces square, padded PNGs on the brand ground so the icon looks right as a
 * home-screen tile (Android masks icons to a circle or squircle, so the logo is
 * inset rather than bleeding to the edge).
 *
 * Usage: php scripts/make_pwa_icons.php
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

$source = $root . '/public/build/assets/image/logo/ohh-log.png';
if (!is_file($source)) {
    fwrite(STDERR, "Source logo not found: $source\n");
    exit(1);
}

$outDir = $root . '/public/icons';
if (!is_dir($outDir) && !mkdir($outDir, 0755, true) && !is_dir($outDir)) {
    fwrite(STDERR, "Could not create $outDir\n");
    exit(1);
}

// size => padding ratio. Maskable icons need a generous safe zone because the
// launcher may crop up to 20% from each edge.
$sizes = [
    'icon-192.png'          => [192, 0.12],
    'icon-512.png'          => [512, 0.12],
    'icon-maskable-512.png' => [512, 0.26],
    'badge-96.png'          => [96,  0.10],
];

$src = imagecreatefrompng($source);
if (!$src) {
    fwrite(STDERR, "Could not read the source logo.\n");
    exit(1);
}

$sw = imagesx($src);
$sh = imagesy($src);

foreach ($sizes as $name => [$size, $pad]) {
    $canvas = imagecreatetruecolor($size, $size);

    // White ground: the logo artwork is dark and would vanish on a dark launcher.
    $white = imagecolorallocate($canvas, 255, 255, 255);
    imagefilledrectangle($canvas, 0, 0, $size, $size, $white);

    $inner = (int) round($size * (1 - 2 * $pad));
    $scale = min($inner / $sw, $inner / $sh);
    $dw = max(1, (int) round($sw * $scale));
    $dh = max(1, (int) round($sh * $scale));
    $dx = (int) round(($size - $dw) / 2);
    $dy = (int) round(($size - $dh) / 2);

    imagecopyresampled($canvas, $src, $dx, $dy, 0, 0, $dw, $dh, $sw, $sh);

    $dest = $outDir . '/' . $name;
    if (imagepng($canvas, $dest, 9)) {
        printf("  %-24s %dx%d  %s\n", $name, $size, $size, human(filesize($dest)));
    } else {
        fwrite(STDERR, "  failed: $name\n");
    }

    imagedestroy($canvas);
}

imagedestroy($src);

echo "done -> public/icons/\n";

function human(int $bytes): string
{
    return $bytes > 1024 ? round($bytes / 1024) . ' KB' : $bytes . ' B';
}
