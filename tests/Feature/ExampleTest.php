<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The homepage renders, with or without books in the database.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')->assertOk();

        $this->seed();

        $this->get('/')->assertOk()->assertSee('Blurred Lines');
    }

    public function test_missing_pages_show_the_branded_404(): void
    {
        foreach (['/no-such-page', '/books/no-such-book', '/blog/no-such-note'] as $url) {
            $this->get($url)->assertNotFound()
                ->assertSee('This page has wandered off the shelf.')
                ->assertSee('Browse books');
        }
    }
}
