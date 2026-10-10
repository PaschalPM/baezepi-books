<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $book = $this->route('book');

        return [
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160', 'alpha_dash', Rule::unique('books', 'slug')->ignore($book?->id)],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:80'],
            'year' => ['nullable', 'integer', 'between:1900,2100'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'question' => ['nullable', 'string', 'max:255'],
            'pages' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999', 'decimal:0,2'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_cover' => ['boolean'],
            'cover_style' => ['required', Rule::in(Book::COVER_STYLES)],
            'topics' => ['array'],
            'topics.*' => [Rule::in(array_keys(config('site.topics')))],
            'featured' => ['boolean'],
            'published' => ['boolean'],
            'position' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'buy_links' => ['array', 'max:12'],
            'buy_links.*.label' => ['required', 'string', 'max:60'],
            'buy_links.*.url' => ['required', 'url:http,https', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'buy_links.*.label' => 'store name',
            'buy_links.*.url' => 'link',
        ];
    }

    /** Unchecked checkboxes are absent from the request; treat them as false. */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'featured' => $this->boolean('featured'),
            'published' => $this->boolean('published'),
            'remove_cover' => $this->boolean('remove_cover'),
            'topics' => $this->input('topics', []),
            'buy_links' => $this->buyLinks(),
        ]);
    }

    /** Buy-link rows with both fields empty are ignored; everything else is trimmed and kept in order. */
    private function buyLinks(): array
    {
        return collect($this->input('buy_links', []))
            ->map(fn ($link) => [
                'label' => trim((string) ($link['label'] ?? '')),
                'url' => trim((string) ($link['url'] ?? '')),
            ])
            ->reject(fn ($link) => $link['label'] === '' && $link['url'] === '')
            ->values()
            ->all();
    }
}
