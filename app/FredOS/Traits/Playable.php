<?php

namespace App\FredOS\Traits;

use Illuminate\Support\Str;
use App\Freds\FredStrings as Fred;
use App\View\Components\FredApps\TheFredPlaya;
use Illuminate\Support\Facades\Blade;

trait Playable
{

  protected function createChaptersJSON(array $chaps): string
  {
    $chapters = [];
    foreach ($chaps as $olchap) {
      $ch = [];
      $strt = strpos($olchap, 'data-chapter=') + 15;
      $num = substr($olchap, $strt, strpos($olchap, '"', $strt) - $strt);
      $strt = strpos($olchap, 'data-start=') + 13;
      $ch['start'] = substr($olchap, $strt, strpos($olchap, '"', $strt) - $strt);
      $strt = strpos($olchap, 'title=') + 8;
      $pcs = explode(' - ', substr($olchap, $strt, strpos($olchap, '"', $strt) - $strt));
      if (isset($pcs[1]) && $pcs[1]) {
        $ch['description'] = $pcs[1];
      }
      $ch['name'] = substr($pcs[0], strpos($pcs[0], "Ch $num ") + 1);
      $chapters[$num] = $ch;
    }

    return json_encode($chapters);
  }

  public function stripPlayas(string $body): string
  {
    $body = str_replace(['&lt;', '&gt;'], ['<', '>'], $body);

    //get fredplaya matches
    $playas = Fred::stringMatches($body, '<div class="fred-playa', '<!--fred-playa-->');
    $screens = Fred::stringBetweens($body, '<div class="silver-screen', '</div>');
    $sounds = Fred::stringBetweens($body, '<div class="sound-system', '</div>');
    $replacementStrings = [];
    foreach ($playas as $idx => $playa) {
      //look for chapters in the playa
      $chapters = Fred::stringMatches($playa, '<div class="playa-chapter-marker', "</div>");
      $chaptersJSONString = '';
      if (!empty($chapters)) {
        $chaptersJSONString = $this->createChaptersJSON($chapters); //must include leading comma
      }
      //get the screen and sound for this playa
      $posterString = '';
      if (strpos($screens[$idx], "<video") !== false) {
        $mime = 'video';
        $srcStart = strpos($screens[$idx], 'src=') + 5;
        $mainstring = substr($screens[$idx], $srcStart, strpos($screens[$idx], '"', $srcStart) - $srcStart);
        if (($pstrstrt = strpos($screens[$idx], "poster="))) {
          $posterStart = $pstrstrt + 8;
          $posterString = substr($screens[$idx], $posterStart, strpos($screens[$idx], '"', $posterStart) - $posterStart);
        }
      } else {
        $mime = 'audio';
        if (strpos($screens[$idx], "<img") !== false) {
          $posterStart = strpos($screens[$idx], 'src=') + 5;
          $posterstring = substr($screens[$idx], $posterStart, strpos($screens[$idx], '"', $posterStart) - $posterStart);
        }
      }
      if (strpos($sounds[$idx], "<audio") !== false) {
        $srcStart = strpos($sounds[$idx], 'src=') + 5;
        $mainstring = substr($sounds[$idx], $srcStart, strpos($sounds[$idx], '"', $srcStart) - $srcStart);
      }
      //craft a JSON string of the Playa props |playaProps={ json }|
      $jsonString = "|playaProps={ \"mime\": \"$mime\", \"main\": { \"url\": \"$mainstring\"}, \"poster\": { \"url\": \"$posterString\"}$chaptersJSONString }|";
      //add to replacementstrings

      $replacementStrings[] = $jsonString;
    }

    //str_replace
    $newbody = str_replace($playas, $replacementStrings, $body);

    return $newbody;
  }

  public function addPlayas(string $body): string
  {
    $matches = Fred::stringMatches($body, "|playaProps=", "}|");
    if (!empty($matches)) {
      $playas = [];
      foreach ($matches as $code) {
        $json = substr($code, 12, strlen($code) - 13);
        $props = json_decode($json, true);
        $playas[] = Blade::renderComponent(new TheFredPlaya($props));
      }
      $newbody = str_replace($matches, $playas, $body);
      return $newbody;
    }

    return $body;
  }
}
