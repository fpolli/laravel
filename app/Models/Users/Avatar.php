<?php

namespace App\Models\Users;

use App\Models\Site;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avatar extends Model
{
  use HasFactory;
  //Name, Gender

  //attributes
  //id
  //screen_name
  //greeting
  //quick_intro
  //bio
  //slogan
  //archived
  //ext ???? file extension of graphical?
  //Gender
  //timestamps

  protected $guarded = ['id'];

  //relationships
  //User belongsTo
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
  //one-to-many with SiteAvatar
  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class, 'avatar_user', 'avatar_id', 'site_id');
  }

  //accessors
  //graphical

  public function graphicalRepresentation(): string
  {
    $itsname = "avatar-{$this->id}.{$this->ext}";
    $path = avatars_path($this->user->id, $itsname);
    //fredLager($path);
    $imgSrc = '';
    //exist?
    if (file_exists($path)) {
      //create src url
      $imgSrc = avatars_url($this->user->id, $itsname);
    } else {
      //else
      //create src url to default image
      $imgSrc = user_url('default_avatar.png');
    }
    //fredLager($imgSrc);
    return $imgSrc;
  }

  public function isCurrent(): bool
  {
    return $this->site_avatars()->where('site', config('app.site'))->exists();
  }

  //fillable, hidden?



}
