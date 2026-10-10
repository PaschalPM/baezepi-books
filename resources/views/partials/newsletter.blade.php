<section class="newsletter">
    <div>
        <p class="eyebrow">From the desk</p>
        <h2>Letters for the long way around.</h2>
    </div>

    <div>
        <p>A quiet note when a new book arrives, plus the rare essay shared with readers on this list.</p>

        <form method="POST" action="{{ route('subscribe') }}" class="newsletter-form">
            @csrf
            <label for="newsletter-email" class="sr-only">Email address</label>
            <input id="newsletter-email" type="email" name="email" placeholder="Your email address" value="{{ old('email') }}" required>
            <button type="submit" class="button button-forest">Subscribe</button>
            @if (session('subscribed'))
                <p class="form-status">{{ session('subscribed') }}</p>
            @endif
            @error('email')
                <p class="form-status">{{ $message }}</p>
            @enderror
        </form>
    </div>
</section>
