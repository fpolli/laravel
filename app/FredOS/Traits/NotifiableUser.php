<?php

namespace App\FredOS\Traits;

use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Support\Str;

trait NotifiableUser
{
  use HasDatabaseNotifications, RoutesNotifications;

  /**
   * Get the notification routing information for the given driver.
   *
   * @param  string  $driver
   * @param  \Illuminate\Notifications\Notification|null  $notification
   * @return mixed
   */
  public function routeNotificationFor($driver, $notification = null)
  {
    if (method_exists($this, $method = 'routeNotificationFor' . Str::studly($driver))) {
      return $this->{$method}($notification);
    }

    return match ($driver) {
      'database' => $this->notifications(),
      'mail' => $this->login,
      default => null,
    };
  }
}
