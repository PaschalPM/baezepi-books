@extends('layouts.admin', ['title' => 'Users'])

@section('content')
    <div class="page-head">
        <div>
            <h1>Users</h1>
            <p class="muted">People who can log in to this dashboard.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add a user</a>
    </div>

    <div class="table-wrap">
        <table class="book-table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="book-title">{{ $user->name }}</a>
                            @if ($user->is(auth()->user()))
                                <span class="muted small">(you)</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td><span @class(['badge', 'badge-featured' => $user->isAdmin()])>{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            @unless ($user->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Remove {{ addslashes($user->name) }}? They will no longer be able to log in.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-button danger">Remove</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <dl class="role-help muted small">
        <div><dt>Admin</dt><dd>Adds, edits and deletes books, and manages users.</dd></div>
        <div><dt>Editor</dt><dd>Adds, edits and deletes books.</dd></div>
    </dl>
@endsection
