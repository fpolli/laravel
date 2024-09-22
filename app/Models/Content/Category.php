<?php

namespace App\Models\Content;

use App\Freds\FredSlugs;
use App\Models\Site;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
  use HasFactory;

  protected $table = 'categories';

  //Attributes
  //id
  //name
  //slug
  //blurb
  //site
  //genres

  protected $guarded = ['id'];

  //Relationships
  //Work - hasMany
  public function works(): HasMany
  {
    return $this->hasMany(Work::class);
  }

  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class, 'category_site');
  }

  protected function slug(): Attribute
  {
    return Attribute::make(
      set: fn($value, $attributes) => FredSlugs::getSlug('categories', 'slug', $attributes['name'])
    );
  }

  //casts
  protected function casts(): array
  {
    return [
      'genres' => 'array'
    ];
  }

  //special methods
  public function hasGenre(string $genre)
  {
    return in_array($genre, $this->genres);
  }

  //returns true if in array or empty array
  public function forGenre(string $genre)
  {
    return ($this->genres) ? in_array($genre, $this->genres) : true;
  }
}
