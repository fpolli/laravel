<?php

namespace App\FredOS\Tasks;

use App\Models\Content\Schedule;
use App\Models\Content\Work;
use Carbon\Carbon;
use Exception;

class PublishScheduled
{
  public function __invoke()
  {
    $compDate = Carbon::now('UTC');
    $tasks = Schedule::all();

    foreach ($tasks as $task) {
      if ($task->publish_at->lessThanOrEqualTo($compDate)) {
        $task->work->status = 2;
        $task->work->published_at = $task->publish_at;
        $task->work->save();
        $task->delete();
      }
    }
  }
}
