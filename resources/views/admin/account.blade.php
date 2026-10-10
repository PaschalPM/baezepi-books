@extends('layouts.admin', ['title' => 'My account'])

@section('content')
    <div class="page-head">
        <div>
            <h1>My account</h1>
            <p class="muted">{{ auth()->user()->name }} · {{ auth()->user()->email }} · {{ \App\Models\User::ROLES[auth()->user()->role] ?? '' }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.account.update') }}" class="panel stack narrow">
        @csrf
        @method('PUT')
        <h2>Change password</h2>

        <label class="field">
            <span>Current password</span>
            <input type="password" name="current_password" autocomplete="current-password" required>
            @error('current_password') <small class="error">{{ $message }}</small> @enderror
        </label>

        <label class="field">
            <span>New password</span>
            <input type="password" name="password" autocomplete="new-password" minlength="8" required>
            <small class="hint">At least 8 characters.</small>
            @error('password') <small class="error">{{ $message }}</small> @enderror
        </label>

        <label class="field">
            <span>Confirm new password</span>
            <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
        </label>

        <button type="submit" class="btn btn-primary">Change password</button>
    </form>
@endsection
