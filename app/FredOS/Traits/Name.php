<?php

namespace App\FredOS\Traits;

use Illuminate\Database\Schema\Blueprint;

trait Name
{

  public function addNameColumnsToMigration(Blueprint $table)
  {
    $table->string('full_name', 255)->default('');
    $table->string('surname', 255)->default('');
    $table->string('formal_name', 255)->default('');
    $table->string('initials', 6)->default('');
    $table->string('familiar_name', 30)->nullable();
    $table->string('nickname', 30)->nullable();
  }
}
