<?php

namespace App\FredOS\Services;
//this has to change
use App\Models\Content\Work;
use Illuminate\Support\Facades\Cache;

class SeedsGetter
{
  public static function getSite(string $site, string $field = ''): mixed
  {
    $seeder = app(SeedsConfig::class);
    if ($field) {
      return $seeder->getSite($site)->$field;
    }
    return $seeder->getSite($site);
  } //getSite

  public static function getAllSites(): array
  {
    $seeder = app(SeedsConfig::class);
    return $seeder->getSites();
  }

  /* not needed with Site being a class now
  public static function getSiteProfiles(array|string $sites = ''): array
  {
    $seeder = app(SeedsConfig::class);
    $allSites = $seeder->getSites();
    $resp = [];
    if (!$sites) {
      foreach ($allSites as $code => $data) {
        $resp[$code] = [
          'public' => $data['public'],
          'name' => $data['config']['app.name'],
          'domain' => $data['urls'][config('app.fredenv')],
          'description' => $data['config']['app.desc'],
        ];
      }
    } else if (is_array($sites)) {
      foreach ($sites as $site) {
        $resp[$site] = [
          'public' => $allSites[$site]['public'],
          'name' => $allSites[$site]['config']['app.name'],
          'domain' => $allSites[$site]['urls'][config('app.fredenv')],
          'description' => $allSites[$site]['config']['app.desc'],
        ];
      }
    } else {
      $resp = [
        'public' => $allSites[$sites]['public'],
        'name' => $allSites[$sites]['config']['app.name'],
        'domain' => $allSites[$sites]['urls'][config('app.fredenv')],
        'description' => $allSites[$sites]['config']['app.desc'],
      ];
    }
    return $resp;
  }
*/
  /////////////////////////
  //media settings
  public static function getMediaSettings($area = '')
  {
    $seeder = app(SeedsConfig::class);

    if (is_array($area)) {
      $settings = [];
      foreach ($area as $a) {
        $settings[$a] = $seeder->mediaSettings[$a];
      }
      return $settings;
    } else if ($area) {
      return $seeder->mediaSettings[$area];
    }
    return $seeder->mediaSettings;
  }

  public static function getFeatured($dim = '')
  {
    $feat = self::getMediaSettings('featured');
    return ($dim) ? $feat[$dim] : $feat;
  }

  public static function getContent($dim = '')
  {
    $cont = self::getMediaSettings('content');
    return ($dim) ? $cont[$dim] : $cont;
  }

  public static function getAudio($dim = '')
  {
    $aud = self::getMediaSettings('audio');
    return ($dim) ? $aud[$dim] : $aud;
  }

  public static function getAspect($ratio = '')
  {
    $aspct = self::getMediaSettings('aspect');
    return ($ratio) ? $aspct[$ratio] : $aspct;
  }

  public static function getAvatar($dim = '')
  {
    $ava = self::getMediaSettings('avatar');
    return ($dim) ? $ava[$dim] : $ava;
  }

  public static function getAllowed($media = '')
  {
    $allwd = self::getMediaSettings('allowed');
    return ($media) ? $allwd[$media] : $allwd;
  }

  ///////////////////
  //currency
  public static function getCurrencies()
  {
    $seeder = app(SeedsConfig::class);
    return $seeder->currencies;
  }

  ///////////////////
  //advanced getters
  public static function getVolumesList($site, $genre = '')
  {
    $sitevols = self::getSite($site, 'volumes');
    if ($genre) {
      $volgen = $genre;
      $genreVolumes = [];
      foreach ($sitevols as $volume => $config) {
        if (in_array($volgen, $config['genres'])) {
          $genreVolumes[] = $volume;
        }
      }
      return $genreVolumes;
    }
    return array_keys($sitevols);
  }

  /////////////////
  public static function getGenresList($site, $volume = '', $withVolumes = false)
  {
    if ($volume) {
      $sitevols = self::getSite($site, 'volumes');
      $genvol = strtolower($volume);
      if (!isset($sitevols[$genvol])) {
        return [];
      }
      return $sitevols[$genvol]['genres'];
    }
    if ($withVolumes) {
      return array_merge(array_keys(self::getSite($site, 'genres')), self::getVolumesList($site));
    }
    return array_keys(self::getSite($site, 'genres'));
  }

