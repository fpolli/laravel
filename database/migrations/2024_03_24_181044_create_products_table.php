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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('stripe_product_id', 255);
      $table->string('sku', 10)->nullable();
      $table->string('name', 30);
      $table->string('type', 15)->required()->default('product');
      $table->text('description');
      $table->json('specifications');
      $table->foreignId('role_id')->constrained();
      $table->boolean('active');
      $table->boolean('public');
      $table->timestamps();
      $table->unique(['stripe_product_id', 'site']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
