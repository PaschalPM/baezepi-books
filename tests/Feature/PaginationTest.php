<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    private function books(int $count): void
    {
        foreach (range(1, $count) as $i) {
            Book::create(['title' => sprintf('Book %03d', $i), 'cover_style' => 'ink', 'position' => $i]);
        }
    }

    public function test_the_books_page_shows_twelve_books_a_page(): void
    {
        $this->books(30);

        $this->get('/books')->assertOk()
            ->assertSee('Book 012')->assertDontSee('Book 013')
            ->assertSee('/books?page=2', false);

        // Numbering continues across pages.
        $this->get('/books?page=2')->assertOk()
            ->assertSee('Book 013')->assertSee('<div class="book-index">13</div>', false)
            ->assertDontSee('Book 012');

        $this->get('/books?page=3')->assertOk()->assertSee('Book 030');
    }

    public function test_search_is_kept_when_changing_pages(): void
    {
        $this->books(30);

        $this->get('/books?q=book')->assertOk()
            ->assertSee('30 books matching', false)
            ->assertSee('/books?q=book&amp;page=2', false);
    }

    public function test_the_admin_list_shows_twenty_books_a_page(): void
    {
        $this->books(45);
        $this->actingAs(User::factory()->create());

        $this->get('/admin/books')->assertOk()
            ->assertSee('45 books')
            ->assertSee('Book 020')->assertDontSee('Book 021');

        $this->get('/admin/books?page=3')->assertOk()->assertSee('Book 045')->assertDontSee('Book 040');
    }

    public function test_no_page_links_when_everything_fits(): void
    {
        $this->books(5);

        $this->get('/books')->assertOk()->assertDontSee('class="pagination"', false);
    }
}
