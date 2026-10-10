@extends('layouts.app', ['title' => 'Notes', 'description' => 'Short essays and working notes on reading, writing and making things, from '.config('site.author.name').'.'])

@section('content')
<section class="page-hero blog-index-hero">
    <p class="eyebrow">Notes</p>
    <h1>For the work beneath the work.</h1>
    <p class="lede">Essays and practical notes on influence, creative practice, technology, and the long attention a meaningful body of work requires.</p>
</section>

<section class="blog-category-grid">
    @foreach ($categories as $category => $section)
        <a href="{{ route('notes.category', $category) }}" class="blog-category-card">
            <picture>
                <source srcset="{{ asset($section['image']['webp']) }}" type="image/webp">
                <img src="{{ asset($section['image']['jpg']) }}" alt="{{ $section['image']['alt'] }}" loading="lazy" decoding="async">
            </picture>
            <div>
                <p class="eyebrow">Topic</p>
                <h2>{{ $section['title'] }}</h2>
                <p>{{ $section['intro'] }}</p>
                <span class="text-link">Enter the notes <b aria-hidden="true">⟶</b></span>
            </div>
        </a>
    @endforeach
</section>

@include('partials.newsletter')
@endsection
