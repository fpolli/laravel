<?php

namespace App\Models\Market;

use App\Models\Site;
use App\Models\Users\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
  use HasFactory;
  //attributes
  //stripe_product_id
  //name
  //type - product, periodical, electronic, service, swag
  //description
  //specifications - json -- cast to array
  //role
  //site
  //active
  //public
  //timestamps

  protected $guarded = ['id', 'stripe_product_id'];

  //Relationships
  protected $with = ['prices', 'role'];
  //Prices - one to many
  public function prices(): BelongsToMany
  {
    return $this->belongsToMany(Price::class);
  }

  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class);
  }

  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class);
  }
  /*
  protected function specifications(): Attribute
  {
    return Attribute::make(
      get: fn (string $value) => json_decode($value),
      set: fn (array $value) => json_encode($value),
    );
  }
*/
  protected function casts(): array
  {
    return [
      //      'specifications' => AsArrayObject::class,
      'specifications' => 'array',
      'active' => 'boolean',
      'public' => 'boolean'
    ];
  }
}
