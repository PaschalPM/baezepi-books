<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_without_store_links_link_to_the_checkout_page(): void
    {
        Book::create(['title' => 'CEOs of TikTok', 'cover_style' => 'ink', 'price' => 14.99]);

        $this->get('/books/ceos-of-tiktok')->assertOk()
            ->assertSee('Purchase for $14.99')
            ->assertSee('/books/ceos-of-tiktok/checkout', false);

        $this->get('/books/ceos-of-tiktok/checkout')->assertOk()
            ->assertSee('Purchase information')
            ->assertSee('$14.99')
            ->assertSee('mailto:'.config('site.email').'?subject=Purchase%20inquiry%3A%20CEOs%20of%20TikTok', false)
            ->assertSee('noindex, follow', false);
    }

    public function test_books_with_store_links_use_them_instead(): void
    {
        Book::create([
            'title' => 'Linked Book', 'cover_style' => 'ink', 'price' => 9.99,
            'buy_links' => [['label' => 'Amazon', 'url' => 'https://amazon.com/linked']],
        ]);

        $this->get('/books/linked-book')->assertOk()
            ->assertSee('https://amazon.com/linked', false)
            ->assertDontSee('/books/linked-book/checkout', false);
    }

    public function test_hidden_books_have_no_checkout(): void
    {
        Book::create(['title' => 'Hidden', 'cover_style' => 'ink', 'published' => false]);

        $this->get('/books/hidden/checkout')->assertNotFound();
    }

    public function test_admin_can_set_a_price(): void
    {
        $book = Book::create(['title' => 'Priced', 'cover_style' => 'ink']);

        $this->actingAs(User::factory()->create())
            ->put("/admin/books/{$book->slug}", ['title' => 'Priced', 'cover_style' => 'ink', 'published' => '1', 'price' => '12.50'])
            ->assertRedirect('/admin/books');

        $this->assertSame('12.50', $book->refresh()->price);
        $this->get('/books')->assertSee('$12.50');
    }
}
