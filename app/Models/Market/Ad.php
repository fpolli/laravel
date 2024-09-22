<?php

namespace App\Models\Market;

use App\Models\Site;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ad extends Model
{
  use HasFactory;
  protected $table = 'ads';
  protected $guarded = ['id', 'owner_id', 'creator_id'];
  //ad_site
  //owner_class
  //owner_id
  //for_site
  //for_meta
  //for_id
  //creator_id
  //link
  //copy
  //slot
  //slot_id
  //start
  //stop
  //draft

  //relationships
  //none with
  public function owner(): BelongsTo
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'creator_id');
  }

  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class, 'for_site_id');
  }

  /* * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'copy' => 'array',
      'draft' => 'boolean',
      'start' => 'datetime',
      'stop' => 'datetime'
    ];
  }
}
