<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\FredOS\Traits\Money;

class Price extends Model
{
  use HasFactory, Money;

  //Attributes
  //stripe_price_id
  //renewal_interval
  //interval_quantity
  //from Money:
  //currency

  protected $guarded = ['id', 'stripe_price_id'];

  //Relationships
  public function products(): BelongsToMany
  {
    return $this->belongsToMany(Product::class);
  }

  //mutators
  //amount in Money

  //display
  public function getIntervalDisplay(Price $price = null)
  {
    $price = $price ?? $this;
    $int = ucfirst($price->renewal_interval);
    return ($price->interval_quantity > 1) ?
      "{$price->interval_quantity} {$int}s" :
      $int;
  }
}
