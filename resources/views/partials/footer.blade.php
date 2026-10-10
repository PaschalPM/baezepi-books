<footer class="site-footer">
    <div class="footer-top">
        <div>
            <a href="{{ route('home') }}" class="brand" aria-label="{{ config('site.name') }} home">
                <img src="{{ asset('baeze-publishing-logo-f5cf45934e202eb7.webp') }}" alt="{{ config('site.name') }} Inc." width="1812" height="1012" decoding="async" loading="lazy">
            </a>
            <p class="footer-intro">{{ config('site.tagline') }}</p>
        </div>

        <div class="footer-links">
            <div>
                <p>Explore</p>
                <a href="{{ route('books.index') }}">The Books</a>
                <a href="{{ route('notes.index') }}">Notes</a>
                <a href="{{ route('stats') }}">Reading Statistics</a>
                <a href="{{ route('about') }}">The Author</a>
                <a href="{{ route('contact') }}">Correspondence</a>
            </div>
            <div>
                <p>Contact</p>
                <a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', config('site.phone')) }}">{{ config('site.phone') }}</a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} {{ config('site.name') }} Inc. All rights reserved.</span>
        <div>
            <a href="{{ route('privacy') }}">Privacy</a>
            <a href="{{ route('terms') }}">Terms</a>
            <a href="{{ route('cookies') }}">Cookies</a>
        </div>
    </div>
</footer>
