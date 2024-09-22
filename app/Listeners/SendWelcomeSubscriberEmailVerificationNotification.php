<?php

namespace App\Listeners;

use App\Events\Subscriber;
use App\Models\Users\User;


class SendWelcomeSubscriberEmailVerificationNotification
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  \Illuminate\Auth\Events\Subscriber  $event
   * @return void
   */
  public function handle(Subscriber $event)
  {
    if ($event->user instanceof User && !$event->user->hasVerifiedEmail()) {
      $event->user->sendSubscriberEmailVerificationNotification();
    }
  }
}
