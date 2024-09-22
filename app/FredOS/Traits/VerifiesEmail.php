<?php

namespace App\FredOS\Traits;

// replaces Illuminate\Auth\MustVerifyEmail;
use App\Notifications\VerifyUserEmail;
use App\Notifications\VerifySubscriberEmail;

trait VerifiesEmail
{
  /**
   * Determine if the user has verified their email address.
   *
   * @return bool
   */
  public function hasVerifiedEmail()
  {
    return !is_null($this->login_verified_at);
  }

  /**
   * Mark the given user's email as verified.
   *
   * @return bool
   */
  public function markEmailAsVerified()
  {;
    $this->login_verified_at = $this->freshTimestamp();
    $this->save();
    /*
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    */
  }

  /**
   * Send the email verification notification.
   *
   * @return void
   */
  public function sendEmailVerificationNotification()
  {

    $this->notify(new VerifyUserEmail);
  }


  /**
   * Send the email verification notification.
   * @param string $checkout
   * @return void
   */
  public function sendSubscriberEmailVerificationNotification()
  {
    $this->notify(new VerifySubscriberEmail($this));
  }


  /**
   * Get the email address that should be used for verification.
   *
   * @return string
   */
  public function getEmailForVerification()
  {
    return $this->login;
  }
}
