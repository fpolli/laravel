<?php

namespace App\FredOS\Tasks;

use App\FredOS\Services\CacheService;
use App\FredOS\Services\SeedsConfig;
use App\FredOS\Services\SeedsGetter;
use App\Models\Content\Schedule;
use App\Models\Content\Work;
use Carbon\Carbon;
use Exception;

class PurgeExpiredFeatured
{
  public function __invoke()
  {
    $compDate = Carbon::now('UTC');
    $allfeatureds = SeedsGetter::getFeaturedData();

    $changedAny = false;
    $newfeatureds = [];
    foreach ($allfeatureds as $site => $tasks) {
      $newfeatureds[$site] = [];
      foreach ($tasks as $medium => $task) {
        if (!$task['date']) {
          continue;
        }
        $taskdate = new Carbon($task['date'], 'UTC');
        if ($taskdate->lessThanOrEqualTo($compDate)) {
          $newfeatureds[$site][$medium] = [
            'id' => 0,
            'mime' => '',
            'date' => ''
          ];
          $changedAny = true;
        } else {
          $newfeatureds[$site][$medium] = $task;
        }
      }
    }

    if ($changedAny) {
      $worker = app(SeedsConfig::class);
      $worker->updateFeatured($newfeatureds);
      CacheService::initializeContentModelsCache();
    }
  }
}
