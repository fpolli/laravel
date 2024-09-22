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
    Schema::create('subscriptions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
      $table->string('stripe_id', 255)->unique();
      $table->string('stripe_status', 255)->default('');
      $table->timestamp('trial_ends_at')->nullable();
      $table->timestamp('ends_at');
      $table->string('origin', 255)->nullable();
      $table->foreignId('product_id')->constrained()->onDelete('CASCADE');
      $table->foreignId('price_id')->constrained()->onDelete('CASCADE');
      $table->foreignId('site_id')->constrained()->onDelete('CASCADE');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('subscriptions');
  }
};
