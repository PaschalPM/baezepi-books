{{-- A post's visual. $visual from config('site.blog.*.posts.*.visual'); $variant is 'card' or 'hero'. --}}
@php($eager = $variant === 'hero')
@if ($visual['type'] === 'image')
    <picture>
        <source srcset="{{ asset($visual['webp']) }}" type="image/webp">
        <img src="{{ asset($visual['jpg']) }}" alt="{{ $visual['alt'] }}" @if ($eager) loading="eager" fetchpriority="high" @else loading="lazy" @endif decoding="async">
    </picture>
@elseif ($visual['type'] === 'timeline')
    <div class="book-timeline book-timeline-{{ $variant }}" role="img" aria-label="{{ $visual['label'] }}">
        <div class="book-timeline-line"></div>
        @foreach ($visual[$variant] as $step)
            <div class="book-timeline-step"><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><b>{{ $step }}</b></div>
        @endforeach
    </div>
@elseif ($visual['type'] === 'outline')
    <div @class(['outline-visual', "outline-visual-$variant", 'outline-visual-chapters' => count($visual[$variant]) > 4]) role="img" aria-label="{{ $visual['label'] }}">
        <span class="outline-thread outline-thread-one"></span><span class="outline-thread outline-thread-two"></span><span class="outline-thread outline-thread-three"></span>
        <div class="outline-cards">
            @foreach ($visual[$variant] as $card)
                <span class="outline-card outline-card-{{ $loop->iteration }}">{{ $card }}</span>
            @endforeach
        </div>
        <span class="outline-mark outline-mark-one"></span><span class="outline-mark outline-mark-two"></span>
    </div>
@endif
