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
    Schema::create('publish_schedule', function (Blueprint $table) {
      $table->id();
      $table->foreignId('work_id')->constrained(table: 'works')->onDelete('cascade');
      $table->timestamp('publish_at')->required();
      $table->string('publish_tz')->required();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('publish_schedule');
  }
};
