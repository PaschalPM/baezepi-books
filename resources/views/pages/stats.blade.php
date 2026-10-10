@extends('layouts.app', ['title' => 'Reading & Publishing Statistics', 'description' => 'A running tally of the books '.config('site.name').' has published and the readers who have found them.'])

@section('content')
    <section class="page-hero">
        <p class="eyebrow">Reading Statistics</p>
        <h1>The press, in numbers.</h1>
        <p class="lede">A running tally of what we have made and who has read it.</p>
    </section>

    <section class="stat-section">
        <div class="stat-grid">
            @foreach (config('site.stats') as $stat)
                <div class="stat">
                    <span class="stat-value">{{ $stat['value'] }}</span>
                    <span class="eyebrow">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>
@endsection
