<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageSending;

class MailSendingListener
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
  public function handle(MessageSending $event): void
  {
    //
    fredLager('Email being sent at ' . now());
    fredLager(var_dump($event));
  }
}
