<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Models\Market\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
  use HandlesAuthorization;
  /**
   * Create a new policy instance.
   */
  public function __construct()
  {
    //
  }

  public function viewAny(User $user)
  {
    //
    return true;
  }

  public function view(User|null $user, Product $product)
  {
    //
    return true;
  }

  public function create(User $user)
  {
    //
    if ($user->getLevel() > 6 || $user->hasRole('Shopkeeper')) {
      return true;
    }
    return false;
  }

  public function update(User $user, Product $product)
  {
    //add shop ownership condition
    if ($user->getLevel() > 7) {
      return true;
    }
    return false;
  }
}
