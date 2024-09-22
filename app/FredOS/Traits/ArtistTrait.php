<?php

namespace App\FredOS\Traits;

use App\FredOS\Services\SeedsGetter;
use App\View\Components\Fredapps\FredApi;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use App\View\Components\Library;

trait ArtistTrait
{
  //
  //  public function getUnsplashRequestForm(): string
  //  {

  //    $html = Blade::renderComponent(new FredApi\UnsplashSearch());

  //    return $html;
  //  }

  public function getPixabayRequestForm(string $mime, string $context): string
  {

    $html = Blade::renderComponent(new FredApi\PixabaySearch($mime, $context));

    return $html;
  }

  public function getAiRequestForm(string $mime): string
  {

    $html = Blade::renderComponent(new FredApi\AiPrompt($mime));

    return $html;
  }

  public function craftPixabayApiCall(array $inputs)
  {
    //get api key
    $api_key = config('app.pixabay_key');
    //craft query string
    $contextSettings = SeedsGetter::getMediaSettings($inputs['context']);
    $params = [];
    $params['key'] = $api_key;
    $params['q'] = urlencode($inputs['keyword']);
    if (isset($inputs['category']) && $inputs['category']) {
      $params['category'] = $inputs['category'];
    }
    if ($contextSettings) {
      foreach ($contextSettings as $key => $value) {
        $key = strtolower($key);
        if (strpos($key, 'width') !== false && strpos($key, 'max') === false) {
          $params['min_width'] = $value;
        } else if (strpos($key, 'height') !== false && strpos($key, 'max') === false) {
          $params['min_height'] = $value;
        }
      }
    }
    $params['page'] = $inputs['page'];
    $params['per_page'] = 50;
    $url = $inputs['mime'] == 'video' ? 'https://pixabay.com/api/videos/' : 'https://pixabay.com/api/';
    $response = Http::get($url, $params);
    return $response;
  }

  public function pixabayGallery(array $pixa, array $props)
  {
    $data = [];
    $items = [];
    foreach ($pixa['hits'] as $hit) {
      $item = [];
      $item['size'] = 'restricted';
      $item['aspect'] = 'gallery-item';
      $item['credit'] = $this->pixabayCredit($hit['user_id'], $hit['user']);
      switch($props['context']) {
        case 'featured':
          $item['caption'] = 
          break;

        default:
          $item['caption'] = $this->pixabayCaption($props['mime'], $hit['type'], $hit['tags'], $hit['imageURL']);
          break;
      }
      switch ($props['mime']) {
        case 'image':
          $item['mime'] = 'image';
          $item['link'] = $hit['pageURL'];
          $item['url'] = $hit['previewURL'];
          break;

        case 'video':
          break;
      }

      $items[] = $item;
    }
    $data['items'] = $items;

    $data['settings'] = $props;

    $html = Blade::renderComponent(new Library\Gallery($data));

    return $html;
  }

  public function pixabayCredit($id, $name)
  {
    $credit = '';
    $profilelink = "https://pixabay.com/users/$name-$id/";
    $credit = "<a href='$profilelink'>by$name</a> on <a href='https://pixabay.com'>Pixabay</a>";

    return $credit;
  }

  public function pixabayCaption(string $mime, string $mediaType, string $tags, string $url)
  {
    $caption = '';
    $tags_array = explode(', ', $tags);
    if (count($tags_array)) {
      $tag_links = [];
      foreach ($tags_array as $tag) {
        $tags_links[] = "<a href='https://pixabay.com/{$mime}s/search/{$tag}/' target='_blank'>{$tag}</a>";
      }
    }
    $caption = "<a href='$url' target='_blank'>$mime of type $mediaType</a> with tags " . implode(', ', $tags_links);

    return $caption;
  }

  public function pixabayLink() {}
}
