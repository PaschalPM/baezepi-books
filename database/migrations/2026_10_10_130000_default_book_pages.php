<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/** Books saved without a page count get the default (Book::DEFAULT_PAGES), which the model now applies on save. */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('books')->whereNull('pages')->update(['pages' => 100]);
    }

    public function down(): void
    {
        // The original blanks are not recorded.
    }
};
