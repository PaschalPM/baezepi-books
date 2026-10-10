<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'slug', 'title', 'subtitle', 'genre', 'year', 'summary', 'question', 'pages', 'price', 'buy_links', 'seo_title', 'seo_description',
    'cover_path', 'cover_style', 'topics', 'featured', 'published', 'position',
])]
class Book extends Model
{
    /** Store names offered as suggestions in the admin; any name can be typed. */
    public const STORES = ['Amazon', 'Barnes & Noble', 'Bookshop.org', 'Apple Books', 'Google Play Books', 'Kobo', 'Audible', 'Waterstones', 'Selar'];

    /** Colour schemes for the CSS-drawn cover used when no image is uploaded. */
    public const COVER_STYLES = ['forest', 'ochre', 'ink', 'clay', 'paper'];

    /** Page count used when none is entered. */
    public const DEFAULT_PAGES = 100;

    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'buy_links' => 'array',
            'featured' => 'boolean',
            'published' => 'boolean',
            'year' => 'integer',
            'pages' => 'integer',
            'price' => 'decimal:2',
            'position' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Book $book) {
            $book->slug = static::uniqueSlug($book->slug ?: $book->title, $book->id);
            $book->pages ??= static::DEFAULT_PAGES;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Published books in display order. */
    public function scopeShelf(Builder $query): void
    {
        $query->where('published', true)->orderBy('position')->orderByDesc('year')->orderBy('title');
    }

    /** Books whose title contains the given text; a blank term matches everything. */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $term = trim((string) $term);

        $query->when($term !== '', fn ($q) => $q->where('title', 'like', '%'.$term.'%'));
    }

    /** Public URL of the uploaded cover, if any. */
    protected function coverUrl(): Attribute
    {
        // asset() follows the address the site is being viewed at, so the link works on any host or port.
        return Attribute::get(fn () => $this->cover_path ? asset('storage/'.$this->cover_path) : null);
    }

    /** The views read `$book['cover']` for the CSS cover palette. */
    protected function cover(): Attribute
    {
        return Attribute::get(fn () => $this->cover_style);
    }

    /** Title used in search results and social shares. */
    public function metaTitle(): string
    {
        return $this->seo_title ?: $this->title;
    }

    /** Description used in search results and social shares, at most ~160 characters. */
    public function metaDescription(): string
    {
        return Str::limit($this->seo_description ?: ($this->summary ?: (string) $this->subtitle), 160, '…');
    }

    /** Price for display, e.g. "$14.99", or null when no price is set. */
    public function formattedPrice(): ?string
    {
        return $this->price === null ? null : config('site.currency_symbol', '$').number_format((float) $this->price, 2);
    }

    public function inTopic(string $key): bool
    {
        return in_array($key, $this->topics ?? [], true);
    }

    protected static function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value, dictionary: ['@' => 'at', '&' => 'and']) ?: 'book';
        $slug = $base;

        for ($i = 2; static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists(); $i++) {
            $slug = "$base-$i";
        }

        return $slug;
    }
}
