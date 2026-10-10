{{--
    Search and social metadata for every public page. Pages pass any of:
      $title        page name (the site name is appended)
      $description  ~160 characters for search results
      $image        absolute or root-relative image URL for social shares
      $type         Open Graph type: website (default), book, article, profile
      $canonical    preferred URL, defaults to the current URL without query string
      $noindex      true to keep the page out of search results
--}}
@php
    $siteName = config('site.name');
    $seoTitle = isset($title) ? $title.' · '.$siteName : $siteName.' — '.config('site.tagline');
    $seoDescription = \Illuminate\Support\Str::limit(strip_tags($description ?? config('site.description')), 160, '…');
    $seoCanonical = $canonical ?? url()->current();
    $seoImage = $image ?? (config('site.seo.share_image') ? asset(config('site.seo.share_image')) : null);
    $seoImage = $seoImage && ! str_starts_with($seoImage, 'http') ? url($seoImage) : $seoImage;
    $seoType = $type ?? 'website';
@endphp
<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ $seoCanonical }}">
<meta name="robots" content="{{ ($noindex ?? false) ? 'noindex, follow' : 'index, follow, max-image-preview:large' }}">

<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ config('site.seo.locale') }}">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:title" content="{{ $title ?? $siteName }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonical }}">
@if ($seoImage)
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="{{ $imageAlt ?? $title ?? $siteName }}">
@endif

<meta name="twitter:card" content="{{ $seoImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $title ?? $siteName }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if ($seoImage)
    <meta name="twitter:image" content="{{ $seoImage }}">
@endif
@if (config('site.seo.twitter'))
    <meta name="twitter:site" content="{{ '@'.config('site.seo.twitter') }}">
@endif

<link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}">
