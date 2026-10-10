@extends('layouts.app', ['title' => $section['title'], 'description' => $section['intro'], 'image' => asset($section['image']['jpg'])])

@push('structured-data')
    @include('partials.jsonld', ['data' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Notes', 'item' => route('notes.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $section['title'], 'item' => url()->current()],
        ],
    ]])
@endpush

@section('content')
    <section class="blog-category-hero">
        <div>
            <a href="{{ route('notes.index') }}" class="back-link">← All notes</a>
            <p class="eyebrow">Notes / {{ $section['title'] }}</p>
            <h1>{{ $section['title'] }}</h1>
            <p class="lede">{{ $section['intro'] }}</p>
        </div>
        <picture>
            <source srcset="{{ asset($section['image']['webp']) }}" type="image/webp">
            <img src="{{ asset($section['image']['jpg']) }}" alt="{{ $section['image']['alt'] }}" loading="eager" fetchpriority="high" decoding="async">
        </picture>
    </section>

    <section class="blog-post-grid">
        @foreach ($section['posts'] as $slug => $note)
            @include('blog.card')
        @endforeach
    </section>

    @include('partials.newsletter')
@endsection
