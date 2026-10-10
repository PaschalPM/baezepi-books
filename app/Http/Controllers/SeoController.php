<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SeoController extends Controller
{
    /** XML sitemap of every public page, so search engines can find new books quickly. */
    public function sitemap(): Response
    {
        $books = Book::shelf()->get();
        $booksUpdated = $books->max('updated_at');

        $urls = collect([
            ['loc' => route('home'), 'lastmod' => $booksUpdated, 'priority' => '1.0'],
            ['loc' => route('books.index'), 'lastmod' => $booksUpdated, 'priority' => '0.9'],
        ])
            ->concat($books->map(fn (Book $book) => [
                'loc' => route('books.show', $book),
                'lastmod' => $book->updated_at,
                'priority' => '0.8',
            ]))
            ->concat(collect(config('site.topics'))->keys()->map(fn ($key) => [
                'loc' => route("topics.$key"),
                'lastmod' => $booksUpdated,
                'priority' => '0.6',
            ]))
            ->push(['loc' => route('notes.index'), 'priority' => '0.6'])
            ->concat(collect(config('site.blog'))->flatMap(fn ($section, $category) => collect([[
                'loc' => route('notes.category', $category),
                'priority' => '0.6',
            ]])->concat(collect($section['posts'])->map(fn ($note, $slug) => [
                'loc' => route('notes.show', [$category, $slug]),
                'lastmod' => Carbon::parse($note['date']),
                'priority' => '0.5',
            ])->values())))
            ->concat(collect(['about', 'contact', 'stats'])->map(fn ($route) => [
                'loc' => route($route),
                'priority' => '0.5',
            ]))
            ->concat(collect(['privacy', 'terms', 'cookies'])->map(fn ($route) => [
                'loc' => route($route),
                'lastmod' => Carbon::parse(config('site.legal.updated')),
                'priority' => '0.2',
            ]));

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * robots.txt. Outside production every crawler is turned away, so staging
     * or local copies of the site never end up in search results.
     */
    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
