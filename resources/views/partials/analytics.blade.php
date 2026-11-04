{{-- Google Analytics, loaded only after a visitor accepts. Signed-in staff are not counted. --}}
@if (($analyticsId = config('site.google_analytics_id')) && auth()->guest())
    <section class="consent-banner" aria-label="Cookie consent" hidden>
        <p>We’d like to use Google Analytics cookies to see how the site is used. They stay off unless you accept. See our <a href="{{ route('cookies') }}">Cookie Policy</a>.</p>
        <div>
            <button type="button" class="button button-forest" data-consent="granted">Accept</button>
            <button type="button" class="button button-outline" data-consent="denied">Decline</button>
        </div>
    </section>

    <script>
        (() => {
            const id = @json($analyticsId);
            const key = 'analytics-consent';
            const disabled = `ga-disable-${id}`;
            const banner = document.querySelector('.consent-banner');
            const settings = document.querySelector('.cookie-settings');
            let loaded = false;

            const stored = () => {
                try {
                    return localStorage.getItem(key);
                } catch {
                    return null;
                }
            };

            const remember = (choice) => {
                try {
                    localStorage.setItem(key, choice);
                } catch {
                    // Storage is blocked, so the banner returns on the next page.
                }
            };

            const enable = () => {
                window[disabled] = false;
                if (loaded) return;
                loaded = true;

                window.dataLayer = window.dataLayer || [];
                window.gtag = function () { dataLayer.push(arguments); };
                gtag('js', new Date());
                gtag('config', id, { allow_google_signals: false, allow_ad_personalization_signals: false });

                const script = document.createElement('script');
                script.async = true;
                script.src = `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(id)}`;
                document.head.append(script);
            };

            const disable = () => {
                window[disabled] = true;
                const host = location.hostname.replace(/^www\./, '');
                document.cookie.split('; ').map((cookie) => cookie.split('=')[0])
                    .filter((name) => name === '_ga' || name.startsWith('_ga_'))
                    .forEach((name) => ['', `; domain=${host}`, `; domain=.${host}`].forEach((domain) => {
                        document.cookie = `${name}=; Max-Age=0; path=/${domain}`;
                    }));
            };

            // Global Privacy Control counts as a standing "no", so there is nothing to ask.
            if (navigator.globalPrivacyControl) {
                disable();
                settings.hidden = true;
                return;
            }

            banner.addEventListener('click', (event) => {
                const choice = event.target.dataset.consent;
                if (!choice) return;

                remember(choice);
                banner.hidden = true;
                choice === 'granted' ? enable() : disable();
            });
            settings.addEventListener('click', () => { banner.hidden = false; });

            if (stored() === 'granted') enable();
            else if (stored() !== 'denied') banner.hidden = false;
        })();
    </script>
@endif
