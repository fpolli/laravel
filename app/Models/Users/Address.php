<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
  use HasFactory;

  //attributes
  //id
  //country
  //postal
  //state
  //locality
  //delivery_address
  //timestamps

  protected $guarded = ['id'];

  //relationships
  //User many-many
  public function users(): HasMany
  {
    return $this->hasMany(UserAddress::class);
  }

  //fillable?

}
