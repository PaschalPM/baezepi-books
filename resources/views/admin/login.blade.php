@extends('layouts.admin', ['title' => 'Log in'])

@section('content')
    <div class="login-card">
        <p class="admin-brand">Baeze <span>Admin</span></p>
        <h1>Log in</h1>

        <form method="POST" action="{{ route('login.store') }}" class="stack">
            @csrf

            <label class="field">
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            </label>

            <label class="field">
                <span>Password</span>
                <input type="password" name="password" autocomplete="current-password" required>
            </label>

            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label class="check">
                <input type="checkbox" name="remember" value="1"> Keep me logged in
            </label>

            <button type="submit" class="btn btn-primary">Log in</button>
        </form>
    </div>
@endsection
