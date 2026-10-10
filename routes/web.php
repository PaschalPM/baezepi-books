<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Search engines
Route::get('sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [SeoController::class, 'robots'])->name('robots');

// Admin
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->name('admin.')->group(function () {
        Route::redirect('/', '/admin/books')->name('home');
        Route::resource('books', BookController::class)->except('show');
        Route::patch('books/{book}/visibility', [BookController::class, 'toggleVisibility'])->name('books.visibility');

        Route::resource('users', UserController::class)->except('show')->middleware('can:manage-users');

        Route::get('account', [AccountController::class, 'edit'])->name('account');
        Route::put('account', [AccountController::class, 'update'])->name('account.update');
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
    });
});

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    Route::get('/books', 'books')->name('books.index');
    Route::get('/books/{slug}', 'book')->name('books.show');
    Route::get('/books/{slug}/checkout', 'checkout')->name('books.checkout');

    Route::get('/blog', 'notes')->name('notes.index');
    Route::get('/blog/{category}', 'noteCategory')->name('notes.category');
    Route::get('/blog/{category}/{slug}', 'note')->name('notes.show');

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->name('contact.send');
    Route::post('/subscribe', 'subscribe')->name('subscribe');

    // Topic landing pages, e.g. /books-about-leadership
    foreach (config('site.topics') as $key => $topic) {
        Route::get('/'.$topic['slug'], 'topic')->defaults('key', $key)->name("topics.$key");
    }
});

Route::view('/about', 'pages.about')->name('about');
Route::view('/reading-and-publishing-statistics', 'pages.stats')->name('stats');
Route::view('/privacy', 'pages.legal.privacy')->name('privacy');
Route::view('/terms', 'pages.legal.terms')->name('terms');
Route::view('/cookie-policy', 'pages.legal.cookies')->name('cookies');
