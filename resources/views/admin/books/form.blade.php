@extends('layouts.admin', ['title' => $book->exists ? 'Edit '.$book->title : 'Add a book'])

@section('content')
    <div class="page-head">
        <div>
            <a href="{{ route('admin.books.index') }}" class="back">⟵ All books</a>
            <h1>{{ $book->exists ? 'Edit book' : 'Add a book' }}</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="error-box" role="alert">
            <p>Please fix the highlighted fields.</p>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" class="book-form"
          action="{{ $book->exists ? route('admin.books.update', $book) : route('admin.books.store') }}">
        @csrf
        @if ($book->exists)
            @method('PUT')
        @endif

        <div class="stack">
        <div class="panel stack">
            <h2>Details</h2>

            <label class="field">
                <span>Title <em>required</em></span>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" required maxlength="160">
                @error('title') <small class="error">{{ $message }}</small> @enderror
            </label>

            <label class="field">
                <span>Subtitle</span>
                <input type="text" name="subtitle" value="{{ old('subtitle', $book->subtitle) }}" maxlength="255">
                @error('subtitle') <small class="error">{{ $message }}</small> @enderror
            </label>

            <div class="row row-4">
                <label class="field">
                    <span>Genre</span>
                    <input type="text" name="genre" value="{{ old('genre', $book->genre) }}" maxlength="80" placeholder="e.g. Nonfiction">
                    @error('genre') <small class="error">{{ $message }}</small> @enderror
                </label>
                <label class="field">
                    <span>Year</span>
                    <input type="number" name="year" value="{{ old('year', $book->year) }}" min="1900" max="2100">
                    @error('year') <small class="error">{{ $message }}</small> @enderror
                </label>
                <label class="field">
                    <span>Pages</span>
                    <input type="number" name="pages" value="{{ old('pages', $book->pages) }}" min="1">
                    @error('pages') <small class="error">{{ $message }}</small> @enderror
                </label>
                <label class="field">
                    <span>Price <em>USD</em></span>
                    <input type="number" name="price" value="{{ old('price', $book->price) }}" min="0" step="0.01" placeholder="e.g. 14.99">
                    @error('price') <small class="error">{{ $message }}</small> @enderror
                </label>
            </div>

            <label class="field">
                <span>Summary</span>
                <textarea name="summary" rows="5" maxlength="2000">{{ old('summary', $book->summary) }}</textarea>
                <small class="hint">Shown on the books list and the book’s own page.</small>
                @error('summary') <small class="error">{{ $message }}</small> @enderror
            </label>

            <label class="field">
                <span>The book’s question</span>
                <input type="text" name="question" value="{{ old('question', $book->question) }}" maxlength="255" placeholder="e.g. What comes after the first success?">
                <small class="hint">The one question the book answers. Shown beside it on the books list.</small>
                @error('question') <small class="error">{{ $message }}</small> @enderror
            </label>

            <label class="field">
                <span>Web address</span>
                <div class="prefixed">
                    <span>{{ url('/books') }}/</span>
                    <input type="text" name="slug" value="{{ old('slug', $book->slug) }}" maxlength="160" placeholder="made from the title">
                </div>
                <small class="hint">Leave blank to create it from the title.</small>
                @error('slug') <small class="error">{{ $message }}</small> @enderror
            </label>
        </div>

        @php($links = old('buy_links', $book->buy_links ?: [['label' => '', 'url' => '']]))
        <div class="panel stack">
            <div>
                <h2>Where to buy</h2>
                <p class="hint">Links shown as buttons on the book’s page. The first one is highlighted. Leave empty to show a “Get a copy” button that goes to the contact page.</p>
            </div>

            <div class="buy-rows" data-buy-rows data-next-index="{{ count($links) }}">
                @foreach ($links as $i => $link)
                    <div class="buy-row" data-buy-row>
                        <label class="field">
                            <span>Store</span>
                            <input type="text" name="buy_links[{{ $i }}][label]" value="{{ $link['label'] ?? '' }}" list="store-names" maxlength="60" placeholder="e.g. Amazon">
                            @error("buy_links.$i.label") <small class="error">{{ $message }}</small> @enderror
                        </label>
                        <label class="field">
                            <span>Link</span>
                            <input type="url" name="buy_links[{{ $i }}][url]" value="{{ $link['url'] ?? '' }}" maxlength="2048" placeholder="https://">
                            @error("buy_links.$i.url") <small class="error">{{ $message }}</small> @enderror
                        </label>
                        <button type="button" class="icon-button" data-remove-row aria-label="Remove this store">×</button>
                    </div>
                @endforeach
            </div>
            @error('buy_links') <small class="error">{{ $message }}</small> @enderror

            <button type="button" class="btn btn-secondary" data-add-row>+ Add another store</button>

            <datalist id="store-names">
                @foreach (\App\Models\Book::STORES as $store)
                    <option value="{{ $store }}">
                @endforeach
            </datalist>

            <template data-row-template>
                <div class="buy-row" data-buy-row>
                    <label class="field">
                        <span>Store</span>
                        <input type="text" name="buy_links[__i__][label]" list="store-names" maxlength="60" placeholder="e.g. Amazon">
                    </label>
                    <label class="field">
                        <span>Link</span>
                        <input type="url" name="buy_links[__i__][url]" maxlength="2048" placeholder="https://">
                    </label>
                    <button type="button" class="icon-button" data-remove-row aria-label="Remove this store">×</button>
                </div>
            </template>
        </div>

        <div class="panel stack" data-seo>
            <div>
                <h2>Search &amp; sharing</h2>
                <p class="hint">Optional. How the book appears on Google and when shared on social media. Leave blank to use the title and summary.</p>
            </div>

            <div class="serp" aria-hidden="true">
                <p class="serp-url">{{ parse_url(url('/'), PHP_URL_HOST) }} › books › <span data-serp-slug>{{ $book->slug ?: 'new-book' }}</span></p>
                <p class="serp-title" data-serp-title></p>
                <p class="serp-desc" data-serp-desc></p>
            </div>

            <label class="field">
                <span>Search title <small class="counter" data-count-for="seo_title"></small></span>
                <input type="text" name="seo_title" value="{{ old('seo_title', $book->seo_title) }}" maxlength="70" placeholder="{{ $book->title ?: 'Defaults to the book title' }}">
                <small class="hint">Up to 70 characters. “ · {{ config('site.name') }}” is added automatically.</small>
                @error('seo_title') <small class="error">{{ $message }}</small> @enderror
            </label>

            <label class="field">
                <span>Search description <small class="counter" data-count-for="seo_description"></small></span>
                <textarea name="seo_description" rows="3" maxlength="160" placeholder="Defaults to the summary">{{ old('seo_description', $book->seo_description) }}</textarea>
                <small class="hint">Up to 160 characters. Say what the book is about and who it’s for.</small>
                @error('seo_description') <small class="error">{{ $message }}</small> @enderror
            </label>
        </div>
        </div>

        <aside class="stack">
            <div class="panel stack">
                <h2>Cover</h2>

                <div class="cover-preview">
                    @include('partials.cover', ['book' => $book->exists ? $book : new \App\Models\Book(['title' => 'New book', 'cover_style' => old('cover_style', $book->cover_style)])])
                </div>

                <label class="field">
                    <span>{{ $book->cover_path ? 'Replace cover image' : 'Upload cover image' }}</span>
                    <input type="file" name="cover" accept="image/jpeg,image/png,image/webp">
                    <small class="hint">JPG, PNG or WebP, up to 5 MB. A 2:3 portrait image works best.</small>
                    @error('cover') <small class="error">{{ $message }}</small> @enderror
                </label>

                @if ($book->cover_path)
                    <label class="check">
                        <input type="checkbox" name="remove_cover" value="1"> Remove the uploaded image
                    </label>
                @endif

                <fieldset class="field">
                    <legend>Colour when there’s no image</legend>
                    <div class="swatches">
                        @foreach (\App\Models\Book::COVER_STYLES as $style)
                            <label class="swatch swatch-{{ $style }}">
                                <input type="radio" name="cover_style" value="{{ $style }}" @checked(old('cover_style', $book->cover_style) === $style)>
                                <span>{{ ucfirst($style) }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </div>

            <div class="panel stack">
                <h2>On the website</h2>

                <label class="check">
                    <input type="checkbox" name="published" value="1" @checked(old('published', $book->published))>
                    <span><strong>Published</strong><br><small class="hint">Untick to keep it as a draft.</small></span>
                </label>

                <label class="check">
                    <input type="checkbox" name="featured" value="1" @checked(old('featured', $book->featured))>
                    <span><strong>Featured</strong><br><small class="hint">Show it in “The Books” on the homepage.</small></span>
                </label>

                <label class="field">
                    <span>Order</span>
                    <input type="number" name="position" value="{{ old('position', $book->position) }}" min="0" placeholder="last">
                    <small class="hint">Lower numbers appear first.</small>
                    @error('position') <small class="error">{{ $message }}</small> @enderror
                </label>

                <fieldset class="field">
                    <legend>Themes</legend>
                    <div class="theme-list">
                        @foreach (config('site.topics') as $key => $topic)
                            <label class="check">
                                <input type="checkbox" name="topics[]" value="{{ $key }}" @checked(in_array($key, old('topics', $book->topics ?? [])))>
                                {{ $topic['title'] }}
                            </label>
                        @endforeach
                    </div>
                    @error('topics.*') <small class="error">{{ $message }}</small> @enderror
                </fieldset>
            </div>

            <button type="submit" class="btn btn-primary btn-block">{{ $book->exists ? 'Save changes' : 'Add book' }}</button>
        </aside>
    </form>

    <script>
        (() => {
            const rows = document.querySelector('[data-buy-rows]');
            const template = document.querySelector('[data-row-template]');
            let next = Number(rows.dataset.nextIndex);

            document.querySelector('[data-add-row]').addEventListener('click', () => {
                rows.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__i__', next++));
                rows.lastElementChild.querySelector('input').focus();
            });

            rows.addEventListener('click', (event) => {
                const button = event.target.closest('[data-remove-row]');
                if (!button) return;
                const row = button.closest('[data-buy-row]');
                // Keep one row on screen; clearing it is the same as removing it.
                if (rows.children.length === 1) {
                    row.querySelectorAll('input').forEach((input) => (input.value = ''));
                } else {
                    row.remove();
                }
            });
        })();
    </script>

    <script>
        // Live search-result preview and character counters.
        (() => {
            const form = document.querySelector('.book-form');
            const field = (name) => form.querySelector(`[name="${name}"]`);
            const siteName = @json(config('site.name'));
            const clip = (text, max) => (text.length > max ? text.slice(0, max - 1).trimEnd() + '…' : text);

            const update = () => {
                const title = field('seo_title').value.trim() || field('title').value.trim() || 'Book title';
                const desc = field('seo_description').value.trim() || field('summary').value.trim() || field('subtitle').value.trim();
                document.querySelector('[data-serp-title]').textContent = clip(`${title} · ${siteName}`, 65);
                document.querySelector('[data-serp-desc]').textContent = clip(desc || 'Add a summary or search description.', 160);
                const slug = field('slug').value.trim();
                if (slug) document.querySelector('[data-serp-slug]').textContent = slug;

                form.querySelectorAll('[data-count-for]').forEach((counter) => {
                    const input = field(counter.dataset.countFor);
                    counter.textContent = `${input.value.length}/${input.maxLength}`;
                });
            };

            ['title', 'subtitle', 'summary', 'slug', 'seo_title', 'seo_description'].forEach((name) => field(name).addEventListener('input', update));
            update();
        })();
    </script>
@endsection
