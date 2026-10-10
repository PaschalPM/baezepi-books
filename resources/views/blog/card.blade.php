{{-- A post card. Expects $category, $section, $slug, $note and optional $heading ('h2' or 'h3'). --}}
@php($url = route('notes.show', [$category, $slug]))
@php($heading ??= 'h2')
<article class="blog-post-card">
    <a href="{{ $url }}" tabindex="-1" aria-hidden="true">
        @include('blog.visual', ['visual' => $note['visual'], 'variant' => 'card'])
    </a>
    <div>
        <p class="eyebrow">{{ $section['title'] }}</p>
        <{{ $heading }}><a href="{{ $url }}">{{ $note['title'] }}</a></{{ $heading }}>
        <p>{{ $note['excerpt'] }}</p>
        <a href="{{ $url }}" class="text-link">Read the note <span aria-hidden="true">⟶</span></a>
    </div>
</article>
