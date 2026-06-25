{{-- Preload + load Bootstrap Icons so glyphs are ready before first paint (no flash on navigation) --}}
<link rel="preload" href="/vendor/fonts/bootstrap-icons.woff2?e34853135f9e39acf64315236852cd5a" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/vendor/bootstrap-icons.css">

{{-- Tab/favicon — green by default, registrar overrides with the blue variant --}}
<link rel="icon" type="image/svg+xml" href="{{ $favicon ?? '/images/favicon-green.svg' }}">
<link rel="alternate icon" href="/images/logo.png">
