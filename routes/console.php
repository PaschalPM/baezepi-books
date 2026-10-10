<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:user {email} {--name=Admin} {--password=} {--role=admin}', function (string $email) {
    if (! array_key_exists($this->option('role'), User::ROLES)) {
        $this->error('Role must be one of: '.implode(', ', array_keys(User::ROLES)));

        return 1;
    }

    $password = $this->option('password') ?: Str::password(16, symbols: false);

    $user = User::updateOrCreate(
        ['email' => $email],
        ['name' => $this->option('name'), 'role' => $this->option('role'), 'password' => $password],
    );

    $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated')." {$user->role} login for {$email}.");

    if (! $this->option('password')) {
        $this->line("Password: {$password}");
    }
})->purpose('Create or reset a login for /admin (role: admin or editor)');
