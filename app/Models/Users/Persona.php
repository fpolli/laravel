<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persona extends Model
{
  use HasFactory;

  //Attributes:
  //id
  //address
  //service
  //usage
  //status
  //public
  //verified_at
  //timestamps

  protected $guarded = ['id'];

  //relationships
  //User belongsTo
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'verified_at' => 'datetime',
      'status' => 'boolean',
      'public' => 'boolean'
    ];
  }
}
