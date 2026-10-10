<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBooksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/admin/books')->assertRedirect('/admin/login');
        $this->post('/admin/books', ['title' => 'Nope'])->assertRedirect('/admin/login');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_admin_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret-password'])
            ->assertRedirect('/admin/books');
        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_admin_can_add_a_book_with_a_cover_and_it_appears_on_the_site(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->post('/admin/books', [
                'title' => 'The Patient Hour',
                'subtitle' => 'On waiting well',
                'genre' => 'Essays',
                'year' => 2026,
                'summary' => 'A short book about patience.',
                'question' => 'What is waiting for?',
                'pages' => 140,
                'cover' => UploadedFile::fake()->image('cover.jpg', 600, 900),
                'cover_style' => 'ochre',
                'topics' => ['success', 'creativity'],
                'featured' => '1',
                'published' => '1',
            ])
            ->assertRedirect('/admin/books');

        $book = Book::sole();
        $this->assertSame('the-patient-hour', $book->slug);
        $this->assertSame(['success', 'creativity'], $book->topics);
        $this->assertTrue($book->featured);
        Storage::disk('public')->assertExists($book->cover_path);

        $this->get('/books')->assertOk()->assertSee('The Patient Hour');
        $this->get('/books/the-patient-hour')->assertOk()->assertSee('What is waiting for?')->assertSee($book->cover_url);
        $this->get('/')->assertOk()->assertSee('The Patient Hour');
        $this->get('/books-about-success')->assertOk()->assertSee('The Patient Hour');
        $this->get('/books-about-leadership')->assertOk()->assertDontSee('The Patient Hour');
    }

    public function test_drafts_stay_off_the_site(): void
    {
        Book::create(['title' => 'Secret Draft', 'cover_style' => 'forest', 'published' => false]);

        $this->get('/books')->assertOk()->assertDontSee('Secret Draft');
        $this->get('/books/secret-draft')->assertNotFound();

        $this->actingAs(User::factory()->create())
            ->get('/admin/books')->assertOk()->assertSee('Secret Draft')->assertSee('Draft');
    }

    public function test_admin_can_edit_a_book_and_replace_its_cover(): void
    {
        Storage::fake('public');
        $old = UploadedFile::fake()->image('old.jpg')->store('covers', 'public');
        $book = Book::create(['title' => 'Old Title', 'cover_style' => 'forest', 'cover_path' => $old]);

        $this->actingAs(User::factory()->create())
            ->put("/admin/books/{$book->slug}", [
                'title' => 'New Title',
                'slug' => 'new-title',
                'cover' => UploadedFile::fake()->image('new.png'),
                'cover_style' => 'clay',
            ])
            ->assertRedirect('/admin/books');

        $book->refresh();
        $this->assertSame('New Title', $book->title);
        $this->assertSame('new-title', $book->slug);
        $this->assertFalse($book->published);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($book->cover_path);
    }

    public function test_admin_can_delete_a_book(): void
    {
        Storage::fake('public');
        $cover = UploadedFile::fake()->image('c.jpg')->store('covers', 'public');
        $book = Book::create(['title' => 'Gone Soon', 'cover_style' => 'forest', 'cover_path' => $cover]);

        $this->actingAs(User::factory()->create())
            ->delete("/admin/books/{$book->slug}")
            ->assertRedirect('/admin/books');

        $this->assertModelMissing($book);
        Storage::disk('public')->assertMissing($cover);
    }

    public function test_validation_rejects_bad_input(): void
    {
        Book::create(['title' => 'Taken', 'cover_style' => 'forest']);

        $this->actingAs(User::factory()->create())
            ->post('/admin/books', [
                'title' => '',
                'slug' => 'taken',
                'cover_style' => 'neon',
                'topics' => ['not-a-theme'],
                'cover' => UploadedFile::fake()->create('cover.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors(['title', 'slug', 'cover_style', 'topics.0', 'cover']);

        $this->assertDatabaseCount('books', 1);
    }

    public function test_slugs_are_unique(): void
    {
        $first = Book::create(['title' => 'Same Name', 'cover_style' => 'forest']);
        $second = Book::create(['title' => 'Same Name', 'cover_style' => 'forest']);

        $this->assertSame('same-name', $first->slug);
        $this->assertSame('same-name-2', $second->slug);
    }

    public function test_buy_links_are_saved_in_order_and_shown_on_the_book_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/books', [
                'title' => 'Buyable',
                'cover_style' => 'forest',
                'published' => '1',
                'buy_links' => [
                    ['label' => ' Bookshop.org ', 'url' => 'https://bookshop.org/buyable'],
                    ['label' => '', 'url' => ''],
                    ['label' => 'Amazon', 'url' => 'https://www.amazon.com/dp/123'],
                ],
            ])
            ->assertRedirect('/admin/books');

        $book = Book::sole();
        $this->assertSame([
            ['label' => 'Bookshop.org', 'url' => 'https://bookshop.org/buyable'],
            ['label' => 'Amazon', 'url' => 'https://www.amazon.com/dp/123'],
        ], $book->buy_links);

        $this->get('/books/buyable')->assertOk()
            ->assertSeeInOrder(['Buy the book', 'https://bookshop.org/buyable', 'Bookshop.org', 'https://www.amazon.com/dp/123', 'Amazon'])
            ->assertDontSee('Get a copy');

        $this->get("/admin/books/{$book->slug}/edit")->assertOk()
            ->assertSee('value="https://www.amazon.com/dp/123"', false);
    }

    public function test_books_without_buy_links_fall_back_to_the_checkout_button(): void
    {
        Book::create(['title' => 'No Links', 'cover_style' => 'forest']);

        $this->get('/books/no-links')->assertOk()->assertSee('Purchase this book')->assertSee('/books/no-links/checkout', false)->assertDontSee('Buy the book');
    }

    public function test_buy_links_need_a_store_name_and_a_web_address(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/books', [
                'title' => 'Bad Links',
                'cover_style' => 'forest',
                'buy_links' => [
                    ['label' => 'Amazon', 'url' => 'not a url'],
                    ['label' => '', 'url' => 'https://example.com'],
                    ['label' => 'Sneaky', 'url' => 'javascript:alert(1)'],
                ],
            ])
            ->assertSessionHasErrors(['buy_links.0.url', 'buy_links.1.label', 'buy_links.2.url']);

        $this->assertDatabaseCount('books', 0);
    }

    public function test_admin_can_hide_and_show_a_book_from_the_list(): void
    {
        $book = Book::create(['title' => 'Quiet Book', 'cover_style' => 'forest', 'published' => true]);
        $this->actingAs(User::factory()->create());

        $this->from('/admin/books')->patch("/admin/books/{$book->slug}/visibility")->assertRedirect('/admin/books');
        $this->assertFalse($book->refresh()->published);
        $this->get('/books')->assertDontSee('Quiet Book');

        $this->patch("/admin/books/{$book->slug}/visibility");
        $this->assertTrue($book->refresh()->published);
        $this->get('/books')->assertSee('Quiet Book');
    }

    public function test_books_can_be_searched_by_title_on_the_site_and_in_the_admin(): void
    {
        Book::create(['title' => 'The Patient Hour', 'cover_style' => 'forest']);
        Book::create(['title' => 'Loud Rooms', 'cover_style' => 'ink']);
        Book::create(['title' => 'Patient Draft', 'cover_style' => 'ink', 'published' => false]);

        $this->get('/books?q=patient')->assertOk()
            ->assertSee('The Patient Hour')->assertDontSee('Loud Rooms')->assertDontSee('Patient Draft');

        $this->actingAs(User::factory()->create())
            ->get('/admin/books?q=patient')->assertOk()
            ->assertSee('The Patient Hour')->assertSee('Patient Draft')->assertDontSee('Loud Rooms');

        $this->get('/admin/books?q=nothing-here')->assertOk()->assertSee('No books match');
    }
}
