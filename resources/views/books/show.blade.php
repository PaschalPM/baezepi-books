@extends('layouts.app', [
    'title' => $book->metaTitle(),
    'description' => $book->metaDescription(),
    'image' => $book->cover_url,
    'imageAlt' => 'Cover of '.$book->title,
    'type' => 'book',
])

@push('structured-data')
    @php
        $schema1 = [
            '@type' => 'Book',
            'name' => $book->title,
            'alternativeHeadline' => $book->subtitle,
            'description' => $book->summary,
            'url' => route('books.show', $book),
            'image' => $book->cover_url,
            'author' => ['@type' => 'Person', 'name' => config('site.author.name'), 'url' => route('about')],
            'publisher' => ['@type' => 'Organization', 'name' => config('site.legal.entity'), 'url' => url('/')],
            'datePublished' => $book->year ? (string) $book->year : null,
            'genre' => $book->genre,
            'numberOfPages' => $book->pages,
            'inLanguage' => 'en',
            'about' => collect($topics)->pluck('title')->values()->all(),
            'offers' => $book->buy_links
                ? collect($book->buy_links)->map(fn ($link) => [
                    '@type' => 'Offer',
                    'url' => $link['url'],
                    'availability' => 'https://schema.org/InStock',
                    'seller' => ['@type' => 'Organization', 'name' => $link['label']],
                ])->all()
                : ($book->price !== null ? [[
                    '@type' => 'Offer',
                    'url' => route('books.checkout', $book),
                    'price' => $book->price,
                    'priceCurrency' => 'USD',
                    'availability' => 'https://schema.org/InStock',
                    'seller' => ['@type' => 'Organization', 'name' => config('site.name')],
                ]] : []),
        ];
    @endphp
    @include('partials.jsonld', ['data' => $schema1])
    @php
        $schema2 = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'The Books', 'item' => route('books.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $book->title, 'item' => route('books.show', $book)],
                ],
        ];
    @endphp
    @include('partials.jsonld', ['data' => $schema2])
@endpush

@section('content')
    <section class="book-detail">
        <div class="book-detail-cover">
            @include('partials.cover', ['book' => $book])
        </div>

        <div class="book-detail-copy">
            <p class="eyebrow">{{ $book['genre'] }} / {{ $book['year'] }}</p>
            <h1>{{ $book['title'] }}</h1>
            <h2>{{ $book['subtitle'] }}</h2>
            <p class="lede">{{ $book['summary'] }}</p>
            <p class="detail-question">{{ $book['question'] }}</p>
            @if ($book['buy_links'])
                <div class="buy-links">
                    <p class="eyebrow">Buy the book</p>
                    <div class="detail-actions">
                        @foreach ($book['buy_links'] as $link)
                            <a href="{{ $link['url'] }}" @class(['button', 'button-ochre' => $loop->first, 'button-outline' => ! $loop->first])
                               target="_blank" rel="noopener">{{ $link['label'] }}</a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('books.index') }}" class="text-link">See every title <span aria-hidden="true">⟶</span></a>
            @else
                <div class="detail-actions">
                    <a href="{{ route('books.checkout', $book) }}" class="button button-ochre">{{ $book->formattedPrice() ? 'Purchase for '.$book->formattedPrice() : 'Purchase this book' }}</a>
                    <a href="{{ route('books.index') }}" class="text-link">See every title <span aria-hidden="true">⟶</span></a>
                </div>
            @endif
            @if (count($topics))
                <div class="chip-row">
                    @foreach ($topics as $key => $topic)
                        <a href="{{ route("topics.$key") }}" class="chip">{{ $topic['title'] }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="book-afterword">
        <p>Questions about rights, events or bulk orders?</p>
        <a href="{{ route('contact') }}" class="text-link">Write to the press <span aria-hidden="true">⟶</span></a>
    </section>
@endsection
