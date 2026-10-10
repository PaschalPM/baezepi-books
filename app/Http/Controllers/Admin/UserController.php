<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::orderByRaw("role = 'admin' desc")->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User(['role' => 'editor'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create($data);

        return redirect()->route('admin.users.index')
            ->with('status', "{$user->name} can now log in as ".strtolower(User::ROLES[$user->role]).'.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if ($data['role'] !== 'admin' && $user->isLastAdmin()) {
            throw ValidationException::withMessages(['role' => 'There must always be at least one admin.']);
        }

        if (blank($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', "{$user->name} was updated.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('status', 'You can’t delete your own login.');
        }

        if ($user->isLastAdmin()) {
            return back()->with('status', 'There must always be at least one admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', "{$user->name} can no longer log in.");
    }
}
