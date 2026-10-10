@extends('layouts.legal', [
    'title' => 'Privacy Policy',
    'intro' => 'What we collect when you visit or write to us, why we collect it, and the choices you have.',
    'toc' => [
        'who-we-are' => 'Who we are',
        'what-we-collect' => 'What we collect',
        'how-we-use' => 'How we use it',
        'sharing' => 'Who we share it with',
        'no-sale' => 'No sale or sharing',
        'signals' => 'Do Not Track and GPC',
        'retention' => 'How long we keep it',
        'state-rights' => 'Your state privacy rights',
        'california' => 'California residents',
        'email' => 'Email communications',
        'security' => 'Security',
        'children' => 'Children’s privacy',
        'outside-us' => 'Visitors outside the US',
        'changes' => 'Changes to this policy',
        'contact' => 'Contact',
    ],
])

@php($legal = config('site.legal'))

@section('legal')
    <h2 id="who-we-are">1. Who we are</h2>
    <p>
        This website is operated by {{ $legal['entity'] }} (“{{ config('site.name') }}”, “we”, “us”), {{ $legal['address'] }}.
        This policy explains how we handle personal information collected through this site.
    </p>

    <h2 id="what-we-collect">2. What we collect</h2>
    <p>We keep data collection to a minimum. We do not run advertising or third-party analytics on this site.</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Examples</th>
                    <th scope="col">Source</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Identifiers</td>
                    <td>Name and email address you give us; IP address</td>
                    <td>You; your browser</td>
                </tr>
                <tr>
                    <td>Communications</td>
                    <td>The contents of messages you send through the contact form or by email</td>
                    <td>You</td>
                </tr>
                <tr>
                    <td>Internet activity</td>
                    <td>Browser type, pages requested, and the date and time of each request, recorded in server logs</td>
                    <td>Your browser</td>
                </tr>
            </tbody>
        </table>
    </div>
    <p>
        We do not collect sensitive personal information such as Social Security numbers, financial account details, precise
        geolocation or health information. A small number of strictly necessary cookies keep the site working; see our
        <a href="{{ route('cookies') }}">Cookie Policy</a>.
    </p>

    <h2 id="how-we-use">3. How we use it</h2>
    <ul>
        <li>To send the newsletter you signed up for, including new releases and essays.</li>
        <li>To read and reply to your messages, including rights, events and order enquiries.</li>
        <li>To keep the site secure, prevent spam and fix problems.</li>
        <li>To comply with the law and enforce our <a href="{{ route('terms') }}">Terms of Use</a>.</li>
    </ul>
    <p>We do not use your information for targeted advertising, profiling or automated decision-making.</p>

    <h2 id="sharing">4. Who we share it with</h2>
    <p>We disclose personal information only to service providers that help us run the site, under contracts that limit their use of it to providing services to us:</p>
    <ul>
        <li><strong>Hosting.</strong> Our web host stores the site, its database and server logs.</li>
        <li><strong>Email.</strong> Newsletter addresses are held by {{ $legal['newsletter_provider'] }}.</li>
        <li><strong>Fonts.</strong> Our typefaces are served by Google Fonts. When a page loads, your browser requests the fonts from Google, which receives your IP address under its own privacy policy.</li>
    </ul>
    <p>
        We may also disclose information to comply with a subpoena, court order or other legal process; to protect the rights,
        property or safety of us, our readers or others; or as part of a merger, acquisition or sale of assets, in which case
        this policy will continue to apply to your information.
    </p>

    <h2 id="no-sale">5. No sale or sharing</h2>
    <p>
        We do not sell your personal information, and we do not “share” it for cross-context behavioral advertising, as
        those terms are defined under California and other state privacy laws. We have not done so in the past 12 months.
    </p>

    <h2 id="signals">6. Do Not Track and Global Privacy Control</h2>
    <p>
        Because we do not track you across websites or sell your information, there is nothing for a Do Not Track or Global
        Privacy Control (GPC) signal to switch off. We nonetheless treat a GPC signal as a valid request to opt out of sale and
        sharing, as required by law.
    </p>

    <h2 id="retention">7. How long we keep it</h2>
    <ul>
        <li><strong>Newsletter:</strong> until you unsubscribe, after which we keep only your address on a suppression list so we don’t email you again.</li>
        <li><strong>Correspondence:</strong> for up to two years after our last exchange, unless it relates to an ongoing agreement.</li>
        <li><strong>Server logs:</strong> for up to 90 days.</li>
        <li><strong>Session data:</strong> deleted automatically shortly after your visit ends.</li>
    </ul>

    <h2 id="state-rights">8. Your state privacy rights</h2>
    <p>
        Depending on where you live, including California, Colorado, Connecticut, Virginia, Texas, Oregon and other states
        with consumer privacy laws, you may have the right to:
    </p>
    <ul>
        <li><strong>Know and access</strong> the personal information we hold about you;</li>
        <li><strong>Correct</strong> inaccurate information;</li>
        <li><strong>Delete</strong> your information;</li>
        <li><strong>Get a copy</strong> in a portable format; and</li>
        <li><strong>Opt out</strong> of sale, sharing, targeted advertising and profiling. We don’t do any of these, but the right is yours.</li>
    </ul>
    <p>
        To make a request, email <a href="mailto:{{ $legal['privacy_email'] }}">{{ $legal['privacy_email'] }}</a> from the
        address we have on file, or tell us how else we can confirm it’s you. You may use an authorized agent, who will need
        your signed permission. We will respond within 45 days and will not discriminate against you for exercising your
        rights. If we deny your request, you may appeal by replying to our decision, and we will respond to the appeal within
        the time the law requires.
    </p>

    <h2 id="california">9. California residents</h2>
    <p>
        The table in <a href="#what-we-collect">section 2</a> lists the categories of personal information we have collected
        in the past 12 months, and <a href="#how-we-use">sections 3</a> and <a href="#sharing">4</a> describe the purposes
        and the categories of recipients. Under California’s “Shine the Light” law, you may ask whether we disclosed personal
        information to third parties for their direct marketing; we do not.
    </p>

    <h2 id="email">10. Email communications</h2>
    <p>
        We send the newsletter only to people who sign up for it. Every email includes our postal address and a working
        unsubscribe link, and we honor unsubscribe requests promptly. We may still send you a one-off reply to a message you
        sent us.
    </p>

    <h2 id="security">11. Security</h2>
    <p>
        The site is served over HTTPS, forms are protected against cross-site request forgery, and access to stored data is
        limited to people who need it. No method of transmission or storage is completely secure. If a breach affects your
        personal information, we will notify you as required by applicable state law.
    </p>

    <h2 id="children">12. Children’s privacy</h2>
    <p>
        This site is not directed at children under {{ $legal['min_age'] }}, and we do not knowingly collect personal
        information from them, in line with the Children’s Online Privacy Protection Act (COPPA). If you believe a child under
        {{ $legal['min_age'] }} has given us their information, please contact us and we will delete it.
    </p>

    <h2 id="outside-us">13. Visitors outside the US</h2>
    <p>
        This site is operated from the United States, and information we collect is stored and processed in the United
        States. If you use the site from elsewhere, you understand that your information will be handled under US law.
    </p>

    <h2 id="changes">14. Changes to this policy</h2>
    <p>
        We review this policy at least once a year and may update it as the site or the law changes. The date at the top of
        the page shows when it was last revised. If a change is significant, we will tell newsletter subscribers by email.
    </p>

    <h2 id="contact">15. Contact</h2>
    <p>
        Questions about privacy can be sent to <a href="mailto:{{ $legal['privacy_email'] }}">{{ $legal['privacy_email'] }}</a>
        or by mail to {{ $legal['entity'] }}, {{ $legal['address'] }}.
    </p>
@endsection
