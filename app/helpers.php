<?php

use App\FredOS\Services\SeedsConfig;

function price($amount, $currency = 'usd', $decimal = false)
{
  switch ($currency) {
    case 'usd':
      $dollars = $amount / 100;
      if ($decimal) {
        $cents = $amount % 100;
        return '$' . "$dollars.$cents";
      }
      return '$' . $dollars;
  }
}

//////////////////////////////
//break string at EOL and wrap in paragraph tags
function toParagraphs(string $inpu)
{

  if ($inpu) {
    $arr = explode(PHP_EOL, $inpu);
    $outArr = [];
    foreach ($arr as $line) {
      if (trim($line)) {
        $outArr[] = "<p>$line</p>";
      }
    }
    return implode(PHP_EOL, $outArr);
  } else {
    return $inpu;
  }
}

function fredLager($brew, $mug = '')
{
  $mug = $mug ? $mug : 'logs/fred.log';
  $mugpath = storage_path($mug);
  //$mugpath = "~/www/newseeds/storage/$mug";
  file_put_contents($mugpath, $brew . PHP_EOL, FILE_APPEND);
}

function array_keys_multi(array $arr): array
{
  $keys = [];
  foreach ($arr as $key => $value) {
    $keys[] = $key;
    if (is_array($value)) {
      $keys = array_merge($keys, array_keys($value));
    }
  }
  return $keys;
}

function icons_url($f)
{
  return url("assets/images/icons/fredapps/$f");
}

//returns a url to the content file storage
function content_url($f)
{
  return url("content/$f");
}

function content_path($f)
{
  return storage_path("app/public/content/$f");
}

//returns a url to the content file storage
function audio_url($f)
{
  return url("content/audio/$f");
}

function audio_path($f)
{
  return storage_path("app/public/content/audio/$f");
}

//returns a url to the content file storage
function image_url($f)
{
  return url("content/image/$f");
}

function image_path($f)
{
  return storage_path("app/public/content/image/$f");
}

//returns a url to the content file storage
function images_url($f)
{
  return url("content/image/$f");
}

function images_path($f)
{
  return storage_path("app/public/content/image/$f");
}

//returns a url to the content file storage
function video_url($f)
{
  return url("content/video/$f");
}

function video_path($f)
{
  return storage_path("app/public/content/video/$f");
}

//returns a url to the ads file storage
//expects the ownerId/fileName
function ad_url($f)
{
  return url("ads/$f");
}

function ad_path($f)
{
  return storage_path("app/public/ads/$f");
}

function user_path($f)
{
  return storage_path("app/public/users/$f");
}

function user_url($f)
{
  return url("users/$f");
}

function avatars_path($id, $f = '')
{
  return storage_path("app/public/users/$id/avatars/$f");
}

function avatars_url($id, $f = '')
{
  return url("users/$id/avatars/$f");
}

function stripe_route()
{
  //get the user object from auth() and create the portal link
  return 'https://billing.stripe.com';
}


//returns the highest content access level for the request user
function CircleOfTrust()
{
  $user = auth()->user();
  if (!$user) {
    return 1;
  }
  switch (true) {
    case (!$user->level): //guest
      return 1;
    case ($user->level < 3): //friend/patron
      return 2;
    case ($user->level < 7): //member
      return 3;
    case ($user->level < 10): //admin
      return 4;
    case ($user->level == 10): //Fred
      return 5;
    default:
      return 1;
  }
}

function current_site()
{
  return app(SeedsConfig::class)->currentSite;
}