  /////////////////////
  public static function getGenreMimes($site, $genre = '', $withVolumes = false)
  {
    $genresArray = self::getSite($site, 'genres');
    if ($withVolumes) {
      $vols = self::getSite($site, 'volumes');
      foreach ($vols as $vol => $void) {
        $genresArray[$vol] = 'volume';
      }
    }
    if ($genre) {
      return $genresArray[$genre];
    }
    return $genresArray;
  }

  /////////////////
  public static function getGenreListByMime($site, $mime)
  {
    $ret = [];
    if ($mime == 'volume') {
      return self::getVolumesList($site);
    }
    $genresArray = self::getSite($site, 'genres');
    foreach ($genresArray as $key => $value) {
      if ($value == $mime) {
        $ret[] = $key;
      }
    }
    return $ret;
  }

  //////////////////
  public static function getSiteMimes($site)
  {
    $mimes = array_values(array_unique(self::getSite($site, 'genres')));
    $mimes[] = 'volume';
    return $mimes;
  }

  ///////////////////
  public static function getVolumeDNA(string $site, string $volume)
  {
    $sitevols = self::getSite($site, 'volumes');
    return $sitevols[$volume];
  }

  //////////////////
  public static function getCirclesOFTrust()
  {
    $seeder = app(SeedsConfig::class);
    return $seeder->circles;
  }

  public static function getCircle(int $circle)
  {
    $circles = self::getCirclesOFTrust();
    return $circles[$circle];
  }

  //////////////
  public static function getMedia($site)
  {
    return self::getSite($site, 'media');
  }

  //////////////////////////////////
  //TRIGGERS
  public static function getTriggerWarnings(array $triggers = []): array
  {
    $seeder = app(SeedsConfig::class);
    $ret = [];
    if ($triggers) {
      foreach ($triggers as $tre) {
        $ret[$tre] = $seeder->triggers[$tre];
      }
    } else {
      foreach ($seeder->triggers as $tre => $murphy) {
        $ret[$tre] = $murphy;
      }
    }
    return $ret;
  } //getTriggerWarnings


  //returns array of icons wrapped in links
  public static function getIcons(array $triggers = []): array
  {

    $seeder = app(SeedsConfig::class);
    $trigonometry = $seeder->triggers;
    $ret = [];
    $icons = [];
    if ($triggers) {
      foreach ($triggers as $tre) {
        if (!isset($icons[$trigonometry[$tre]['asset']])) {
          $icons[$trigonometry[$tre]['asset']] = [
            'href' => route('fredopedia', ['category' => 'triggers', 'term' => "icon.{$trigonometry[$tre]['asset']}"]),
            'src' => asset("assets/images/icons/fredapps/{$trigonometry[$tre]['asset']}.png"),
            'title' => $trigonometry[$tre]['title']
          ];
        } else {
          $icons[$trigonometry[$tre]['asset']]['title'] .= ", {$trigonometry[$tre]['name']}";
        }
      }
    } else {
      foreach ($trigonometry as $tre => $murphy) {
        if (!isset($icons[$murphy['asset']])) {
          $icons[$murphy['asset']] = [
            'href' => route('fredopedia', ['category' => 'triggers', 'term' => "icon.{$murphy['asset']}"]),
            'src' => asset("assets/images/icons/fredapps/{$murphy['asset']}.png"),
            'title' => $murphy['title']
          ];
        } else {
          $icons[$murphy['asset']]['title'] .= ", {$murphy['name']}";
        }
      }
    }

    foreach ($icons as $key => $data) {
      $ret[$key] = "<a class='trigger-icon-link' href='{$data['href']}' title='{$data['title']}. Click for an explanation'><img class='trigger-icon inline' src='{$data['src']}'></a>";
    }

    return $ret;
  }

