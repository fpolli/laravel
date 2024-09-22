<?php

namespace App\Models\Fredopedia;

use App\Models\Content\Work;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trigger extends Entry
{
  //use HasFactory;
  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'topic' => 'triggers',
  ];

  //Relationships
  //lazy loaded
  //Works many-many
  public function works(): BelongsToMany
  {
    return $this->belongsToMany(Work::class);
  }
}
