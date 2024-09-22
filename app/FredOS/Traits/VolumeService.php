<?php

namespace App\FredOS\Traits;

use App\Events\WelcomeContentChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use App\View\Components;
use App\FredOS\Services\SeedsGetter;
use App\Models\Content\Work;
use App\Models\Content\Volume;
use Exception;
use Carbon\Carbon;

trait VolumeService
{

  //uses index to search contents for entry
  public function getEntryData(Volume $volume, int $index): array
  {
    $data = [];
    $rawentry = $this->searchContents($volume->body, ['index' => $index]);
    $data['entry'] = $this->injectFlagsInEntry($volume->dna, $rawentry);
    $data['entry']['volume'] = $volume->id;
    $data['navigation'] = $this->makeNavigationDataArray($volume, $data['entry']);
    $data['object'] = ($data['entry']['which'] == 'item') ? Work::find($data['entry']['id']) : null;
    if ($data['entry']['which'] == 'item' && !$data['object']) {
      throw new Exception('Content item not found from info given');
    }

    return $data;
  }

  //uses content id to search contents for entry
  public function getEntry(Volume $volume, int $entryId): array
  {
    //searchContents
    $entry = $this->searchContents($volume->body, ['id' => $entryId]);
    if (!$entry) {
      throw new Exception('Entry not found for content id');
    }
    //injectFlags
    $entry = $this->injectFlagsInEntry($volume->dna, $entry);
    $entry['volume'] = $volume->id;
    //return
    return $entry;
  }

  public function makeNavigationDataArray(Volume $volume, array $entry): array
  {
    $ret = [];
    $thisindex = $entry['index'];
    //if series->covers
    $ret['covers'] = [];
    //when lay in the feature, will put in the 'front' and 'back' fields

    $ret['title'] = $volume->title;
    $ret['volume'] = $volume->genre;
    $ret['mime'] = $volume->mime;
    $ret['slug'] = $volume->slug;
    //make breadcrumbs
    $ret['breadcrumbs'] = $this->hansel($volume->body, $thisindex, []);

    //flatten contents
    $flat = $this->unspoolContents($volume->body);
    //get previous entry (item or group if group has a "newPage")
    $i = $thisindex - 1;
    $prev = [];
    while ($i > 0) {
      if ($flat[$i]['which'] == 'item' || in_array('newPage', $flat[$i]['flags'])) {
        $prev = $flat[$i];
        break;
      }
      $i--;
    }
    $ret['previous'] = $prev;
    //feed $entry into thisEntry
    $ret['thisEntry'] = $entry;
    //get next entry (item or group if group has a "newPage")
    $i = $thisindex + 1;
    $next = [];
    $c = count($flat);
    while ($i <= $c) {
      if ($flat[$i]['which'] == 'item' || in_array('newPage', $flat[$i]['flags'])) {
        $next = $flat[$i];
        break;
      }
      $i++;
    }
    $ret['next'] = $next;

    return $ret;
  }

  public function injectFlagsInEntry(array $schema, array $entry): array
  {
    if ($entry['which'] == 'item') {
      if (isset($schema['items'][$entry['ifredifier']]['flags'])) {
        $entry['flags'] = $schema['items'][$entry['ifredifier']]['flags'];
      }
    } else {
      if (isset($schema['groups'][$entry['level']][$entry['ifredifier']]['flags'])) {
        $entry['flags'] = $schema['groups'][$entry['level']][$entry['ifredifier']]['flags'];
      }
      $entry['contents'] = $this->injectFlagsInContents($schema, $entry['contents']);
    }

    return $entry;
  }

  public function injectFlagsInContents(array $schema, array $contents): array
  {
    $ret = [];
    foreach ($contents as $entry) {
      $ret[] = $this->injectFlagsInEntry($schema, $entry);
    }

    return $ret;
  }

  //////////////////////////////////////
  //lays down a trail of breadcrumbs
  protected function hansel(array $contents, int $thisindex, array $crumbs): array
  {
    foreach ($contents as $key => $value) {
      if ($value['index'] == $thisindex) {
        return $crumbs;
      }
      if ($value['which'] == 'group') {
        $crumbs[] = $value;
        $crumbs = $this->hansel($value['contents'], $thisindex, $crumbs);
        if (!empty($crumbs)) {
          return $crumbs;
        }
      }
    }
    return [];
  } //hansel

  //returns first that meets all conditions
  public function searchContents(array $contents, array $conditions): array|bool
  {
    foreach ($contents as $thing) {
      $passed = false;
      foreach ($conditions as $key => $value) {
        if (!isset($thing[$key]) || $thing[$key] !== $value) {
          $passed = false;
          break;
        }
        $passed = true;
      }
      if ($passed) {
        return $thing;
      }
      if ($thing['which'] == 'group') {
        $result = $this->searchContents($thing['contents'], $conditions);
        if ($result) {
          return $result;
        }
      }
    }
    return false;
  }

