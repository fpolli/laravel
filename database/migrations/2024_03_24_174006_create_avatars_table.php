<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\FredOS\Traits\Gender;

return new class extends Migration
{
  use Gender;
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('avatars', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('restrict')->onUpdate('restrict'); // delete profile when user is deleted
      $table->string('screen_name', 50)->unique();
      $table->string('greeting', 50)->default('');
      $table->string('quick_intro', 200)->nullable();
      $table->text('bio')->nullable();
      $table->string('slogan', 200)->nullable();
      $table->boolean('archived')->default(false);
      $table->string('ext', 4)->nullable();
      $this->addGenderColumnsToMigration($table);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('avatars');
  }
};
