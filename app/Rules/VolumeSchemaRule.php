<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Exception;

class VolumeSchemaRule implements Rule
{
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  array  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    //fredLager($value);
    //fredLager(print_r($value,true));
    if (!isset($value['items']) || !isset($value['groups'])) return false;

    foreach ($value['items'] as $key => $arr) {
      //key can only have letters and be between 2 and 15 characters in length
      if (!preg_match("/[a-zA-Z]{2,15}/", $key)) return false;
      //must have an indexStyle property and must have two properties (flags is the other)
      if (
        !isset($arr['indexStyle']) ||
        !isset($arr['genres']) ||
        (count($arr) == 3 && !isset($arr['flags'])) ||
        count($arr) > 3
      ) return false;
      //if indexStyle must have these and only these elements
      if (!in_array($arr['indexStyle'], ['1', 'One', 'I', 'i', 'A', 'a', 'None'])) return false;
      foreach ($arr['genres'] as $g) {
        if (!preg_match("/[a-zA-Z]{2,15}/", $g)) return false;
      }
      if (isset($arr['flags'])) {
        foreach ($arr['flags'] as $f) {
          if (!is_bool($f)) {
            return false;
          }
        }
      }
    }

    foreach ($value['groups'] as $idx => $level) {
      foreach ($level as $name => $data) {
        if (!preg_match("/[a-zA-Z]{2,15}/", $name)) return false;
        //must have an indexStyle property and must have two properties (flags is the other)
        if (
          !isset($data['indexStyle']) ||
          !isset($data['flags']) ||
          !isset($data['items']) ||
          !isset($data['groups']) ||
          count($data) > 4
        ) return false;
        if (!in_array($data['indexStyle'], ['1', 'One', 'I', 'i', 'A', 'a', 'None'])) return false;
        if (array_keys($data['flags']) != ["showIndex", "showTitle", "showBlurb", "newPage", "restartNumbers"]) {
          return false;
        }
        foreach ($data['flags'] as $f) {
          if (!is_bool($f)) {
            return false;
          }
        }
        if ((array_keys($data['items']) != ['required', 'allowed']) || (array_keys($data['groups']) != ['required', 'allowed'])) {
          return false;
        }
        if (
          !empty($data['items']['required']) &&
          !empty(array_diff($data['items']['required'], array_keys($value['items'])))
        ) {
          return false;
        }
        if (
          !empty($data['items']['allowed']) &&
          !empty(array_diff($data['items']['allowed'], array_keys($value['items'])))
        ) {
          return false;
        }
        if (
          !empty($data['groups']['required']) &&
          !empty(array_diff($data['groups']['required'], array_keys($value['groups'][$idx + 1])))
        ) {
          return false;
        }
        if (
          !empty($data['groups']['allowed']) &&
          !empty(array_diff($data['groups']['allowed'], array_keys($value['groups'][$idx + 1])))
        ) {
          return false;
        }
      }
    }

    return true;
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'The Schema JSON did not resolve to an array of the proper structure.';
  }
}
