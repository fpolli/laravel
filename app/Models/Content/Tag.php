<?php

namespace App\Models\Content;

use App\Freds\FredSlugs;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
  use HasFactory;

  protected $guarded = ['id'];
  //Attributes
  //id
  //name
  //slug

  protected function slug(): Attribute
  {
    return Attribute::make(
      set: fn ($value, $attributes) => FredSlugs::getSlug('tags', 'slug', $attributes['name'])
    );
  }

  //Relationships
  //Work - ManyToMany
  public function works(): BelongsToMany
  {
    return $this->belongsToMany(Work::class);
  }
}
