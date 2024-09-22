<?php

namespace App\Listeners;

use App\Events\EmailAdded;
use App\Mail\VerifyPersonaEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class VerifyAddedEmail
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(EmailAdded $event): void
  {
    $persona = $event->persona;
    //get the user's name
    $userName = $persona->user->formal_name ?? $persona->user->full_name;
    //the email address
    $email = $persona->address;
    //the hashed route
    $returnURL =
      URL::temporarySignedRoute(
        'account.persona.verify',
        Carbon::now()->addMinutes(15),
        [
          'id' => $persona->user->id,
          'hash' => sha1($email),
          'persona' => $persona->id,
        ]
      );
    //create the email
    $themail = new VerifyPersonaEmail($userName, $returnURL);
    //send
    Mail::to($email)->send($themail);
  }
}
