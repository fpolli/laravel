<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Models\Content\Category\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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
   * Determine whether the user can create models.
   *
   * @param  \App\Models\Users\User  $user
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function create(User $user)
  {
    //
    return ($user->getLevel() >= 6);
  }

  public function delete(User $user)
  {
    return ($user->getLevel() >= 7);
  }
}
