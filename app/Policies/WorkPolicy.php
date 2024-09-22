<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Models\Content\Work;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkPolicy
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
   * @param  \App\Models\Content\Work $work
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function view(User|null $user, Work $work)
  {
    //
    return (
      $work->status == 2
      || ($work->status == 1
        && ($this->update($user, $work)
          || $this->beta($user, $work)
        )
      )
    );
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
    return ($user->getLevel() >= 4 || $user->hasRole('Creator'));
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param  \App\Models\Users\User  $user
   * @param  \App\Models\Content\Work  $content
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function update(User $user, Work $content)
  {
    //
    return ($user->getLevel() >= 7 || $user->id === $content->author->id);
  }

  public function delete(User $user)
  {
    return ($user->getLevel() >= 7 || $user->hasRole('Editor'));
  }

  public function beta(User $user, Work $work)
  {
    return $work->betas->contains($user->id);
  }
}
