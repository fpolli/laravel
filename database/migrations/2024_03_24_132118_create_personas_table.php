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
    Schema::create('personas', function (Blueprint $table) {
      $table->id();
      $table->string('address', 255)->unique();
      $table->string('service', 32)->default('email');
      $table->string('usage', 32)->default('personal');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->boolean('status')->default(true);
      $table->boolean('public')->default(true);
      $table->timestamp('verified_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('personas');
  }
};
