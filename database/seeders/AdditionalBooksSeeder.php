<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Ten more titles, added after the books already on the shelf. Run with:
 *
 *     php artisan db:seed --class=AdditionalBooksSeeder
 *
 * Like BookSeeder, books that already exist (matched by slug) are left alone, so edits made in /admin
 * survive a re-run. Price and opening question are not known yet; fill them in from /admin.
 * Drop a cover into database/seeders/covers/{slug}.webp before seeding to use it instead of the drawn cover.
 */
class AdditionalBooksSeeder extends Seeder
{
    public function run(): void
    {
        $position = (int) Book::max('position');

        foreach ($this->books() as $book) {
            if (Book::where('slug', $book['slug'])->exists()) {
                continue;
            }

            Book::create($book + [
                'year' => 2026,
                'cover_path' => $this->cover($book['slug']),
                'published' => true,
                'position' => ++$position,
            ]);
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
                'slug' => 'thankfulness',
                'title' => 'Thankfulness',
                'subtitle' => 'The quiet power of everyday gratitude',
                'genre' => 'Gratitude / mindfulness',
                'pages' => 159,
                'summary' => 'A warm, practical guide to the transforming power of gratitude. Patrick Okeke shows how living in the present, small acts of kindness and mindful reflection can help you face challenges, strengthen relationships, accept imperfection and find joy in life\'s simple moments.',
                'cover_style' => 'ochre',
                'topics' => ['identity'],
                'featured' => false,
            ],
            [
                'slug' => 'that-day-we-discovered-fire',
                'title' => 'That Day We Discovered Fire',
                'subtitle' => 'Igniting the creative spirit within us all',
                'genre' => 'Creativity',
                'pages' => 158,
                'summary' => 'A celebration of creativity and the people who quietly change the world. Patrick Okeke explores how to spark your creative fire, collaborate with others, overcome fear of judgment and use practical strategies and real breakthroughs to unleash the creator within you.',
                'cover_style' => 'clay',
                'topics' => ['creativity'],
                'featured' => false,
            ],
            [
                'slug' => 'the-conscious-endeavor',
                'title' => 'The Conscious Endeavor',
                'subtitle' => 'Living with intention, purpose and peace',
                'genre' => 'Mindfulness / spirituality',
                'pages' => 156,
                'summary' => 'A guide to living with intention and self-awareness. Patrick Okeke shows how to align your actions with your purpose, build inner peace and resilience through practices like meditation, gratitude and empathy, nurture healthy relationships and create a deeply fulfilling life.',
                'cover_style' => 'forest',
                'topics' => ['identity', 'success'],
                'featured' => false,
            ],
            [
                'slug' => 'the-delay-of-gratification',
                'title' => 'The Delay of Gratification',
                'subtitle' => 'Patience, focus and the art of long-term fulfillment',
                'genre' => 'Self-discipline / goal-setting',
                'pages' => 155,
                'summary' => 'Through Anya\'s journey, Patrick Okeke reveals how patience and focus lead to lasting success. Learn to set meaningful goals, manage your time, stay mindful, bounce back from setbacks and trade quick pleasures for long-term fulfilment, with practical tools and reflective exercises.',
                'cover_style' => 'ink',
                'topics' => ['success', 'ambition'],
                'featured' => false,
            ],
            [
                'slug' => 'the-indelible-mind',
                'title' => 'The Indelible Mind',
                'subtitle' => 'Master new skills, overcome setbacks and grow for life',
                'genre' => 'Learning and skill development',
                'pages' => 155,
                'summary' => 'An empowering guide to lifelong learning. Drawing on the science of skill acquisition and neuroplasticity, Patrick Okeke shows how inner drive, mentorship, resilience and practical strategies can help you master new skills, keep growing and unlock your full potential.',
                'cover_style' => 'paper',
                'topics' => ['success', 'creativity'],
                'featured' => false,
            ],
            [
                'slug' => 'the-influence-of-good-deeds',
                'title' => 'The Influence of Good Deeds',
                'subtitle' => 'How small acts of kindness create a lasting legacy',
                'genre' => 'Purpose / service',
                'pages' => 153,
                'summary' => 'An inspiring look at how kindness and service shape lives and communities. Patrick Okeke challenges the illusion of material success and shows how contribution, living by your values and selfless service can bring inner peace and leave a lasting legacy.',
                'cover_style' => 'ochre',
                'topics' => ['influence', 'leadership'],
                'featured' => false,
            ],
            [
                'slug' => 'the-making-of-an-office',
                'title' => 'The Making of an Office',
                'subtitle' => 'Your Complete Guide to Career Success',
                'genre' => 'Career development',
                'pages' => 383,
                'summary' => 'A complete career guide from first job search to promotion. Patrick Okeke covers finding your direction, CVs and cover letters, interviews, salary negotiation, onboarding, networking, workplace dynamics and advancement strategies to help you grow with confidence at every stage.',
                'cover_style' => 'forest',
                'topics' => ['leadership', 'success', 'ambition'],
                'featured' => false,
            ],
            [
                'slug' => 'the-road-from-the-eye-to-the-heart',
                'title' => 'The Road from the Eye to the Heart',
                'subtitle' => 'Mindfulness, Stoic wisdom and a life of purpose',
                'genre' => 'Mindfulness / self-awareness',
                'pages' => 159,
                'summary' => 'A thoughtful guide that blends mindfulness and Stoic wisdom. Patrick Okeke shows how to live in the present, recognise your biases, let go of the past, face reality honestly and build resilience, compassion and a purposeful future.',
                'cover_style' => 'clay',
                'topics' => ['identity'],
                'featured' => false,
            ],
            [
                'slug' => 'vital-questions',
                'title' => 'Vital Questions',
                'subtitle' => 'The art of writing proposals that win clients and build lasting partnerships',
                'genre' => 'Proposal writing / business communication',
                'pages' => 153,
                'summary' => 'A practical guide to writing proposals that win. Patrick Okeke shows how to understand your audience, set SMART objectives, structure for impact, persuade with clarity and refine your work, with case studies and templates for success across industries.',
                'cover_style' => 'ink',
                'topics' => ['influence', 'leadership'],
                'featured' => false,
            ],
            [
                'slug' => 'what-do-we-know-about-failure',
                'title' => 'What Do We Know About Failure?',
                'subtitle' => 'Turning setbacks into growth and resilience',
                'genre' => 'Resilience',
                'pages' => 159,
                'summary' => 'What if true failure is refusing to rise again? Through the entrepreneurial journeys of Elias and Anya, Patrick Okeke shows how resilience, perseverance and a positive mindset turn setbacks into opportunities and lasting success.',
                'cover_style' => 'paper',
                'topics' => ['success', 'ambition'],
                'featured' => false,
            ],
        ];
    }
}
