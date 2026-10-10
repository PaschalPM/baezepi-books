@extends('layouts.legal', [
    'title' => 'Terms of Use',
    'intro' => 'The rules for using this website. By browsing the site, you agree to them.',
    'toc' => [
        'about' => 'About these terms',
        'eligibility' => 'Eligibility',
        'using' => 'Using the site',
        'ip' => 'Our content',
        'your-content' => 'What you send us',
        'dmca' => 'Copyright complaints (DMCA)',
        'books' => 'Books and orders',
        'newsletter' => 'Newsletter',
        'links' => 'Links to other sites',
        'disclaimer' => 'Disclaimer of warranties',
        'liability' => 'Limitation of liability',
        'indemnity' => 'Indemnification',
        'disputes' => 'Disputes and governing law',
        'general' => 'General',
        'changes' => 'Changes',
        'contact' => 'Contact',
    ],
])

@php($legal = config('site.legal'))

@section('legal')
    <h2 id="about">1. About these terms</h2>
    <p>
        These terms are a binding agreement between you and {{ $legal['entity'] }} (“we”, “us”) and govern your use of this
        website. Our <a href="{{ route('privacy') }}">Privacy Policy</a> and <a href="{{ route('cookies') }}">Cookie Policy</a>
        form part of these terms. If you do not agree with them, please do not use the site.
    </p>

    <h2 id="eligibility">2. Eligibility</h2>
    <p>
        You must be at least {{ $legal['min_age'] }} years old to use the site. If you are under 18, you may use it only with
        the involvement of a parent or guardian.
    </p>

    <h2 id="using">3. Using the site</h2>
    <p>You may browse and read the site for personal, non-commercial purposes. You agree not to:</p>
    <ul>
        <li>use the site in any way that breaks the law or infringes anyone’s rights;</li>
        <li>try to gain unauthorized access to the site, its servers or its data;</li>
        <li>introduce viruses or other harmful code, or disrupt the site’s operation;</li>
        <li>scrape, copy or harvest content in bulk by automated means, including to train machine-learning models, without our written permission; or</li>
        <li>use our forms to send spam, abuse or unsolicited advertising.</li>
    </ul>
    <p>We may suspend or block access to the site at any time, for any reason.</p>

    <h2 id="ip">4. Our content</h2>
    <p>
        Everything on this site, including text, essays, book excerpts, cover designs, images and the Baeze name and logo, is
        owned by us or our licensors and is protected by US and international copyright and trademark law.
    </p>
    <p>
        You may quote short passages for review, commentary or research, as permitted by fair use, provided you credit the
        author and link back to the source. Any other reproduction, distribution or adaptation requires our prior written
        permission. For rights and permissions, please <a href="{{ route('contact') }}">write to us</a>.
    </p>

    <h2 id="your-content">5. What you send us</h2>
    <p>
        When you send us a message, letter or idea, you confirm that it is yours to share and that it does not break any law
        or anyone’s rights. You keep ownership of what you send. You agree that we may use it to reply to you and, if you
        give us permission, to quote it, for example as a reader’s letter. Unsolicited manuscripts and ideas are received
        without any obligation of confidentiality or payment.
    </p>

    <h2 id="dmca">6. Copyright complaints (DMCA)</h2>
    <p>
        We respect intellectual property rights. If you believe material on this site infringes your copyright, send a notice
        under the Digital Millennium Copyright Act to our designated agent:
    </p>
    <p>
        {{ $legal['dmca_agent'] }}<br>
        {{ $legal['entity'] }}, {{ $legal['address'] }}<br>
        <a href="mailto:{{ $legal['dmca_email'] }}">{{ $legal['dmca_email'] }}</a>
    </p>
    <p>Your notice must include:</p>
    <ul>
        <li>your physical or electronic signature;</li>
        <li>a description of the copyrighted work you believe has been infringed;</li>
        <li>the location of the material on our site, such as its URL;</li>
        <li>your name, address, telephone number and email address;</li>
        <li>a statement that you have a good-faith belief the use is not authorized by the copyright owner, its agent or the law; and</li>
        <li>a statement, under penalty of perjury, that the information in your notice is accurate and that you are the copyright owner or authorized to act on the owner’s behalf.</li>
    </ul>

    <h2 id="books">7. Books and orders</h2>
    <p>
        Book descriptions, prices and availability shown on the site are for information and may change. Where books are
        sold through third-party retailers, your purchase is governed by that retailer’s terms. Direct, bulk or event orders
        arranged with us will be confirmed in writing, along with any additional terms that apply.
    </p>

    <h2 id="newsletter">8. Newsletter</h2>
    <p>
        Subscribing is free and optional. You can unsubscribe at any time using the link in any email. We may change how
        often we send it, or stop it altogether.
    </p>

    <h2 id="links">9. Links to other sites</h2>
    <p>
        The site may link to websites run by others, such as booksellers or publications. We do not control those sites and
        are not responsible for their content, policies or practices.
    </p>

    <h2 id="disclaimer">10. Disclaimer of warranties</h2>
    <p>
        The essays, notes and books on this site share ideas and opinions. They are not professional, financial, legal or
        medical advice.
    </p>
    <p class="caps">
        The site and its content are provided “as is” and “as available”, without warranties of any kind, express or implied,
        including warranties of merchantability, fitness for a particular purpose, title and non-infringement. We do not
        warrant that the site will be uninterrupted, error-free, secure or free of harmful components.
    </p>

    <h2 id="liability">11. Limitation of liability</h2>
    <p class="caps">
        To the fullest extent permitted by law, {{ $legal['entity'] }} and its officers, employees and agents will not be
        liable for any indirect, incidental, special, consequential or punitive damages, or for any loss of profits, data or
        goodwill, arising from or related to your use of the site, even if advised of the possibility of such damages. Our
        total liability for any claim relating to the site will not exceed one hundred US dollars (US$100).
    </p>
    <p>
        Some states do not allow the exclusion of certain warranties or the limitation of certain damages, so some of the
        limits above may not apply to you.
    </p>

    <h2 id="indemnity">12. Indemnification</h2>
    <p>
        You agree to defend, indemnify and hold harmless {{ $legal['entity'] }} and its officers, employees and agents from
        any claims, losses and expenses, including reasonable attorneys’ fees, arising from your breach of these terms or your
        misuse of the site.
    </p>

    <h2 id="disputes">13. Disputes and governing law</h2>
    <p>
        These terms are governed by the laws of the State of {{ $legal['state'] }} and applicable US federal law, without
        regard to conflict-of-law rules. Before filing any claim, you agree to contact us and try to resolve the dispute
        informally for at least 30 days. If it cannot be resolved, it will be brought exclusively in the state or federal
        courts located in {{ $legal['venue'] }}, and you and we consent to their jurisdiction. Either party may bring a
        qualifying claim in small claims court.
    </p>
    <p>
        To the extent permitted by law, any claim must be brought on an individual basis and not as a plaintiff or class
        member in any class or representative action.
    </p>

    <h2 id="general">14. General</h2>
    <p>
        If any part of these terms is found unenforceable, the rest remains in effect. Our failure to enforce a provision is
        not a waiver of it. These terms are the entire agreement between you and us about the site, and you may not assign
        them without our consent.
    </p>

    <h2 id="changes">15. Changes</h2>
    <p>
        We may revise these terms from time to time. The date at the top of the page shows when they last changed. If you
        keep using the site after a change, you accept the revised terms.
    </p>

    <h2 id="contact">16. Contact</h2>
    <p>
        Questions about these terms can be sent to <a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a>
        or by mail to {{ $legal['entity'] }}, {{ $legal['address'] }}.
    </p>
@endsection
