<?php

namespace App\Models;

use App\Models\Content\Category;
use App\Models\Market\Ad;
use App\Models\Market\Product;
use App\Models\Market\Subscription;
use App\Models\Users\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Base
{
  use HasFactory;

  protected $guarded = ['id'];
  protected $table = 'sites';

  //Fields
  //id? just use code?
  //code
  //owner_id
  //public
  //name
  //domain
  //local_url
  //development_url
  //description
  //site_password
  //genres
  //volumes
  //media
  //mail_server

  //Relationships
  //none defined on this model
  //some getters for on-demand retrievals
  //roles => many-to-many
  //getRoleUsers
  //getRoleAvatars
  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(Role::class, 'role_user', 'site_id', 'role_id');
  }

  public function getRoleUsers(int|string $role): Collection
  {
    return is_int($role)
      ? $this->roles()->find($role)->users
      : $this->roles()->where('name', $role)->first()->users;
  }
  //products  => many-to-many lazy
  public function products(): BelongsToMany
  {
    return $this->belongsToMany(Product::class, 'product_site', 'site_id', 'product_id');
  }
  //subscriptions => has-many lazy
  public function subscriptions(): HasMany
  {
    return $this->hasMany(Subscription::class)->with('subscriber');
  }
  //ads => has-many lazy
  public function ads(): HasMany
  {
    return $this->hasMany(Ad::class, 'for_site_id');
  }
  //works => many-to-many no 
  //categories => many-to-many lazy
  public function categories(): BelongsToMany
  {
    return $this->belongsToMany(Category::class, 'category_site', 'site_id', 'category_id');
  }

  //Casts
  protected function casts(): array
  {
    return [
      'genres' => 'array',
      'volumes' => 'array',
      'media' => 'array',
      'mail_server' => 'array',
      'created_at' => 'datetime',
      'updated_at' => 'datetime',
      'public' => 'boolean',
      'routedata' => 'array'
    ];
  }
  //public => bool
  //genres => array
  //volumes => array
  //media => array
  //mail_server => array
  //created_at => datetime

}
