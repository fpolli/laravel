<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Content\Work;
use App\Models\Market\Subscription;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use App\FredOS\Traits\NotifiableUser;
//use App\FredOS\Traits\VerifiesEmail;
//use App\FredOS\Traits\UserResetsPassword;
//use Filament\Models\Contracts\FilamentUser;
//use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
  use HasFactory, Notifiable; //NotifiableUser, VerifiesEmail, UserResetsPassword;
  //Name, Gender traits?

  //attributes:
  //id
  //Name Trait:
  //full_name
  //surname
  //formal_name
  //initials
  //familiar_name
  //nickname
  //Gender Trait:
  //gender
  //subj
  //obj
  //poss
  //password
  //remember
  //time_zone
  //locale
  //stripeId
  //pm_type
  //pm_last_four
  //trial_ends_at
  //last_login
  //last_visit
  //Timestamps
  //created_at
  //updated_at

  public function canAccessPanel(Panel $panel): bool
  {
    if ($panel->getId() == 'admin') {
      return ($this->getLevel() > 6);
    } else {
      return true;
    }
  }

  protected int $level = -1;

  protected $guarded = ['id', 'password', 'stripe_id'];

  protected $attributes = [
    'locale' => 'en_US'
  ];

  protected function email(): Attribute
  {
    return Attribute::make(
      get: fn($value, $attributes) => $attributes['login'],
    );
  }

  //Relationships
  protected $with = ['personas', 'siteAvatar', 'roles'];
  //Personas - one to many
  public function personas(): HasMany
  {
    return $this->hasMany(Persona::class);
  }

  //Login
  //  public function login(): HasOne
  //  {
  //    return $this->personas()->one()->where('usage', 'login');
  //  }
  //Avatars
  public function avatars(): HasMany
  {
    return $this->HasMany(Avatar::class);
  }
  //SiteAvatar (CurrentAvatar?)
  public function siteAvatar(): HasOne
  {
    //return $this->hasOne(SiteAvatar::class, 'user_id')->where('site', config('app.site'));
    return $this->HasOne(Avatar::class)->withWhereHas('sites', function (Builder $query) {
      $query->where('id', request()->currentSite->id);
    });
  }
  //Addresses
  public function addresses(): HasMany
  {
    return $this->hasMany(UserAddress::class);
  }

  public function primary_address(): HasOne
  {
    //    return $this->addresses->where([
    //      ['usage', 'billing'],
    //      ['current', true]
    //    ])->first();
    return
      $this->hasOne(UserAddress::class)
      ->where([
        ['usage', '=', 'billing'],
        ['current', '=', true]
      ]);
  }

  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(Role::class, 'role_user')
      ->wherePivotIn('site', ['sobb', request()->currentSite->code])
      ->orderBy('level', 'desc');
  }

  //Works
  public function works(): HasMany
  {
    return $this->hasMany(Work::class);
  }
  //Betas
  public function betas(): BelongsToMany
  {
    return $this->belongsToMany(Work::class, 'betas_works', 'user_id', 'work_id');
  }
  //Subscriptions
  public function subscriptions(): HasMany
  {
    return $this->hasMany(Subscription::class);
  }

  //accessors
  //LoginEmailVerifiedAt - accessor

  //  protected function loginVerifiedAt(): Attribute
  //  {
  //    return Attribute::make(
  //      get: fn ($value) => $this->login->verified_at,
  //      set: fn ($value) => $this->login->verified_at = $value
  //    );
  //  }

  //it is assumed that the rolename given is for this site
  //meaning - do not use this to set a subscription on another site
  public function setRole($roleName)
  {
    switch ($roleName) {
      case 'Friend':
        $newSiteRole = Role::firstWhere('name', 'Friend');
        $newSoBBRole = Role::firstWhere('name', 'Guest');
        $this->roles()->attach($newSiteRole->id, ['site' => config('app.site')]);
        if (!$this->hasRole($newSoBBRole->id)) {
          $this->roles()->attach($newSoBBRole->id, ['site' => 'sobb']);
        }
        break;
      case 'Patron':
        $newSiteRole = Role::firstWhere('name', 'Patron');
        $newSoBBRole = Role::firstWhere('name', 'Friend');
        $this->roles()->attach($newSiteRole->id, ['site' => config('app.site')]);
        if (!$this->hasRole($newSoBBRole->id)) {
          $this->roles()->attach($newSoBBRole->id, ['site' => 'sobb']);
        }
        break;
      default:
        $newRole = Role::firstWhere('name', $roleName);
        $this->roles()->attach($newRole->id, ['site' => 'sobb']);
    }
  }

  public function getLevel()
  {
    if ($this->level == -1) {
      $this->level = $this->roles->first()->level;
    }
    return $this->level;
  }

  public function hasRole(int|string $role): bool
  {
    if (is_int($role)) {
      return $this->roles()
        ->where('id', $role)
        ->wherePivotIn('site', ['sobb', config('app.site')])
        ->get()
        ->isNotEmpty();
    } else {
      return $this->roles()
        ->where('name', $role)
        ->wherePivotIn('site', ['sobb', config('app.site')])
        ->get()
        ->isNotEmpty();
    }
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      //      'password' => 'hashed', -- hashing in service
      'last_login' => 'datetime',
      'last_visit' => 'datetime'
    ];
  }

  public static function byEmail($email)
  {
    return self::byLogin($email);
  }

  public static function byLogin($login)
  {
    return self::firstWhere('login', $login);
  }

  public function getName(): array
  {
    return [
      'full_name' => $this->full_name,
      'surname' => $this->surname,
      'initials' => $this->initials,
      'formal_name' => $this->formal_name,
      'familiar_name' => $this->familiar_name,
      'nickname' => $this->nickname,
    ];
  }

  public function getGender(): array
  {
    return [
      'gender' => $this->gender,
      'subj' => $this->subj,
      'obj' => $this->obj,
      'poss' => $this->poss
    ];
  }

  public function getLoginPersona()
  {
    return $this->personas()->one()->where('usage', 'login');
  }
}