  public function unspoolContents(array $contents, array $unspooled = []): array
  {
    foreach ($contents as $entry) {
      if (!isset($entry['index']) || !$entry['index']) {
        throw new Exception('no valid content index');
      }
      if ($entry['which'] === 'item') {
        $unspooled[$entry['index']] = $entry;
      } elseif ($entry['which'] === 'group') {
        if (!isset($entry['contents']) || !is_array($entry['contents'])) {
          throw new Exception('Invalid contents');
        }
        if (count($entry['contents'])) {
          $temp = $entry;
          $temp['contents'] = [];
          foreach ($entry['contents'] as $kid) {
            $temp['contents'][] = $kid['index'];
          }
          $unspooled[$entry['index']] = $temp;
          $unspooled = $this->unspoolContents($entry['contents'], $unspooled);
        } else {
          $unspooled[$entry['index']] = $entry;
        }
      } else {
        throw new Exception('Invalid which');
      }
    }
    return $unspooled;
  }

  //////////////////////////////////////
  public function createDNAResponse(Volume $work)
  {

    //see javascript - adding click to submit button
    //modules/pages/create/contentForm.js in the 
    //featuredMediaSuccess function

    //also need to go WorksService step two response function and change the 
    //step values back

    //    return $this->noSchemaResponseStep($work);

    //because all schema is drawn from the sites file currently, it really
    //does not allow for modification. Unless and until we create a
    //customizable volume type, the step is pointless, confusing and likely
    //to cause errors
    //so for now, it is skipped but can easily be re-introduced



    $resp = [];
    //    $resp['id'] = $work->getId();
    //    $resp['slug'] = $work->getSlug();
    //    $resp['ucc_id'] = $work->getUccId();
    $resp['status'] = $work->status;

    $resp['step'] = [
      'value' => 'volume-dna',
      'text' => "Next: Contents"
    ];
    $rowContent = Blade::renderComponent(new Components\Content\Create\Modules\SeriesCreator($work, 'dna'));
    $resp['html'] = Blade::renderComponent(new Components\MainContentRow(2, $rowContent));
    $site = config('app.site');
    $dna = $work->dna ?: SeedsGetter::getVolumeDNA($site, $work->genre);

    $resp['dna'] = $dna;

    return $resp;
  } //createDNAResponse

  private function noSchemaResponseStep(Volume $work)
  {

    $resp = [];
    $resp['id'] = $work->id;
    $resp['slug'] = $work->slug;
    $resp['ucc_id'] = $work->ucc_id;
    $resp['status'] = $work->status;

    $resp['step'] = [
      'value' => 'volume-contents',
      'text' => "Next: Publish"
    ];
  }

  ////////////////////////////
  public function createContentsResponse(Volume $work)
  {
    $resp = [];
    $resp['id'] = $work->id;
    $resp['slug'] = $work->slug;
    $resp['ucc_id'] = $work->ucc_id;
    $resp['status'] = $work->status;

    $resp['step'] = [
      'value' => 'volume-contents',
      'text' => "Next: Publish"
    ];
    $rowContent = Blade::renderComponent(new Components\Content\Create\Modules\SeriesCreator($work, 'contents'));
    $resp['html'] = Blade::renderComponent(new Components\MainContentRow(3, $rowContent));
    //    $site = config('app.site');
    //    $resp['dna'] = ($work->getDnaString()) ? $work->getDnaString() : SeedsGetter::getVolumeDNA($site, $volume);

    return $resp;
  } //createContentsResponse

  //////////////////////////////
  public function publish($props)
  {
    if ($props['status'] == 3) {
      //schedule
      $this->schedulePublication($props);
    } else if ($props['status'] == 2) {
      //set published_at
      $props['published_at'] = Carbon::now();
    } else {
      throw new Exception('Wrong status on volume publish');
    }
    //publish
    $work = Work::find($props['work_id']);
    $work->update($props);
    $work->save();
    $contItems = $this->contentItems($work->body);
    //save to volumes_items table
    $this->updateJoinTable($work->id, $contItems);
    if ($props['saveEntries'] == 'yes') {
      $this->publishItems($contItems);
    }

    event(new WelcomeContentChange(config('app.site')));
    return $work;
  } //publish

  //////////////////////////
  public function contentItems($contents)
  {
    $ret = [];
    foreach ($contents as $entry) {
      if ($entry['which'] == 'item') {
        $ret[] = $entry;
      } else {
        $ret = array_merge(
          $ret,
          $this->contentItems($entry['contents'])
        );
      }
    }
    return $ret;
  } //contentItems