  public static function adultIcon(): string
  {
    $seeder = app(SeedsConfig::class);
    $href = route('fredopedia', ['category' => 'triggers', 'term' => 'icon.adult-content']);
    $src = asset('assets/images/icons/fredapps/adult-content.png');
    $ret = "<a class='trigger-icon-link' href='$href' title='This content may not be recommended for children. Click for an explanation'><img class='trigger-icon inline' src='$src'></a>";
    return $ret;
  }

  //returns array of individual trigger terms wrapped in links
  public static function getWarnings(array $triggers = []): array
  {
    $seeder = app(SeedsConfig::class);
    $ret = [];
    $icons = [];
    if ($triggers) {
      foreach ($triggers as $tre) {
        $href = route('fredopedia', ['category' => 'triggers', 'term' => $seeder->triggers[$tre]['name']]);
        $text = $seeder->triggers[$tre]['name'];
        $title = $seeder->triggers[$tre]['title'];
        $ret[$tre] = "<a class='trigger-term-link' href='$href' title='$title. Click for an explanation'>$text</a>";
      }
    } else {
      foreach ($seeder->triggers as $tre => $murphy) {
        $href = route('fredopedia', ['category' => 'triggers', 'term' => $murphy['name']]);
        $text = $murphy['name'];
        $title = $murphy['title'];
        $ret[$tre] = "<a class='trigger-term-link' href='$href' title='$title. Click for an explanation'>$text</a>";
      }
    }

    return $ret;
  }

  public static function adultWarning(): string
  {
    $seeder = app(SeedsConfig::class);
    $href = route('fredopedia', ['category' => 'triggers', 'term' => 'Adult']);
    $ret = "<a class='trigger-term-link' href='$href' title='This content may not be recommended for children. Click for an explanation'>Adult Content</a>";
    return $ret;
  }


  //returns array of trigger postscripts for appending to content
  public static function getPostScripts(array $triggers = []): array
  {
    $seeder = app(SeedsConfig::class);
    $ret = [];
    $icons = [];
    if ($triggers) {
      foreach ($triggers as $tre) {
        if ($seeder->triggers[$tre]['postscript']) {
          $ret[$tre] = $seeder->triggers[$tre]['postscript'];
        }
      }
    } else {
      foreach ($seeder->triggers as $tre => $murphy) {
        if ($murphy['postscript']) {
          $ret[$tre] = $murphy['postscript'];
        }
      }
    }

    return $ret;
  }

  //returns an array of Work instances keyed by medium
  public static function getFeaturedData(string $site = '', string $medium = ''): array
  {
    $seeder = app(SeedsConfig::class);
    $site = ($site) ? $site : config('app.site');

    return ($medium) ? $seeder->featured[$site][$medium] : $seeder->featured[$site];
  }

  //receive data in format medium => featured_array
  public static function setFeaturedData(string $site = '', array $data = []): bool
  {
    $seeder = app(SeedsConfig::class);
    $current = self::getFeaturedData($site);
    foreach ($data as $key => $value) {
      $current[$key] = $value;
    }
    return $seeder->updateFeatured([$site => $current]);
  }

  public static function getFeaturedWorks(string $site = '', string $medium = ''): array
  {
    $fdata = self::getFeaturedData($site, $medium);

    if (!$medium) {
      $featIds = [];
      foreach ($fdata as $key => $value) {
        if ($value['id'] && !in_array($value['id'], $featIds)) {
          $featIds[] = $value['id'];
        }
      }
      $works = Work::whereIn('id', $featIds)->get();
      $featureds = [];
      foreach ($fdata as $med => $values) {
        $featureds[$med] = $values['id'] ? $works->find($values['id']) : null;
      }
      return $featureds;
    }

    $featured['medium'] = $fdata['id'] ? Work::find($fdata['id']) : null;
    return $featured;
  }


  public static function getWelcomeContentModels(string $site)
  {
    //get from cache
    if (!$models = Cache::get("{$site}ContentModels")) {
      return CacheService::refreshContentModelsCache($site);
    }
    return $models;
    //Cache::get("{$site}ContentModels");
  }
}
