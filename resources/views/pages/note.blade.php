@extends('layouts.app', ['title' => $note['title'], 'description' => $note['excerpt'], 'type' => 'article', 'image' => isset($note['visual']['jpg']) ? asset($note['visual']['jpg']) : null])

@push('structured-data')
    @php
        $schema1 = [
            '@type' => 'BlogPosting',
            'headline' => $note['title'],
            'description' => $note['excerpt'],
            'datePublished' => $note['date'],
            'url' => url()->current(),
            'mainEntityOfPage' => url()->current(),
            'author' => ['@type' => 'Person', 'name' => config('site.author.name'), 'url' => route('about')],
            'publisher' => ['@type' => 'Organization', 'name' => config('site.legal.entity'), 'url' => url('/')],
        ];
    @endphp
    @include('partials.jsonld', ['data' => $schema1])
    @php
        $schema2 = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Notes', 'item' => route('notes.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $section['title'], 'item' => route('notes.category', $category)],
                    ['@type' => 'ListItem', 'position' => 4, 'name' => $note['title'], 'item' => url()->current()],
                ],
        ];
    @endphp
    @include('partials.jsonld', ['data' => $schema2])
@endpush

@section('content')
    <section class="article-hero">
        <a href="{{ route('notes.category', $category) }}" class="back-link">← {{ $section['title'] }}</a>
        <p class="eyebrow">{{ $section['title'] }}</p>
        <h1>{{ $note['title'] }}</h1>
        <p class="lede">{{ $note['excerpt'] }}</p>
        @include('blog.visual', ['visual' => $note['visual'], 'variant' => 'hero'])
    </section>

    <article class="article-body">
        @include("blog.posts.$slug")
    </article>

    @if ($related->isNotEmpty())
        <section class="blog-post-grid related-posts">
            <div class="related-posts-heading">
                <p class="eyebrow">Keep reading</p>
                <h2>Related posts</h2>
            </div>
            @foreach ($related as $slug => $note)
                @include('blog.card', ['heading' => 'h3'])
            @endforeach
        </section>
    @endif

    @include('partials.newsletter')
@endsection
