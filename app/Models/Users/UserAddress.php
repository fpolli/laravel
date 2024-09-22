<?php

namespace App\Models\Users;

//use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{

  protected $table = 'user_address';

  //user_id (User)
  //address_id (Address)
  //usage
  //current
  //public
  //timestamps

  protected $guarded = ['id'];

  //relationships
  //  protected array $with = ['user', 'address'];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function address(): BelongsTo
  {
    return $this->belongsTo(Address::class);
  }

  protected function casts()
  {
    return [
      'current' => 'boolean',
      'public' => 'boolean'
    ];
  }
}
