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
    Schema::create('sites', function (Blueprint $table) {
      $table->id();
      //NOT a key, not a relationship
      $table->bigInteger('owner_id')->required()->unsigned()->default(0);
      $table->string('code', 4)->required()->unique();
      $table->string('name', 30)->required();
      $table->string('domain', 75)->required();
      $table->string('local_url', 75)->required();
      $table->string('development_url', 75)->required();
      $table->string('description', 100)->nullable();
      $table->string('site_password', 255)->nullable();
      $table->text('genres')->nullable();
      $table->text('volumes')->nullable();
      $table->text('media')->nullable();
      $table->text('mail_server')->nullable();
      $table->boolean('public')->default(false);
      //      $table->text('routedata');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sites');
  }
};
