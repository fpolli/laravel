<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
  use HasFactory;
  //id
  //work_id
  //publish_at
  //publish_tz

  protected $table = 'publish_schedule';
  protected $guarded = ['id'];

  //  protected $with = ['work'];

  public function work(): BelongsTo
  {
    return $this->belongsTo(Work::class, 'work_id');
  }

  //Accessors/Mutators?
  //must pass a timezone 'publish_tz'
  //may pass either a CarbonImmutable 'datetime' or a 'date' and 'time' strings
  public function setPublishAt(array $elements)
  {
    if (!isset($elements['publish_tz'])) {
      throw new Exception('Must include a time zone with key \'zone\'');
    }
    if (isset($elements['date']) && isset($elements['time'])) {
      $theirtimezonedate = new Carbon("{$elements['date']} {$elements['time']}", $elements['publish_tz']);
      $this->publish_at = $theirtimezonedate->setTimezone('UTC');
    } else if (isset($elements['datetime'])) {
      //assumes the Carbon instance was made with the included zone
      $this->publish_at = $elements['datetime']->setTimezone('UTC');
    } else {
      throw new Exception("Must include a 'date' and 'time' string or a 'datetime' CarbonImmutable");
    }
  }

  protected function casts(): array
  {
    return [
      'publish_at' => 'datetime'
    ];
  }

  public function getTheirPublishTime(): Carbon
  {
    return $this->publish_at->setTimezone($this->publish_tz);
  }
}
