<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('genre')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('summary')->nullable();
            $table->string('question')->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('cover_style')->default('forest');
            $table->json('topics')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
