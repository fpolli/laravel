<?php

namespace App\Listeners;

use App\FredOS\Services\CacheService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateContentCache
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
  public function handle(object $event): void
  {
    //
    CacheService::refreshContentModelsCache($event->site);
  }
}
