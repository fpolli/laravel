<?php

namespace App\FredOS\Services;

use App\Models\Site;
use Illuminate\Support\Str;
use Exception;

class SeedsConfig
{
  private array $sites;
  private array $lookupTable = [];
  //  private array $routeData; current_site()->routeData
  //  public array $mediaSettings; pulled in as config('media')
  public array $circles;
  public array $currencies;
  public array $mediaSettings;
  public array $triggers;
  public array $featured;
  public Site $currentSite;

  public function __construct()
  {
    $seedSites = [];
    try {
      $seedSites = Site::where('owner_id', 0)->get()->all();
    } catch (Exception $e) {
      die($e->getMessage());
    }

    $this->lookupTable['localhost'] = config('app.site');
    if ($seedSites) {
      foreach ($seedSites as $site) {
        $this->sites[$site->code] = $site;
        $this->lookupTable[$site->domain] = $site->code;
        $this->lookupTable[$site->local_url] = $site->code;
        $this->lookupTable[$site->development_url] = $site->code;
      }

      $this->currentSite = $this->lookupTable[request()->host];
      $this->circles = require(base_path('/.seedscfg/circles.php'));
      $this->currencies = require(base_path('/.seedscfg/currencies.php'));
      $this->mediaSettings = require(base_path('/.seedscfg/media.php'));
      $this->triggers = require(base_path('/.seedscfg/triggers.php'));

      $this->setFeatured();
    }
  }

  public function Lookup($key)
  {
    return $this->lookupTable[$key];
  }

  public function getSites()
  {
    return $this->sites ?? [];
  }

  public function getSite($site)
  {
    try {
      return $this->sites[$site];
    } catch (Exception $e) {
      return null;
    }
  }

  public function setFeatured()
  {
    $featpath = database_path('featured.json');
    $featJson = file_get_contents($featpath);
    $this->featured = json_decode($featJson, true);
  }

  //receive array in format site => featured_array
  public function updateFeatured(array $newdata)
  {
    $newfeatured = $this->featured;
    foreach ($newdata as $site => $value) {
      $newfeatured[$site] = $value;
    }
    $this->featured = $newfeatured;
    $featpath = database_path('featured.json');
    $featJson = json_encode($newdata, JSON_PRETTY_PRINT);
    return file_put_contents($featpath, $featJson);
  }
}
