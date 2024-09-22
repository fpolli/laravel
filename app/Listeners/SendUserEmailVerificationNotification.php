<?php

namespace App\Listeners;

use App\Events\RegisteredUser;
use App\Models\Users\User;

class SendUserEmailVerificationNotification
{
  /**
   * Handle the event.
   *
   * @param  \Illuminate\Auth\Events\RegisteredUser  $event
   * @return void
   */
  public function handle(RegisteredUser $event)
  {
    if ($event->user instanceof User && !$event->user->hasVerifiedEmail()) {
      $event->user->sendEmailVerificationNotification();
    }
  }
}
