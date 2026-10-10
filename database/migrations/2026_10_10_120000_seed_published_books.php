<?php

use App\Models\Book;
use Database\Seeders\BookSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

/**
 * Puts the published catalogue into every environment as part of `php artisan migrate`,
 * so a fresh deploy has the books without a separate seeding step.
 */
return new class extends Migration
{
    /** Slugs of the placeholder books the app originally shipped with. */
    private const PLACEHOLDERS = ['signal-and-noise', 'the-quiet-border', 'small-engines', 'the-long-room', 'second-language'];

    private const TITLES = ['Blurred Lines', 'Boosting the Size of Sale', 'CEOs of TikTok', 'IQ: An Overrated Quotient', 'The Dogged Spirit of Success'];

    public function up(): void
    {
        // Tests build their own data and expect an empty table.
        if (app()->runningUnitTests()) {
            return;
        }

        // Remove placeholders, keeping any that were edited into a different book.
        Book::whereIn('slug', self::PLACEHOLDERS)->get()
            ->filter(fn (Book $book) => $book->updated_at->equalTo($book->created_at) || in_array($book->title, self::TITLES, true))
            ->each(function (Book $book) {
                if ($book->cover_path) {
                    Storage::disk('public')->delete($book->cover_path);
                }
                $book->delete();
            });

        (new BookSeeder)->run();
    }

    public function down(): void
    {
        // Books may have been edited since; leave them in place.
    }
};
