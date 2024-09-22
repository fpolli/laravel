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
    Schema::create('fredopedia', function (Blueprint $table) {
      $table->id();
      $table->string('term', 100)->default('');
      $table->string('category', 100)->default('general');
      $table->text('definition')->default('');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fredopedia');
  }
};
