<?php

namespace App\FredOS\Services;

use App\Models\Content\Work;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CacheService
{

  public static function refreshContentModelsCache(string $site = 'moms')
  {
    //    $lock = Cache::lock('modelsLock', 10);
    //    if ($lock->get()) {
    $siteKey = "{$site}ContentModels";
    Cache::forget($siteKey);
    if ($site == 'moms') {
      $media = ['welcome', 'movies', 'music', 'shows', 'games'];
      $contentModels = [];
      $contentAlready = [];
      $newsAlready = [];
      $featuredWorks = SeedsGetter::getFeaturedWorks($site);
      $nextEpisode = Work::where('medium', 'shows')
        ->where('status', 3)
        ->whereNotIn('mime', ['text', 'volume'])
        //          ->orderBy('schedule.publish_at') //ascending
        ->get()
        ->sortBy('schedule.publish_at')
        ->first();

      foreach ($media as $medium) {
        $mediumModels = [];
        $contentAlready[$medium] = [];
        $newsAlready[$medium] = [];
        //get future
        $futurequery = Work::where('status', 3)
          ->whereNot('id', $nextEpisode?->id);
        if ($medium != 'welcome') {
          $futurequery->where('medium', $medium);
        }
        $futureWorks = $futurequery
          ->orderByDesc('id')
          //        ->groupBy('mime')
          ->get();

        //get past
        $pastquery = Work::where('status', '<', 3);
        if ($medium != 'welcome') {
          $pastquery->where('medium', $medium);
        }
        $pastWorks = $pastquery
          //        ->groupBy('mime')
          ->orderByDesc('id')
          ->limit(50)
          ->get();

        if ($featuredWorks[$medium]) {
          $mediumModels['featured'] = $featuredWorks[$medium];
        }

        if ($nextEpisode) {
          $mediumModels['next'] = $nextEpisode;
        }

        if ($futureWorks->isNotEmpty()) {
          $mediumModels['upcoming'] = $futureWorks;
        }

        if ($pastWorks->isNotEmpty()) {
          //featured?
          if (!isset($mediumModels['featured'])) {
            $feat = $medium == 'welcome'
              ? $pastWorks->where('genre', '!=', 'article')
              ->where('status', '=', 2)
              ->sortByDesc('published_at')
              ->first()
              : $pastWorks->where('medium', $medium)
              ->where('genre', '!=', 'article')
              ->where('status', '=', 2)
              ->sortByDesc('published_at')
              ->first();
            if ($feat) {
              $mediumModels['featured'] = $feat;
            }
          }
          //latest
          if (isset($mediumModels['featured'])) {
            $latest = $medium == 'welcome'
              ? $pastWorks->where('genre', '!=', 'article')
              ->where('id', '!=', $mediumModels['featured']->id)
              ->where('status', '=', 2)
              ->sortByDesc('published_at')
              ->first()
              : $pastWorks->where('medium', $medium)
              ->where('id', '!=', $mediumModels['featured']->id)
              ->where('genre', '!=', 'article')
              ->where('status', '=', 2)
              ->sortByDesc('published_at')
              ->first();
            if ($latest) {
              $mediumModels['latest'] = $latest;
            }
          }
          if (isset($mediumModels['featured'])) {
            $contentAlready[$medium][] = $mediumModels['featured'];
          }
          if (isset($mediumModels['latest'])) {
            $contentAlready[$medium][] = $mediumModels['latest'];
          }

          //news
          $newsies = ($medium == 'welcome')
            ? $pastWorks->where('genre', 'article')
            ->where('status', '=', 2)
            ->sortByDesc('published_at')
            ->take(3)
            ->values()
            ->all()
            : $pastWorks->where('medium', $medium)
            ->where('genre', 'article')
            ->where('status', '=', 2)
            ->sortByDesc('published_at')
            ->take(3)
            ->values()
            ->all();
          if ($newsies) {
            $mediumModels['news'] = $newsies;
            foreach ($newsies as $news) {
              $newsAlready[$medium][] = $news->id;
            }
          }
        }

        if (empty($mediumModels)) {
          if ($medium == 'welcome') {
            break;
          } else {
            continue;
          }
        }

        $contentModels[$medium] = $mediumModels;
      } //media
      //place in cache
    } //moms
    Cache::put($siteKey, $contentModels);
    $siteCAKey = "{$site}ContentAlready";
    Cache::put($siteCAKey, $contentAlready);
    $siteNAKey = "{$site}NewsAlready";
    Cache::put($siteNAKey, $newsAlready);
    //    if ($lock->get()) {
    //      $lock->release();
    return $contentModels;
    //    }
  } //refreshContentModelsCache

  public static function initializeContentModelsCache(): void
  {
    Cache::lock('contentModels')->get(function () {
      Cache::forget('contentModels');
      $seeder = app(SeedsConfig::class);
      if ($sites = $seeder->getSites()) {
        foreach ($sites as $site => $data) {
          if (!$data->public) {
            continue;
          }
          self::refreshContentModelsCache($site);
        }
      }
    });
  }
}
