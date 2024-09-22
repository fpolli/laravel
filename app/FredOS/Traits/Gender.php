<?php

namespace App\FredOS\Traits;

use Illuminate\Database\Schema\Blueprint;

trait Gender
{

  public function addGenderColumnsToMigration(Blueprint $table)
  {
    $table->string('gender', 1)->default('');
    $table->string('subj', 10)->nullable();
    $table->string('obj', 10)->nullable();
    $table->string('poss', 10)->nullable();
  }
}