  /////////////////////////
  //must be passed a complete items list
  protected function updateJoinTable($volId, $items)
  {
    //select all entries for the volume
    $dbItems = DB::table('volumes_items')
      //      ->select('genres_id')
      ->where('volume_id', $volId)
      //      ->get()
      ->pluck('item_id')
      ->toArray();
    $dbids = array_values($dbItems);
    $itemadds = [];
    foreach ($items as $item) {
      $itemadds[$item['id']] = $item['genre'];
    }
    $adds = [];
    $toadd = array_diff(array_keys($itemadds), $dbids);
    foreach ($toadd as $t) {
      $adds[] = ['volume_id' => $volId, 'item_id' => $t];
    }
    $removals = array_diff($dbids, array_keys($itemadds));
    //run the contents and make an entry for each not in the select results
    //run the insert statement
    DB::table('volumes_items')->insert($adds);
    $deleted = DB::table('volumes_items')
      ->where('volume_id', $volId)
      ->whereIn('item_id', $removals)
      ->delete();
  } //updateJoinTable

  /////////////////////////////
  //takes a contentItems array
  protected function publishItems($arr)
  {
    foreach ($arr as $entry) {
      $ent = Work::find($entry['id']);
      if (!$ent) {
        throw new Exception('Volume contents item not found from id');
      }
      if ($ent->status == 1) {
        $ent->status = 2;
        $ent->published_at = Carbon::now();
        $ent->save();
      }
    }
  } //publishItems

  public function allItemIds(array $haystack, $needle): array
  {
    $found = [];
    array_walk_recursive($haystack, function ($value, $key) use (&$found, $needle) {
      if ($key == $needle)
        $found[] = $value;
    });
    return $found;
  }

  public function newItem(Request $request)
  {

    $resp = [];
    $props = [];
    $props['which'] = 'item';
    $props['ifredifier'] = $props['ifredifierIndex'] = $props['ifredifierStyle'] = $props['genre'] = $props['desc'] = $props['index'] = '';
    $addid = $request->input('addid', 0);
    if ($addid) {
      $props['id'] = $addid;
      $item = Work::find($props['id']);
      if (!$item) {
        throw new Exception('Add Id did not retrive Genre object');
      }
      $props['title'] = $item->title;
      $props['genre'] = $item->genre;
    } else {
      $props['id'] = 0;
      $props['title'] = 'Click to select a content item';
      $vol = Work::find($request->input('id'));
      $allowedGenres = SeedsGetter::getGenresList(config('app.site'), $vol->genre);
      $itemIds = [];
      if ($request->input('body')) {
        $volbody = $request->input('body');
        $vol->body = $volbody;
        $vol->update();
        $itemIds = $this->allItemIds($volbody, 'id');
      }
      $itemsQuery = DB::table('works')
        ->select(['id', 'title', 'author_name'])
        ->whereIn('genre', $allowedGenres);
      if ($itemIds) {
        $itemsQuery = $itemsQuery->whereNotIn('id', $itemIds);
      }
      $props['last-20'] = $itemsQuery->orderByDesc('id')
        ->limit(20)
        ->get();
    }
    $resp['html'] = Blade::renderComponent((new Components\Fredapps\SeriesFreditor\ContentItem($props)));

    return $resp;
  }

  public function itemData(Request $request)
  {
    $itemid = intval($request->itemId, 10);
    $item = Work::find($itemid);
    $resp = [];
    //genre, genreid,title
    if ($item) {
      $resp['genre'] = $item->genre;
      $resp['id'] = $item->id;
      $resp['title'] = $item->title;
    } else {
      $resp['error'] = 'Item not found in database';
    }
    return $resp;
  }

  public function newGroup(Request $request)
  {
    $resp = [];
    $props = [];
    $props['which'] = 'group';
    $props['level'] = $request->level;
    $props['next-level'] = $props['level'] + 1;
    $props['add-group'] = ($request->level < $request->maxlevel);
    $props['ifredifier'] = $props['ifredifierIndex'] = $props['ifredifierStyle'] = $props['genre'] = $props['desc'] = $props['index'] = '';
    $props['id'] = 0;
    $props['title'] = 'Replace this with a title';

    $resp['html'] = Blade::renderComponent((new Components\Fredapps\SeriesFreditor\ContentItem($props)));

    return $resp;
  }

  public function craftGenreFreditor(array $props)
  {
    if ($props['itemId']) {
      $srcurl = route('content.edit', ['mime' => $props['mime'], 'id' => $props['itemId'], 'volume' => $props['volumeId']]);
    } else {
      $srcurl = route('content.create', ['volume' => $props['volumeId']]);
    }
    return "<iframe src='$srcurl' class='fredapp-frame genre-freditor'></iframe>";
  }
}
