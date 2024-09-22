<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Models\Users\Avatar;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvatarPolicy
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
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Users\Avatar  $avatar
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function view(User $user, Avatar $avatar)
  {
    //
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
    return $user->getLevel() >= 0;
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Users\Avatar  $avatar
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function update(User $user, Avatar $avatar)
  {
    //
    return ($user->getLevel() >= 7 || $user->id === $avatar->user->id);
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Users\Avatar  $avatar
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function delete(User $user, Avatar $avatar)
  {
    //
    return ($user->getLevel() >= 7 || $user->id === $avatar->user->id);
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Users\Avatar  $avatar
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function restore(User $user, Avatar $avatar)
  {
    //
    return ($user->getLevel() >= 7 || $user->id === $avatar->user->id);
  }

  /**
   * Determine whether the user can permanently delete the model.
   *
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Users\Avatar  $avatar
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function forceDelete(User $user, Avatar $avatar)
  {
    //
    return ($user->getLevel() >= 7 || $user->id === $avatar->user->id);
  }
}
