{{-- Page links for both the site and the admin; each stylesheet styles .pagination. --}}
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Pages">
        @if ($paginator->onFirstPage())
            <span class="pagination-step" aria-disabled="true">← Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-step" rel="prev">← Previous</a>
        @endif

        <span class="pagination-pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-gap">{{ $element }}</span>
                @else
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Page {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-step" rel="next">Next →</a>
        @else
            <span class="pagination-step" aria-disabled="true">Next →</span>
        @endif
    </nav>
@endif
