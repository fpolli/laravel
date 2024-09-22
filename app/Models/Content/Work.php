<?php

namespace App\Models\Content;

use App\FredOS\Services\SeedsGetter;
use App\Freds\FredSlugs;
use App\Models\Base;
use App\Models\Fredopedia\Trigger;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Work extends Base
{
  use HasFactory;

  //Attributes
  //id
  //genre
  //ucc_id
  //title
  //blurb
  //author_name
  //credits
  //dna
  //body
  //mime
  //status
  //access
  //nonfiction
  //medium
  //triggers
  //adult
  //published_at

  protected $guarded = ['id'];
  protected $table = 'works';
  protected $stiClassField = 'mime';
  protected $stiNamespace = 'App\Models\Content';
  //needed if different than base class namespace:
  //protected $childNamespace = 'App\Models\Content';
  protected $stiBaseClass = 'Work';



  //Relationships
  protected $with = ['author', 'category', 'tags', 'schedule'];
  //Author
  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }
  //Category
  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }
  //Tags
  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class, 'tag_work', 'work_id', 'tag_id');
  }
  //Triggers - Fredopedia
  //  public function triggers(): BelongsToMany
  //  {
  //    return $this->belongsToMany(Trigger::class, 'trigger_work', 'work_id', 'trigger_id');
  //  }
  //Betas
  public function betas(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'beta_work', 'work_id', 'user_id');
  }

  public function schedule(): HasOne
  {
    return $this->hasOne(Schedule::class, 'work_id', 'id');
  }



  //Accessors/Mutators?

  //ucc_id mutator?
  protected function uccId(): Attribute
  {
    return Attribute::make(
      set: fn($value) => $this->makeUccId($value),
      get: fn($value) => trim($value)
    );
  }

  protected function slug(): Attribute
  {
    return Attribute::make(
      set: fn($value, $attributes) => FredSlugs::getSlug('works', 'slug', $attributes['title'])
    );
  }

  protected function sortDate(): Attribute
  {
    return Attribute::make(
      get: fn($value, $attributes) =>
      $attributes['published_at'] ?? $attributes['updated_at'],

    );
  }
  //body in child models?


  //casts
  //nonfiction
  //adult
  //published_at
  //credits
  //dna
  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'nonfiction' => 'boolean',
      'adult' => 'boolean',
      'published_at' => 'datetime',
      'updated_at' => 'datetime',
      'credits' => 'array',
      'dna' => 'array',
      'triggers' => 'array'
    ];
  }


  public function makeUccId(string|null $value = ''): string
  {
    if ($value) {
      return $value;
    }

    return config('app.site') . "-" . class_basename(__CLASS__) . "-" . time();
  }

  public function getFeatured()
  {
    //account for multi-media featured
    $imageName = "featured-image-{$this->ucc_id}.png";
    //check if file exists
    $path = images_path($imageName);
    if (file_exists($path)) {
      $url = images_url($imageName);
      return $url;
    }

    return '';
  }

  public function getPoster()
  {
    return '';
  }

  public function getTrailer()
  {
    return '';
  }

  public function circleOfTrust(User|null $user): bool
  {
    if ($this->access == 1) {
      return true;
    }

    $circle = Circle::find($this->access);

    if (
      !$user
      || (
        $user->getLevel() < $circle->level
        && !$user->hasRole($circle->role)
      )
    ) {
      return false;
    }
    return true;
  }

  public function getBylineDate()
  {
    switch ($this->status) {
      case 3:
        $bylineDate = $this->schedule->publish_at
          ->locale(session('locale'))
          ->setTimeZone(session('timezone'));
        break;
      case 2:
        $bylineDate = $this->published_at
          ->locale(session('locale'))
          ->setTimeZone(session('timezone'));
        break;
      case 1:
        $bylineDate = $this->updated_at
          ->locale(session('locale'))
          ->setTimeZone(session('timezone'));
        break;
    }

    $compDate = Carbon::now()
      ->locale(session('locale'))
      ->setTimeZone(session('timezone'));

    return $bylineDate->calendar($compDate, config('app.calendar_formats'));
  }
}
