<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $books = $this->shelf();

        return view('pages.home', [
            'books' => $books,
            'featured' => $books->where('featured', true),
            'topics' => config('site.topics'),
        ]);
    }

    public function books(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        return view('books.index', [
            'books' => Book::shelf()->search($search)->paginate(12)->withQueryString(),
            'topics' => config('site.topics'),
            'search' => $search,
        ]);
    }

    public function book(string $slug): View
    {
        $book = Book::where('slug', $slug)->where('published', true)->firstOrFail();

        $topics = Arr::only(config('site.topics'), $book->topics ?? []);

        return view('books.show', compact('slug', 'book', 'topics'));
    }

    /** Purchase information for books sold directly rather than through a store link. */
    public function checkout(string $slug): View
    {
        $book = Book::where('slug', $slug)->where('published', true)->firstOrFail();

        return view('books.checkout', compact('book'));
    }

    public function topic(string $key): View
    {
        $topic = config("site.topics.$key");
        $books = $this->shelf()->filter(fn (Book $book) => $book->inTopic($key));

        return view('books.topic', compact('topic', 'books'));
    }

    public function notes(): View
    {
        return view('pages.notes', ['categories' => config('site.blog')]);
    }

    public function noteCategory(string $category): View
    {
        $section = config("site.blog.$category") ?? abort(404);

        return view('blog.category', compact('category', 'section'));
    }

    public function note(string $category, string $slug): View
    {
        $section = config("site.blog.$category") ?? abort(404);
        $note = $section['posts'][$slug] ?? abort(404);

        $related = collect($section['posts'])->except($slug)->take(3);

        return view('pages.note', compact('category', 'section', 'slug', 'note', 'related'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // TODO: deliver the message (Mail, database, CRM...).

        return back()->with('status', 'Thank you. Your letter has been received.');
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // TODO: add the address to your newsletter provider.

        return back()->with('subscribed', 'You are on the list. Watch your inbox.');
    }

    /** Published books in display order, keyed by slug. */
    private function shelf(): Collection
    {
        return Book::shelf()->get()->keyBy('slug');
    }
}
