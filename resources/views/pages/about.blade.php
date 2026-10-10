@extends('layouts.app', ['title' => 'The Author', 'description' => config('site.author.short_bio'), 'type' => 'profile'])

@push('structured-data')
    @php
        $schema1 = [
            '@type' => 'ProfilePage',
            'url' => route('about'),
            'mainEntity' => [
                '@type' => 'Person',
                'name' => config('site.author.name'),
                'description' => config('site.author.short_bio'),
                'url' => route('about'),
                'worksFor' => ['@type' => 'Organization', 'name' => config('site.legal.entity')],
            ],
        ];
    @endphp
    @include('partials.jsonld', ['data' => $schema1])
@endpush

@section('content')
    <section class="page-hero author-hero">
        <p class="eyebrow">The Author</p>
        <h1>A writer of small arguments and long attention.</h1>
        <p class="lede">{{ config('site.author.name') }} writes about the invisible scaffolding of modern life, from cultural identity to creator economies, from intelligence to influence</p>
    </section>

    <section class="prose-page">
        @foreach (config('site.author.bio') as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach

        <div class="prose-cta">
            <a href="{{ route('books.index') }}" class="button button-ochre">Read the books</a>
            <a href="{{ route('contact') }}" class="text-link">Write to Patrick <span aria-hidden="true">⟶</span></a>
        </div>
    </section>
@endsection
