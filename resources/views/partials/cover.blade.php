{{-- Book cover: the uploaded image when there is one, otherwise a typographic cover drawn in CSS. --}}
@if ($book['cover_url'])
    <img class="cover cover-image {{ $class ?? '' }}" src="{{ $book['cover_url'] }}" alt="Cover of {{ $book['title'] }}" loading="lazy" decoding="async">
@else
    <div class="cover cover-{{ $book['cover'] }} {{ $class ?? '' }}" role="img" aria-label="Cover of {{ $book['title'] }}">
        <span class="cover-mark">B.</span>
        <span class="cover-title">{{ $book['title'] }}</span>
        <span class="cover-author">{{ config('site.author.name') }}</span>
    </div>
@endif
