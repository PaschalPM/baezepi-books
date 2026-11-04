@extends('layouts.legal', [
    'title' => 'Cookie Policy',
    'intro' => 'We use the cookies the site needs to work, plus Google Analytics cookies only if you accept them.',
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
    <p>The cookies below are strictly necessary. They are first-party, so they are set by this site and not by anyone else.</p>

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

    <p>If you accept analytics, Google Analytics also sets these cookies on this site’s domain, so we can count visits and see which pages are read:</p>

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
                    <td><code>_ga</code></td>
                    <td>Tells one browser apart from another, so repeat visits are not counted as new visitors.</td>
                    <td>2 years</td>
                </tr>
                <tr>
                    <td><code>_ga_*</code></td>
                    <td>Keeps track of the current visit.</td>
                    <td>2 years</td>
                </tr>
            </tbody>
        </table>
    </div>
    <p>We also save your accept or decline choice in your browser’s local storage, under <code>analytics-consent</code>, so we don’t ask on every page.</p>

    <h2 id="third-party">3. Third-party requests</h2>
    <p>
        Our typefaces are loaded from Google Fonts. Google Fonts does not set cookies, but your browser does send your IP
        address to Google when it downloads the fonts. If you accept analytics, your browser also sends information about your
        visit to Google Analytics. See our <a href="{{ route('privacy') }}#sharing">Privacy Policy</a> for details.
    </p>

    <h2 id="not-used">4. What we don’t use</h2>
    <p>We do not use:</p>
    <ul>
        <li>advertising, retargeting or tracking pixels; or</li>
        <li>social media cookies or embedded widgets that track you.</li>
    </ul>

    <h2 id="consent">5. Your choices</h2>
    <p>
        Google Analytics does not load unless you accept it in the cookie banner. You can change your mind at any time using
        the “Cookie settings” link at the bottom of each page; if you decline, we also remove any Google Analytics cookies
        already set. If your browser sends a Global Privacy Control signal, we treat that as a decline and do not ask. See our
        <a href="{{ route('privacy') }}#signals">Privacy Policy</a>.
    </p>

    <h2 id="control">6. Managing cookies</h2>
    <p>
        You can view, block or delete cookies in your browser settings. Most browsers explain how in their help pages:
        look for “cookies” or “site data” in the settings of Chrome, Firefox, Safari or Edge. If you block the strictly
        necessary cookies, you can still read the site, but the contact and newsletter forms will not work.
    </p>

    <h2 id="changes">7. Changes</h2>
    <p>We will update this page whenever the cookies we use change. The date at the top shows the latest revision.</p>

    <h2 id="contact">8. Contact</h2>
    <p>
        Questions about cookies can be sent to <a href="mailto:{{ $legal['privacy_email'] }}">{{ $legal['privacy_email'] }}</a>.
    </p>
@endsection
