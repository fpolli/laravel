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
    Schema::create('volumes_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('item_id')->references('id')->on('works')->onDelete('CASCADE');
      $table->foreignId('volume_id')->references('id')->on('works')->onDelete('CASCADE');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('volumes_items');
  }
};
