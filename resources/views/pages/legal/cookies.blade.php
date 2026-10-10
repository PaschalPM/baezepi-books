@extends('layouts.legal', [
    'title' => 'Cookie Policy',
    'intro' => 'We use as few cookies as possible, and only the ones the site needs to work.',
    'toc' => [
        'what' => 'What cookies are',
        'ours' => 'Cookies we use',
        'third-party' => 'Third-party requests',
        'not-used' => 'What we don’t use',
        'consent' => 'Your choices',
        'control' => 'Managing cookies',
        'changes' => 'Changes',
        'contact' => 'Contact',
    ],
])

@php($legal = config('site.legal'))
@php($session = config('session'))

@section('legal')
    <h2 id="what">1. What cookies are</h2>
    <p>
        Cookies are small text files that a website stores in your browser. They let the site remember things between page
        loads, such as the fact that a form you are filling in really came from this site.
    </p>

    <h2 id="ours">2. Cookies we use</h2>
    <p>This site sets only strictly necessary cookies. They are first-party, so they are set by this site and not by anyone else.</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Purpose</th>
                    <th scope="col">Duration</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>{{ $session['cookie'] }}</code></td>
                    <td>Keeps your visit together from page to page, so we can show form confirmations and error messages. It does not identify you personally.</td>
                    <td>{{ $session['lifetime'] }} minutes{{ $session['expire_on_close'] ? ', or until you close your browser' : '' }}</td>
                </tr>
                <tr>
                    <td><code>XSRF-TOKEN</code></td>
                    <td>A security token that protects the contact and newsletter forms from cross-site request forgery.</td>
                    <td>{{ $session['lifetime'] }} minutes</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h2 id="third-party">3. Third-party requests</h2>
    <p>
        Our typefaces are loaded from Google Fonts. Google Fonts does not set cookies, but your browser does send your IP
        address to Google when it downloads the fonts. See our <a href="{{ route('privacy') }}#sharing">Privacy Policy</a> for
        details.
    </p>

    <h2 id="not-used">4. What we don’t use</h2>
    <p>We do not use:</p>
    <ul>
        <li>analytics or statistics cookies;</li>
        <li>advertising, retargeting or tracking pixels; or</li>
        <li>social media cookies or embedded widgets that track you.</li>
    </ul>

    <h2 id="consent">5. Your choices</h2>
    <p>
        Because we use no advertising, analytics or tracking cookies, none of our cookies are used to sell or share your
        personal information, and there is nothing to opt out of. We honor Global Privacy Control signals regardless; see our
        <a href="{{ route('privacy') }}#signals">Privacy Policy</a>. If we ever add cookies that are not strictly necessary, we
        will update this page and give you a way to opt out before they are used.
    </p>

    <h2 id="control">6. Managing cookies</h2>
    <p>
        You can view, block or delete cookies in your browser settings. Most browsers explain how in their help pages:
        look for “cookies” or “site data” in the settings of Chrome, Firefox, Safari or Edge. If you block the cookies above,
        you can still read the site, but the contact and newsletter forms will not work.
    </p>

    <h2 id="changes">7. Changes</h2>
    <p>We will update this page whenever the cookies we use change. The date at the top shows the latest revision.</p>

    <h2 id="contact">8. Contact</h2>
    <p>
        Questions about cookies can be sent to <a href="mailto:{{ $legal['privacy_email'] }}">{{ $legal['privacy_email'] }}</a>.
    </p>
@endsection
