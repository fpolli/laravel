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
    Schema::create('ads', function (Blueprint $table) {
      $table->id();
      $table->string('ad_site', 4)->default('sobb');
      $table->string('owner_class', 10)->default('seeds');
      $table->foreignId('owner_id')->references('id')->on('users')->constrained();
      $table->foreignId('for_site_id')->references('id')->on('sites')->constrained();
      $table->string('for_meta', 7);
      $table->integer('for_id')->unsigned();
      $table->foreignId('creator_id')->references('id')->on('users')->constrained();
      $table->string('link')->default('https://seedsoftheburningbushll.com');
      $table->text('copy');
      $table->string('slot', 20)->nullable();
      $table->integer('slot_id')->unsigned()->nullable();
      $table->timestamp('start');
      $table->timestamp('stop');
      $table->boolean('draft')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ads');
  }
};
