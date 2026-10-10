<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Prices of the published catalogue, applied to books that do not have one yet. */
    private const PRICES = [
        'blurred-lines' => 9.99,
        'boosting-the-size-of-sale' => 12.99,
        'ceos-of-tiktok' => 14.99,
        'iq-an-overrated-quotient' => 11.99,
        'the-dogged-spirit-of-success' => 10.99,
    ];

    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->after('pages');
        });

        foreach (self::PRICES as $slug => $price) {
            DB::table('books')->where('slug', $slug)->whereNull('price')->update(['price' => $price]);
        }
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
