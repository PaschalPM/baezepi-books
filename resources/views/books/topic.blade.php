@extends('layouts.app', ['title' => 'Books about '.$topic['title'], 'description' => $topic['blurb']])

@php
    $lead = $books->first();
@endphp

@push('structured-data')
    @php
        $schema1 = [
            '@type' => 'CollectionPage',
            'name' => 'Books about '.strtolower($topic['title']),
            'description' => $topic['blurb'],
            'url' => url()->current(),
            'hasPart' => $books->map(fn ($book) => ['@type' => 'Book', 'name' => $book->title, 'url' => route('books.show', $book)])->values()->all(),
        ];
    @endphp
    @include('partials.jsonld', ['data' => $schema1])
    @php
        $schema2 = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'The Books', 'item' => route('books.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => 'Books about '.strtolower($topic['title']), 'item' => url()->current()],
                ],
        ];
    @endphp
    @include('partials.jsonld', ['data' => $schema2])
@endpush

@section('content')
    <section class="influence-hero">
        <div>
            <a href="{{ route('books.index') }}" class="back-link">⟵ All books</a>
            <p class="eyebrow">Theme / {{ $topic['title'] }}</p>
            <h1>Books about {{ strtolower($topic['title']) }}</h1>
            <p class="lede">{{ $topic['blurb'] }} A short guide to the titles on this shelf, and where to begin.</p>
            <div class="detail-actions">
                @if ($lead)
                    <a href="{{ route('books.show', $lead['slug']) }}" class="button button-ochre">Start here</a>
                @endif
                <a href="{{ route('books.index') }}" class="text-link">See every title <span aria-hidden="true">⟶</span></a>
            </div>
        </div>
        @if ($lead)
            <a href="{{ route('books.show', $lead['slug']) }}" class="influence-hero-cover" aria-label="{{ $lead['title'] }}">
                @include('partials.cover', ['book' => $lead])
            </a>
        @endif
    </section>

    <article class="influence-body">
        <p>
            {{ $topic['title'] }} runs through more than one book on this list. Each approaches it from a different angle, so
            you can read them in any order. Start with the question that feels most urgent to you today.
        </p>

        @if ($books->isEmpty())
            <p>No books are filed under this theme yet.</p>
        @endif

        <h2>The books on this shelf</h2>
        <div class="influence-principles">
            @foreach ($books as $slug => $book)
                <article>
                    <p class="eyebrow">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                    <h3><a href="{{ route('books.show', $slug) }}">{{ $book['title'] }}</a></h3>
                    <p>{{ $book['question'] }}</p>
                </article>
            @endforeach
        </div>

        @foreach ($books as $slug => $book)
            <div class="influence-featured-book">
                <a href="{{ route('books.show', $slug) }}" tabindex="-1" aria-hidden="true">
                    @include('partials.cover', ['book' => $book])
                </a>
                <div>
                    <p class="book-meta">{{ $book['genre'] }} / {{ $book['year'] }}</p>
                    <h3>{{ $book['title'] }}</h3>
                    <p>{{ $book['summary'] }}</p>
                    <a href="{{ route('books.show', $slug) }}" class="text-link">About this book <span aria-hidden="true">⟶</span></a>
                </div>
            </div>
        @endforeach
    </article>

    @include('partials.newsletter')
@endsection
