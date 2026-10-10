<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Admin logins are not seeded; create one with `php artisan admin:user you@example.com`.
     */
    public function run(): void
    {
        $this->call(BookSeeder::class);
    }
}
