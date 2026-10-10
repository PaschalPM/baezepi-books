<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        return view('admin.books.index', [
            'books' => Book::search($search)->orderBy('position')->orderByDesc('year')->orderBy('title')->paginate(20)->withQueryString(),
            'total' => Book::count(),
            'live' => Book::where('published', true)->count(),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.books.form', [
            'book' => new Book(['cover_style' => 'forest', 'published' => true, 'pages' => Book::DEFAULT_PAGES, 'year' => now()->year, 'topics' => []]),
        ]);
    }

    public function store(BookRequest $request): RedirectResponse
    {
        $book = new Book($this->attributes($request));
        $book->position ??= (int) Book::max('position') + 1;
        $book->save();

        return redirect()->route('admin.books.index')->with('status', "“{$book->title}” was added.");
    }

    public function edit(Book $book): View
    {
        return view('admin.books.form', compact('book'));
    }

    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $book->update($this->attributes($request, $book));

        return redirect()->route('admin.books.index')->with('status', "“{$book->title}” was updated.");
    }

    /** Show or hide a book on the website without opening the edit form. */
    public function toggleVisibility(Book $book): RedirectResponse
    {
        $book->update(['published' => ! $book->published]);

        $state = $book->published ? 'is now visible on the website' : 'is now hidden from the website';

        return back()->with('status', "“{$book->title}” {$state}.");
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('status', "“{$book->title}” was deleted.");
    }

    /** Validated input mapped to model attributes, with the cover upload handled. */
    private function attributes(BookRequest $request, ?Book $book = null): array
    {
        $data = $request->safe()->except(['cover', 'remove_cover']);
        $data['position'] = $data['position'] ?? $book?->position;

        $replacing = $request->hasFile('cover') || $request->boolean('remove_cover');

        if ($replacing && $book?->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
            $data['cover_path'] = null;
        }

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        return $data;
    }
}
