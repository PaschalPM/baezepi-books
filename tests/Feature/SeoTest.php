<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function book(array $overrides = []): Book
    {
        return Book::create($overrides + [
            'title' => 'Signal & Noise',
            'subtitle' => 'Notes on attention',
            'summary' => 'A field guide to how ideas travel online.',
            'genre' => 'Nonfiction',
            'year' => 2025,
            'pages' => 224,
            'cover_style' => 'forest',
            'topics' => ['influence'],
            'buy_links' => [['label' => 'Selar', 'url' => 'https://selar.com/signal']],
        ]);
    }

    public function test_book_pages_have_meta_tags_and_book_structured_data(): void
    {
        $book = $this->book();

        $html = $this->get('/books/signal-and-noise')->assertOk()->getContent();

        $this->assertStringContainsString('<title>Signal &amp; Noise · '.config('site.name').'</title>', $html);
        $this->assertStringContainsString('<meta name="description" content="A field guide to how ideas travel online.">', $html);
        $this->assertStringContainsString('<link rel="canonical" href="'.url('/books/signal-and-noise').'">', $html);
        $this->assertStringContainsString('<meta property="og:type" content="book">', $html);
        $this->assertStringContainsString('<meta name="robots" content="index, follow, max-image-preview:large">', $html);

        $data = $this->structuredData($html);
        $bookData = collect($data)->firstWhere('@type', 'Book');
        $this->assertSame('Signal & Noise', $bookData['name']);
        $this->assertSame('2025', $bookData['datePublished']);
        $this->assertSame(224, $bookData['numberOfPages']);
        $this->assertSame(['Influence'], $bookData['about']);
        $this->assertSame('Selar', $bookData['offers'][0]['seller']['name']);
        $this->assertArrayNotHasKey('image', $bookData, 'no cover uploaded, so no image key');

        $crumbs = collect($data)->firstWhere('@type', 'BreadcrumbList');
        $this->assertSame(['Home', 'The Books', 'Signal & Noise'], array_column($crumbs['itemListElement'], 'name'));
    }

    public function test_seo_overrides_replace_the_title_and_description(): void
    {
        $this->book(['seo_title' => 'Signal & Noise: a book about attention', 'seo_description' => 'Custom search text.']);

        $this->get('/books/signal-and-noise')
            ->assertSee('<title>Signal &amp; Noise: a book about attention · '.config('site.name').'</title>', false)
            ->assertSee('<meta name="description" content="Custom search text.">', false);
    }

    public function test_every_public_page_has_a_unique_title_and_a_description(): void
    {
        $this->book();
        $titles = [];

        foreach (['/', '/books', '/books/signal-and-noise', '/influence-books', '/blog', '/blog/creator-practice', '/blog/creator-practice/how-to-start-writing', '/about', '/contact', '/reading-and-publishing-statistics', '/privacy', '/terms', '/cookie-policy'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            preg_match('~<title>(.*?)</title>~', $html, $title);
            preg_match('~<meta name="description" content="(.*?)">~', $html, $description);

            $this->assertNotEmpty($title[1] ?? null, "$url has a title");
            $this->assertNotEmpty($description[1] ?? null, "$url has a description");
            $this->assertLessThanOrEqual(161, mb_strlen(html_entity_decode($description[1])), "$url description fits in search results");
            $this->assertNotContains($title[1], $titles, "$url title is unique");
            $titles[] = $title[1];

            foreach ($this->structuredData($html) as $block) {
                $this->assertSame('https://schema.org', $block['@context'] ?? null, "$url structured data is valid JSON-LD");
            }
        }
    }

    public function test_404_pages_are_not_indexed(): void
    {
        $this->get('/nowhere')->assertNotFound()->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_sitemap_lists_published_books_but_not_drafts(): void
    {
        $this->book();
        Book::create(['title' => 'Hidden Draft', 'cover_style' => 'ink', 'published' => false]);

        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $xml = simplexml_load_string($response->getContent());

        $locs = collect($xml->xpath('//*[local-name()="loc"]'))->map(fn ($loc) => (string) $loc);
        $this->assertGreaterThan(10, $locs->count());
        $this->assertContains(url('/books/signal-and-noise'), $locs);
        $this->assertContains(url('/influence-books'), $locs);
        $this->assertContains(url('/blog/creator-practice'), $locs);
        $this->assertContains(url('/blog/creator-practice/how-to-start-writing'), $locs);
        $this->assertNotContains(url('/books/hidden-draft'), $locs);
        $this->assertFalse($locs->contains(fn ($loc) => str_contains($loc, '/admin')));
    }

    public function test_robots_txt_blocks_admin_in_production_and_everything_elsewhere(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee("Disallow: /\n", false);

        $this->app['env'] = 'production';
        $this->get('/robots.txt')->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.url('/sitemap.xml'));
    }

    public function test_admin_can_save_seo_fields_within_limits(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/books', [
            'title' => 'Too Long',
            'cover_style' => 'forest',
            'seo_title' => str_repeat('a', 71),
            'seo_description' => str_repeat('b', 161),
        ])->assertSessionHasErrors(['seo_title', 'seo_description']);

        $this->actingAs($user)->post('/admin/books', [
            'title' => 'Just Right',
            'cover_style' => 'forest',
            'seo_title' => 'A good search title',
            'seo_description' => 'A good search description.',
        ])->assertRedirect('/admin/books');

        $this->assertSame('A good search title', Book::sole()->seo_title);
        $this->get('/admin/books/just-right/edit')->assertOk()->assertSee('Search &amp; sharing', false);
    }

    /** All JSON-LD blocks on a page, decoded. */
    private function structuredData(string $html): array
    {
        preg_match_all('~<script type="application/ld\+json">(.*?)</script>~s', $html, $matches);

        return array_map(fn ($json) => json_decode($json, true, flags: JSON_THROW_ON_ERROR), $matches[1]);
    }
}
