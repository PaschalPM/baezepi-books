@extends('layouts.app', ['title' => 'Page not found', 'noindex' => true])

@section('content')
    <section class="not-found">
        <p class="eyebrow">404</p>
        <h1>This page has wandered off the shelf.</h1>
        <p>The page you’re looking for isn’t here.</p>
        <a href="{{ route('books.index') }}" class="button button-ochre">Browse books</a>
    </section>
@endsection
