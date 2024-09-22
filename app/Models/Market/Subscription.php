<?php

namespace App\Models\Market;

use App\Models\Site;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Subscription extends Model
{
  use HasFactory;

  //attributes
  //id
  //stripe_id
  //stripe_status
  //trial_ends_at
  //ends_at
  //origin
  //timestamps

  protected $guarded = ['id', 'stripe_id'];

  //Relationships
  protected $with = ['product', 'price'];
  //User
  public function subscriber(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
  //ProductThrough?
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }
  //PriceThrough?
  public function price(): BelongsTo
  {
    return $this->belongsTo(Price::class);
  }

  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }

  //accessors/mutators?

  //casts
  //trial_ends_at
  //ends_at
  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'trial_ends_at' => 'datetime',
      'ends_at' => 'datetime'
    ];
  }
}
