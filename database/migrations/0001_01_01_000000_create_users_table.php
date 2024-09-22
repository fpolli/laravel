<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\FredOS\Traits\Name;
use App\FredOS\Traits\Gender;

return new class extends Migration
{
  use Name;
  use Gender;
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      //            $table->string('name');
      $this->addNameColumnsToMigration($table);
      $this->addGenderColumnsToMigration($table);
      $table->string('login', 255)->unique();
      $table->timestamp('login_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();
      $table->string('time_zone', 40)->nullable();
      $table->string('locale', 10)->nullable();
      $table->string('stripe_id', 255)->nullable()->unique();
      $table->timestamp('last_login')->nullable();
      $table->timestamp('last_visit')->nullable();

      $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('email')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignId('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
  }
};
