<?php

namespace App\FredOS\Traits;

use Illuminate\Auth\Passwords\CanResetPassword;

trait UserResetsPassword
{
  use CanResetPassword;

  /**
   * Get the e-mail address where password reset links are sent.
   *
   * @return string
   */
  public function getEmailForPasswordReset()
  {
    return $this->login;
  }
}
