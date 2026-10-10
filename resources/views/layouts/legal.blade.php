{{-- Shared shell for legal pages. Expects $title, $intro and $toc (id => heading). --}}
@extends('layouts.app', ['description' => $intro])

@section('content')
    @php($legal = config('site.legal'))

    <section class="legal-page">
        <p class="eyebrow">Legal</p>
        <h1>{{ $title }}</h1>
        <p>{{ $intro }}</p>
        <p class="legal-updated">Last updated {{ \Illuminate\Support\Carbon::parse($legal['updated'])->format('j F Y') }}</p>

        <nav class="legal-toc" aria-label="On this page">
            <p class="eyebrow">On this page</p>
            <ol>
                @foreach ($toc as $id => $heading)
                    <li><a href="#{{ $id }}">{{ $heading }}</a></li>
                @endforeach
            </ol>
        </nav>

        <div class="legal">
            @yield('legal')
        </div>
    </section>
@endsection
