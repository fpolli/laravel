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
    Schema::create('works', function (Blueprint $table) {
      $table->id();
      $table->string('genre', 17);
      $table->char('ucc_id', 36)->nullable()->unique();
      $table->string('title', 100);
      $table->string('slug', 40)->unique();
      $table->string('blurb', 300)->default('excerpt');
      $table->foreignId('author_id')->constrained(table: 'users');
      $table->string('author_name', 255)->index();
      $table->text('credits')->nullable();
      $table->text('dna')->nullable();
      $table->text('body')->default(''); //maps to simple_array type in DBAL
      $table->foreignId('category_id')->constrained(table: 'categories');
      $table->string('mime', 50)->nullable();
      $table->integer('status')->default(0);
      $table->integer('access')->default(1);
      $table->boolean('nonfiction')->default(true);
      $table->string('medium', 20)->default('none');
      $table->text('triggers')->nullable();
      $table->boolean('adult')->nullable();
      $table->timestamps();
      $table->timestamp('published_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('works');
  }
};
