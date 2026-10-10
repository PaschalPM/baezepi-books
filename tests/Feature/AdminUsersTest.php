<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_an_editor_who_can_then_manage_books(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/users', [
                'name' => 'Ada Editor',
                'email' => 'ada@example.com',
                'role' => 'editor',
                'password' => 'long-enough-1',
                'password_confirmation' => 'long-enough-1',
            ])
            ->assertRedirect('/admin/users');

        $editor = User::where('email', 'ada@example.com')->sole();
        $this->assertSame('editor', $editor->role);
        $this->assertTrue(Hash::check('long-enough-1', $editor->password));

        // The new editor can log in and add, edit and delete books.
        $this->post('/admin/logout');
        $this->post('/admin/login', ['email' => 'ada@example.com', 'password' => 'long-enough-1'])
            ->assertRedirect('/admin/books');

        $this->post('/admin/books', ['title' => 'Editor Book', 'cover_style' => 'forest', 'published' => '1'])
            ->assertRedirect('/admin/books');
        $book = Book::sole();

        $this->put("/admin/books/{$book->slug}", ['title' => 'Edited Book', 'cover_style' => 'ink'])
            ->assertRedirect('/admin/books');
        $this->assertSame('Edited Book', $book->fresh()->title);
        $this->assertSame('editor-book', $book->fresh()->slug, 'renaming keeps the existing web address');

        $this->delete("/admin/books/{$book->slug}")->assertRedirect('/admin/books');
        $this->assertDatabaseCount('books', 0);
    }

    public function test_editors_cannot_manage_users(): void
    {
        $editor = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($editor);

        $this->get('/admin/users')->assertForbidden();
        $this->get('/admin/users/create')->assertForbidden();
        $this->post('/admin/users', [
            'name' => 'Sneaky', 'email' => 'sneaky@example.com', 'role' => 'admin',
            'password' => 'long-enough-1', 'password_confirmation' => 'long-enough-1',
        ])->assertForbidden();
        $this->put("/admin/users/{$editor->id}", ['name' => $editor->name, 'email' => $editor->email, 'role' => 'admin'])->assertForbidden();
        $this->delete("/admin/users/{$other->id}")->assertForbidden();

        $this->assertSame('editor', $editor->fresh()->role);
        $this->assertModelExists($other);
        $this->get('/admin/books')->assertOk()->assertDontSee('/admin/users');
    }

    public function test_admin_can_edit_and_remove_users(): void
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->create(['password' => 'old-password-1']);

        $this->actingAs($admin)
            ->put("/admin/users/{$editor->id}", [
                'name' => 'Renamed', 'email' => $editor->email, 'role' => 'admin', 'password' => '',
            ])
            ->assertRedirect('/admin/users');

        $editor->refresh();
        $this->assertSame('Renamed', $editor->name);
        $this->assertSame('admin', $editor->role);
        $this->assertTrue(Hash::check('old-password-1', $editor->password), 'blank password keeps the old one');

        $this->delete("/admin/users/{$editor->id}")->assertRedirect('/admin/users');
        $this->assertModelMissing($editor);
    }

    public function test_the_last_admin_cannot_be_demoted_or_removed(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->put("/admin/users/{$admin->id}", ['name' => $admin->name, 'email' => $admin->email, 'role' => 'editor'])
            ->assertSessionHasErrors('role');
        $this->assertSame('admin', $admin->fresh()->role);

        // Admins can't delete themselves either.
        $this->delete("/admin/users/{$admin->id}");
        $this->assertModelExists($admin);
    }

    public function test_user_validation(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/users', [
                'name' => '',
                'email' => $admin->email,
                'role' => 'owner',
                'password' => 'short',
                'password_confirmation' => 'different',
            ])
            ->assertSessionHasErrors(['name', 'email', 'role', 'password']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_anyone_can_change_their_own_password(): void
    {
        $editor = User::factory()->create(['password' => 'current-pass-1']);
        $this->actingAs($editor);

        $this->put('/admin/account', [
            'current_password' => 'wrong',
            'password' => 'brand-new-pass',
            'password_confirmation' => 'brand-new-pass',
        ])->assertSessionHasErrors('current_password');

        $this->put('/admin/account', [
            'current_password' => 'current-pass-1',
            'password' => 'brand-new-pass',
            'password_confirmation' => 'brand-new-pass',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('brand-new-pass', $editor->fresh()->password));
    }
}
