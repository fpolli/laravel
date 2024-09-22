<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\FredOS\Traits\Money;

return new class extends Migration
{
  use Money;
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('prices', function (Blueprint $table) {
      $table->id();
      $table->string('name', 35);
      $table->string('stripe_price_id', 255);
      $table->string('renewal_interval', 30);
      $table->integer('interval_quantity');
      $this->addMoneyColumnsToMigration($table);
      //      $table->foreignId('product_id')->constrained()->onDelete('CASCADE');
      $table->timestamps();
      $table->unique('stripe_price_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('prices');
  }
};
