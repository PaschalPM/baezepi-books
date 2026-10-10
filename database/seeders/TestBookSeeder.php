<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

/**
 * Throwaway books for trying out pagination locally. Not called by DatabaseSeeder or any migration.
 *
 *   php artisan db:seed --class=TestBookSeeder
 *   php artisan tinker --execute="App\Models\Book::where('slug', 'like', 'test-book-%')->delete()"
 */
class TestBookSeeder extends Seeder
{
    public function run(): void
    {
        $styles = Book::COVER_STYLES;
        $topics = array_keys(config('site.topics'));
        $start = (int) Book::max('position');

        foreach (range(1, 20) as $i) {
            Book::updateOrCreate(['slug' => "test-book-$i"], [
                'title' => "Test Book $i",
                'subtitle' => 'A placeholder for testing pagination',
                'genre' => 'Test',
                'year' => 2026,
                'summary' => 'Sample book added by TestBookSeeder. Delete it when you are done testing.',
                'question' => 'Does the next page work?',
                'price' => 9.99,
                'cover_style' => $styles[$i % count($styles)],
                'topics' => [$topics[$i % count($topics)]],
                'published' => true,
                'position' => $start + $i,
            ]);
        }
    }
}
