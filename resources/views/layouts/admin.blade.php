<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ isset($title) ? $title.' · ' : '' }}Admin · {{ config('site.name') }}</title>

    @include('partials.favicons')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600&family=Bodoni+Moda:opsz,wght@6..96,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @auth
        <header class="admin-bar">
            <a href="{{ route('admin.books.index') }}" class="admin-brand">Baeze <span>Admin</span></a>
            <nav class="admin-nav" aria-label="Admin">
                <a href="{{ route('admin.books.index') }}" @class(['active' => request()->routeIs('admin.books.*')])>Books</a>
                @can('manage-users')
                    <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Users</a>
                @endcan
                <a href="{{ route('admin.account') }}" @class(['active' => request()->routeIs('admin.account')])>My account</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener">View site ↗</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="link-button">Log out</button>
                </form>
            </nav>
        </header>
    @endauth

    <main class="admin-main">
        @if (session('status'))
            <p class="flash" role="status">{{ session('status') }}</p>
        @endif

        @yield('content')
    </main>
</body>
</html>
