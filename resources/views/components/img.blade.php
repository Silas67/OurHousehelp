@props([
    // Path relative to public/, e.g. "build/assets/image/profile_photo/foo.jpg"
    'src',
    'alt' => '',
    // Set eager for above-the-fold images (logo, hero). Everything else lazy.
    'loading' => 'lazy',
    // Shown when $src is empty or the file is missing.
    'fallback' => 'build/assets/image/logo/ohh-log.png',
])

@php
    $path = ltrim((string) $src, '/');

    // Fall back when the record has no image or the file is gone.
    if ($path === '' || !file_exists(public_path($path))) {
        $path = ltrim($fallback, '/');
    }

    // scripts/optimize_images.php writes a resized sibling <name>.webp.
    // Serve it when present and let <picture> fall back for older browsers.
    $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
    $hasWebp = $webp !== $path && file_exists(public_path($webp));
@endphp

<picture>
    @if ($hasWebp)
        <source srcset="{{ asset($webp) }}" type="image/webp">
    @endif
    <img src="{{ asset($path) }}"
         alt="{{ $alt }}"
         loading="{{ $loading }}"
         decoding="async"
         {{ $attributes }}>
</picture>
