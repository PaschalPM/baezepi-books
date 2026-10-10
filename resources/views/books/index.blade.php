@extends('layouts.app', ['title' => 'The Books', 'description' => 'Every title from '.config('site.name').', with what each book is about and where to buy it.'])

@section('content')
    <section class="page-hero books-hero">
        <p class="eyebrow">The Library</p>
        <h1>All the books, one shelf.</h1>
        <p class="lede">Each title begins with a different question about the present. Follow the one that speaks to yours.</p>
        <a href="#themes" class="text-link">Explore books about social media and modern influence <span aria-hidden="true">⟶</span></a>
    </section>

    <section class="book-list">
        <form method="GET" action="{{ route('books.index') }}" class="book-search" role="search">
            <label for="book-search" class="sr-only">Search books by title</label>
            <input type="search" id="book-search" name="q" value="{{ $search }}" placeholder="Search by title…">
            <button type="submit" aria-label="Search">
                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="m16 16 4.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </button>
        </form>

        @if ($search !== '')
            <p class="book-search-status">
                {{ $books->total() }} {{ Str::plural('book', $books->total()) }} matching “{{ $search }}” · <a href="{{ route('books.index') }}">Show all books</a>
            </p>
        @endif

        @foreach ($books as $book)
            @php($slug = $book->slug)
            <article class="book-row">
                <div class="book-index">{{ str_pad($books->firstItem() + $loop->index, 2, '0', STR_PAD_LEFT) }}</div>
                <a href="{{ route('books.show', $slug) }}" class="book-row-cover" tabindex="-1" aria-hidden="true">
                    @include('partials.cover', ['book' => $book])
                </a>
                <div class="book-row-copy">
                    <p class="book-meta">{{ $book['genre'] }} / {{ $book['year'] }}</p>
                    <h2>{{ $book['title'] }}</h2>
                    <p class="book-subtitle">{{ $book['subtitle'] }}</p>
                    <p>{{ $book['summary'] }}</p>
                    <a href="{{ route('books.show', $slug) }}" class="text-link">About this book <span aria-hidden="true">⟶</span></a>
                </div>
                <div class="book-row-question">
                    <p>{{ $book['question'] }}</p>
                    <span>{{ $book['pages'] }} pp @if ($book->formattedPrice()) · {{ $book->formattedPrice() }} @endif</span>
                </div>
            </article>
        @endforeach

        {{ $books->onEachSide(1)->links('partials.pagination') }}
    </section>

    <section class="topic-index" id="themes">
        <p class="eyebrow">Browse by theme</p>
        <div class="chip-row">
            @foreach ($topics as $key => $topic)
                <a href="{{ route("topics.$key") }}" class="chip">{{ $topic['title'] }}</a>
            @endforeach
        </div>
    </section>

    @include('partials.newsletter')
@endsection
