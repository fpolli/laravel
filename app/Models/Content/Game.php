<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;

class Game extends Work
{
  //  use HasFactory;
  //attributes in parent
  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'mime' => 'game',
    'body' => []
  ];

  //relationships in parent

  //inVolumes

  //Accessors/Mutators?

  //casts
  //body
  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'body' => 'array',
      'triggers' => 'array'
    ];
  }
}
