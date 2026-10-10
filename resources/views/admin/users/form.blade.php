@extends('layouts.admin', ['title' => $user->exists ? 'Edit '.$user->name : 'Add a user'])

@section('content')
    <div class="page-head">
        <div>
            <a href="{{ route('admin.users.index') }}" class="back">⟵ All users</a>
            <h1>{{ $user->exists ? 'Edit user' : 'Add a user' }}</h1>
        </div>
    </div>

    <form method="POST" class="panel stack narrow"
          action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if ($user->exists)
            @method('PUT')
        @endif

        <label class="field">
            <span>Name</span>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="120">
            @error('name') <small class="error">{{ $message }}</small> @enderror
        </label>

        <label class="field">
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            <small class="hint">They log in with this address.</small>
            @error('email') <small class="error">{{ $message }}</small> @enderror
        </label>

        <fieldset class="field">
            <legend>Role</legend>
            <label class="check">
                <input type="radio" name="role" value="editor" @checked(old('role', $user->role) === 'editor')>
                <span><strong>Editor</strong><br><small class="hint">Can add, edit and delete books.</small></span>
            </label>
            <label class="check">
                <input type="radio" name="role" value="admin" @checked(old('role', $user->role) === 'admin')>
                <span><strong>Admin</strong><br><small class="hint">Can also add, edit and remove users.</small></span>
            </label>
            @error('role') <small class="error">{{ $message }}</small> @enderror
        </fieldset>

        <label class="field">
            <span>{{ $user->exists ? 'New password' : 'Password' }}</span>
            <input type="password" name="password" autocomplete="new-password" minlength="8" @required(! $user->exists)>
            <small class="hint">{{ $user->exists ? 'Leave blank to keep their current password.' : 'At least 8 characters. Share it with them privately.' }}</small>
            @error('password') <small class="error">{{ $message }}</small> @enderror
        </label>

        <label class="field">
            <span>Confirm password</span>
            <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" @required(! $user->exists)>
        </label>

        <button type="submit" class="btn btn-primary">{{ $user->exists ? 'Save changes' : 'Add user' }}</button>
    </form>
@endsection
