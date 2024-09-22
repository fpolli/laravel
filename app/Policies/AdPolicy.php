<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Models\Market\Ad;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdPolicy
{
  use HandlesAuthorization;

  /**
   * Create a new policy instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Determine whether the user can view any models.
   *
   * @param  \App\Models\Users\User  $user
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function viewAny(User $user)
  {
    //
    return true;
  }

  /**
   * Determine whether the user can view the model.
   *
   * @param  \App\Models\Users\User|null  $user
   * @param  \App\Models\Market\Ad $ad
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function view(User|null $user, Ad $ad)
  {
    //
    if ($ad->draft) {
      return ($user?->getLevel() >= 7
        || ($user?->id === $ad->owner->id || $user?->id === $ad->creator->id)
      );
    }
    return true;
  }

  /**
   * Determine whether the user can create models.
   *
   * @param  \App\Models\Users\User  $user
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function create(User $user)
  {
    //
    return ($user->getLevel() >= 7 || $user->hasRole('Shopkeeper') || $user->hasRole('Sponsor'));
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Market\Ad  $ad
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function update(User $user, Ad $ad)
  {
    //
    return ($user->getLevel() >= 7
      || ($user->id == $ad->owner->id || $user->id == $ad->creator->id)
    );
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Market\Ad  $ad
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function delete(User $user, Ad $ad)
  {
    return ($user->getLevel() >= 7
      || ($user->id == $ad->owner->id || $user->id == $ad->creator->id)
    );
  }
}
