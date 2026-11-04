@if (auth()->user()?->isAdmin())
    <aside class="admin-panel" aria-label="Admin">
        <span>Signed in as {{ auth()->user()->name }}</span>
        <a href="{{ route('admin.home') }}">Go to admin</a>
    </aside>
@endif
