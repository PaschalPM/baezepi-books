@extends('layouts.app', ['title' => 'Correspondence', 'description' => 'Contact '.config('site.name').' about rights, events, bulk orders or anything you have read.'])

@section('content')
    <section class="page-hero contact-hero">
        <p class="eyebrow">Correspondence</p>
        <h1>Write to me. I read every letter.</h1>
        <p class="lede">For reader correspondence, press, speaking and rights inquiries.</p>
    </section>

    <section class="contact-layout">
        <aside>
            <div>
                <p class="eyebrow">Email</p>
                <a href="mailto:{{ config('site.email') }}" class="contact-link">{{ config('site.email') }}</a>
            </div>
            <div>
                <p class="eyebrow">Phone</p>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', config('site.phone')) }}" class="contact-link">{{ config('site.phone') }}</a>
            </div>
            <div>
                <p class="eyebrow">Office</p>
                <p>{{ config('site.legal.address') }}</p>
            </div>
        </aside>

        <div>
            <p class="eyebrow">A Short Letter</p>
            <h2>Send a note.</h2>

            <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
                @csrf
                <label><span>Name</span>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label><span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label><span>Subject</span>
                    <input type="text" name="subject" value="{{ old('subject') }}">
                </label>
                <label><span>Message</span>
                    <textarea name="message" required>{{ old('message') }}</textarea>
                </label>

                @if (session('status'))
                    <p class="form-status">{{ session('status') }}</p>
                @endif
                @if ($errors->any())
                    <p class="form-status">{{ $errors->first() }}</p>
                @endif

                <button type="submit" class="button button-forest">Send Note</button>
            </form>
        </div>
    </section>
@endsection
