<?php

namespace App\Models\Users;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
  use HasFactory;

  public function users(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'role_user');
  }

  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class, 'role_user', 'role_id', 'site_id');
  }
}
