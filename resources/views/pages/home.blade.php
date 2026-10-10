@extends('layouts.app')

@push('structured-data')
@php
$schema1 = [
'@graph' => [
[
'@type' => 'WebSite',
'@id' => url('/').'#website',
'url' => url('/'),
'name' => config('site.name'),
'description' => config('site.description'),
'publisher' => ['@id' => url('/').'#organization'],
],
[
'@type' => 'Organization',
'@id' => url('/').'#organization',
'name' => config('site.legal.entity'),
'url' => url('/'),
'email' => config('site.email'),
'sameAs' => config('site.seo.same_as'),
],
],
];
@endphp
@include('partials.jsonld', ['data' => $schema1])
@endpush

@section('content')
{{-- Hero --}}
<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow reveal">{{ config('site.author.name') }} / Independent author</p>
        <h1 class="reveal delay-1">Books for people who look twice.</h1>
        <p class="hero-text reveal delay-2">
            {{ config('site.author.name') }} writes across culture, technology, influence and the inner craft of making useful things.
        </p>
        <div class="hero-action reveal delay-3">
            <span>Where should<br>I begin?</span>
            <span class="arrow" aria-hidden="true">⟶</span>
            <a href="{{ route('books.index') }}" class="button button-ochre">Match me with a book</a>
        </div>
    </div>

    <div class="hero-covers">
        @foreach ($books->take(5) as $slug => $book)
        <a href="{{ route('books.show', $slug) }}" class="hero-book book-{{ $loop->iteration }}">
            @include('partials.cover', ['book' => $book])
        </a>
        @endforeach
    </div>
</section>

{{-- Themes --}}
<section class="question-strip">
    <div class="strip-title">
        <h2> A shelf connected
            by one question</h2>
    </div>
    @foreach ($topics as $key => $topic)
    <a href="{{ route("topics.$key") }}">
        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
        <strong>{{ $topic['title'] }}</strong>
        <p>{{ $topic['blurb'] }}</p>
        <b aria-hidden="true">⟶</b>
    </a>
    @endforeach
</section>

{{-- Featured books --}}
<section class="shelf-section">
    <div class="section-intro">
        <p class="eyebrow">The Books</p>
        <h2>Find the thought that keeps returning.</h2>
        <a href="{{ route('books.index') }}" class="text-link">See every title <span aria-hidden="true">⟶</span></a>
    </div>

    <div class="featured-books">
        @foreach ($featured as $slug => $book)
        <article class="featured-book">
            <a href="{{ route('books.show', $slug) }}" class="cover-link" tabindex="-1" aria-hidden="true">
                @include('partials.cover', ['book' => $book])
            </a>
            <div>
                <p class="book-meta">{{ $book['genre'] }} / {{ $book['year'] }}</p>
                <h3>{{ $book['title'] }}</h3>
                <p>{{ $book['subtitle'] }}</p>
                <a href="{{ route('books.show', $slug) }}" class="text-link">About this book <span aria-hidden="true">⟶</span></a>
            </div>
        </article>
        @endforeach
    </div>
</section>

{{-- Author --}}
<section class="author-note">
    <div class="note-mark" aria-hidden="true">“</div>
    <div>
        <p class="eyebrow">The author</p>
        <h2>Writing is a way of paying closer attention.</h2>
        <p>{{ config('site.author.short_bio') }}</p>
        <a href="{{ route('about') }}" class="button button-forest">Meet Patrick</a>
    </div>
</section>

@include('partials.newsletter')
@endsection