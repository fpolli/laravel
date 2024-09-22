<?php

namespace App\FredOS\Traits;

use App\Models\Content\Category;
use App\Models\Content\Tag;
use App\View\Components\Content;
use Exception;
use Illuminate\Support\Facades\Blade;

trait AnnotationService
{
  public function createCategory(array $options)
  {
    //blade component create/category
    $html = Blade::renderComponent(new Content\Create\Category($options));
    return $html;
  }

  public function newCategory($props)
  {
    $newcat = new Category($props);
    try {
      $newcat->save();
    } catch (Exception $e) {
      return false;
    }
    return $newcat;
  }

  public function updateCategory(Category $pussy, array $props)
  {
    $pussy->update($props);
    return $pussy;
  }

  public function getCategoryList(array $options)
  {
    $categories = Category::where('site', config('app.site'))->get();
    $html = Blade::renderComponent(new Content\Annotations\CategoryList($categories, $options));
    return $html;
  }

  public function suggestions(string $guess): array
  {

    $suggestions = Tag::where('name', 'like', "$guess%")->limit(10)->get();

    $ret = [];
    if ($suggestions->isNotEmpty()) {
      //loop through and feed the ids into an array
      foreach ($suggestions as $idea) {
        $ret[] = $idea->name;
      }
    }
    return $ret;
  }

  public static function getTagsCount(array $axes = []): array
  {

    $alltags = Tag::withCount('works')
      ->groupBy('tags.id')
      ->orderBy('works_count', 'desc')
      ->limit(50)
      ->get()
      //      ->toArray();
      ->all();

    return $alltags;
  }
}
