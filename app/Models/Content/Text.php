<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\FredOS\Traits\Playable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;

class Text extends Work
{
  //use HasFactory;
  use Playable;
  //attributes in parent
  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'mime' => 'text',
    'body' => ''
  ];

  //relationships in parent

  //inVolumes

  //Tags
  public function tags(): BelongsToMany
  {
    return parent::tags();
  }

  //Accessors/Mutators?
  protected function body(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => $this->addPlayas($value),
      set: fn ($value) => $this->stripPlayas($value),
    );
  }

  //casts
  //methods

}
