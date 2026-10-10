<header class="site-header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ config('site.name') }} home">
            <img src="{{ asset('baeze-publishing-logo-f5cf45934e202eb7.webp') }}" alt="{{ config('site.name') }} Inc." width="1812" height="1012" decoding="async" fetchpriority="high">
        </a>

        <button type="button" class="menu-button" aria-label="Open navigation" aria-expanded="false" aria-controls="main-nav">
            <span></span><span></span>
        </button>

        <nav id="main-nav" class="main-nav" aria-label="Main navigation">
            @foreach (config('site.nav') as $item)
                <a href="{{ route($item['route']) }}" @class(['active' => request()->routeIs($item['route'])])>{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ route('books.index') }}" class="button button-forest nav-cta">Start reading</a>
        </nav>
    </div>
</header>

<script>
    (() => {
        const button = document.querySelector('.menu-button');
        const nav = document.getElementById('main-nav');
        button.addEventListener('click', () => {
            const open = nav.classList.toggle('open');
            button.setAttribute('aria-expanded', open);
            button.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
        });
    })();
</script>
