<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class BookSeeder extends Seeder
{
    /**
     * The published catalogue. Covers are kept in database/seeders/covers and copied to the
     * public disk, because uploaded files in storage/ are not part of a deploy.
     *
     * Books that already exist (matched by slug) are left alone, so edits made in /admin
     * survive a re-seed. Delete a book in /admin and re-run the seeder to restore it.
     */
    public function run(): void
    {
        // A migration runs this seeder before later migrations add columns (e.g. price), so on
        // a fresh database only write the columns that exist yet; those migrations fill the rest.
        $columns = Schema::getColumnListing('books');

        foreach ($this->books() as $position => $book) {
            if (Book::where('slug', $book['slug'])->exists()) {
                continue;
            }

            Book::create(Arr::only($book + [
                'cover_path' => $this->cover($book['slug']),
                'published' => true,
                'position' => $position + 1,
            ], $columns));
        }
    }

    /** Copy the bundled cover onto the public disk and return its stored path. */
    private function cover(string $slug): ?string
    {
        $source = database_path("seeders/covers/$slug.webp");
        $path = "covers/$slug.webp";

        if (! is_file($source)) {
            return null;
        }

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }

        return $path;
    }

    private function books(): array
    {
        return [
            [
                'slug' => 'blurred-lines',
                'title' => 'Blurred Lines',
                'subtitle' => 'Decoding the Patterns of Cultural Identity',
                'genre' => 'Cultural Studies',
                'year' => 2024,
                'summary' => 'An incisive exploration of the intricate weave of global traditions, and how identity is shaped, distorted and reborn across borders.',
                'question' => 'How do the stories around us shape who we become?',
                'price' => 9.99,
                'cover_style' => 'clay',
                'topics' => ['identity', 'creativity'],
                'featured' => true,
            ],
            [
                'slug' => 'boosting-the-size-of-sale',
                'title' => 'Boosting the Size of Sale',
                'subtitle' => 'An in-depth look at the qualities of a good influencer',
                'genre' => 'Business',
                'year' => 2024,
                'summary' => 'A practical guide to modern persuasion: what makes a voice trusted, a message sticky, and a sale inevitable in the influencer economy.',
                'question' => 'What makes influence worth listening to?',
                'price' => 12.99,
                'cover_style' => 'ochre',
                'topics' => ['influence', 'leadership', 'success', 'ambition'],
                'featured' => true,
            ],
            [
                'slug' => 'ceos-of-tiktok',
                'title' => 'CEOs of TikTok',
                'subtitle' => 'Build New Paradigms of Life and Tech',
                'genre' => 'Technology',
                'year' => 2025,
                'summary' => 'How a new generation of founders is rewriting the rules of leadership, attention, and creative power in a vertical-video world.',
                'question' => 'How is technology changing who gets to lead?',
                'price' => 14.99,
                'cover_style' => 'ink',
                'topics' => ['identity', 'influence', 'leadership', 'creativity', 'ambition'],
                'featured' => true,
            ],
            [
                'slug' => 'iq-an-overrated-quotient',
                'title' => 'IQ: An Overrated Quotient',
                'subtitle' => 'Where creativity meets the technical',
                'genre' => 'Essays',
                'year' => 2025,
                'summary' => 'On the combination of creative and technical thinking: the real foundation of a project, an idea, a life worth building.',
                'question' => 'What turns a good idea into useful work?',
                'price' => 11.99,
                'cover_style' => 'paper',
                'topics' => ['identity', 'influence', 'success', 'creativity'],
                'featured' => false,
            ],
            [
                'slug' => 'the-dogged-spirit-of-success',
                'title' => 'The Dogged Spirit of Success',
                'subtitle' => 'Persistence, purpose, and the path to lasting achievement',
                'genre' => 'Self-Development',
                'year' => 2025,
                'summary' => 'A reflection on resilience, discipline, and the mindset required to turn ambition into enduring success.',
                'question' => 'What helps ambition become enduring work?',
                'price' => 10.99,
                'cover_style' => 'forest',
                'topics' => ['leadership', 'success', 'ambition'],
                'featured' => false,
            ],
        ];
    }
}
