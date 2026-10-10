@extends('layouts.admin', ['title' => 'Books'])

@section('content')
    <div class="page-head">
        <div>
            <h1>Books</h1>
            <p class="muted">{{ $total }} {{ Str::plural('book', $total) }} · {{ $live }} on the website</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">+ Add a book</a>
    </div>

    @if ($total > 0)
        <form method="GET" action="{{ route('admin.books.index') }}" class="search-bar" role="search">
            <label for="book-search" class="sr-only">Search books by title</label>
            <input type="search" id="book-search" name="q" value="{{ $search }}" placeholder="Search by title…">
            <button type="submit" class="btn btn-secondary">Search</button>
            @if ($search !== '')
                <a href="{{ route('admin.books.index') }}">Clear</a>
            @endif
        </form>
    @endif

    @if ($total > 0 && $books->isEmpty())
        <div class="empty">
            <p>No books match “{{ $search }}”.</p>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Show all books</a>
        </div>
    @elseif ($books->isEmpty())
        <div class="empty">
            <p>No books yet.</p>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Add your first book</a>
        </div>
    @else
        <div class="table-wrap">
            <table class="book-table">
                <thead>
                    <tr>
                        <th scope="col" class="num">#</th>
                        <th scope="col">Book</th>
                        <th scope="col">Genre / Year</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td class="num">{{ $book->position }}</td>
                            <td>
                                <div class="book-cell">
                                    <div class="thumb">@include('partials.cover', ['book' => $book])</div>
                                    <div>
                                        <a href="{{ route('admin.books.edit', $book) }}" class="book-title">{{ $book->title }}</a>
                                        @if ($book->subtitle)
                                            <p class="muted small">{{ $book->subtitle }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ collect([$book->genre, $book->year])->filter()->join(' / ') ?: '—' }}</td>
                            <td>
                                <span @class(['badge', 'badge-live' => $book->published])>{{ $book->published ? 'Published' : 'Draft' }}</span>
                                @if ($book->featured)
                                    <span class="badge badge-featured">Featured</span>
                                @endif
                            </td>
                            <td class="actions">
                                @if ($book->published)
                                    <a href="{{ route('books.show', $book) }}" target="_blank" rel="noopener">View</a>
                                @endif
                                <form method="POST" action="{{ route('admin.books.visibility', $book) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="link-button">{{ $book->published ? 'Hide' : 'Show' }}</button>
                                </form>
                                <a href="{{ route('admin.books.edit', $book) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Delete “{{ addslashes($book->title) }}”? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-button danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $books->onEachSide(1)->links('partials.pagination') }}
        <p class="muted small">Books appear on the website in the order of the # column (lowest first). Featured books are shown on the homepage. Use Hide / Show to take a book off the website or put it back.</p>
    @endif
@endsection
