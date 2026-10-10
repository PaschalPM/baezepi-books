<?php

namespace Tests\Feature;

use Tests\TestCase;

class BlogTest extends TestCase
{
    public function test_blog_index_lists_categories(): void
    {
        $this->get('/blog')->assertOk()
            ->assertSee('Creator Practice')
            ->assertSee('/blog/creator-practice', false);
    }

    public function test_category_page_lists_its_posts(): void
    {
        $response = $this->get('/blog/creator-practice')->assertOk()->assertSee('Notes / Creator Practice');

        foreach (config('site.blog.creator-practice.posts') as $slug => $post) {
            $response->assertSee($post['title'])->assertSee("/blog/creator-practice/$slug", false);
        }
    }

    public function test_every_post_renders_with_related_posts(): void
    {
        foreach (array_keys(config('site.blog.creator-practice.posts')) as $slug) {
            $this->get("/blog/creator-practice/$slug")->assertOk()
                ->assertSee('Related posts')
                ->assertSee('← Creator Practice', false);
        }
    }

    public function test_unknown_categories_and_posts_are_not_found(): void
    {
        $this->get('/blog/nope')->assertNotFound();
        $this->get('/blog/creator-practice/nope')->assertNotFound();
        $this->get('/blog/nope/how-to-start-writing')->assertNotFound();
    }

    public function test_every_blog_image_exists(): void
    {
        $html = collect(config('site.blog.creator-practice.posts'))->keys()
            ->map(fn ($slug) => file_get_contents(resource_path("views/blog/posts/$slug.blade.php")))->join('');

        preg_match_all('#/images/blog/[^"\s,]+#', $html.json_encode(config('site.blog'), JSON_UNESCAPED_SLASHES), $paths);

        foreach (array_unique($paths[0]) as $path) {
            $this->assertFileExists(public_path(ltrim($path, '/')));
        }
    }
}
