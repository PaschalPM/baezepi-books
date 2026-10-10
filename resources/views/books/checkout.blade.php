@extends('layouts.app', [
    'title' => 'Purchase '.$book->title,
    'description' => 'Purchase information for '.$book->title.'.',
    'image' => $book->cover_url,
    'noindex' => true,
])

@section('content')
    <section class="purchase-page">
        <div class="purchase-book">
            @include('partials.cover', ['book' => $book])
        </div>
        <div>
            <p class="eyebrow">Purchase information</p>
            <h1>{{ $book->title }}</h1>
            @if ($book->formattedPrice())
                <p class="purchase-price">{{ $book->formattedPrice() }}</p>
            @endif
            <p class="lede">For direct purchase information, email {{ config('site.name') }} with the title you would like to order.</p>
            <div class="detail-actions">
                <a href="mailto:{{ config('site.email') }}?subject={{ rawurlencode('Purchase inquiry: '.$book->title) }}" class="button button-ochre">Email about this book</a>
                <a href="{{ route('books.show', $book) }}" class="text-link">Back to the book <span aria-hidden="true">⟶</span></a>
            </div>
        </div>
    </section>
@endsection
