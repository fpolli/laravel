<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Exception;

class VolumeBodyRule implements Rule
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

  private function runContents($contents)
  {

    foreach ($contents as $key => $val) {
      if (!isset($val['which']) || !in_array($val['which'], ['item', 'group'])) {
        //fredLager(print_r($val,true));
        //fredLager('failed which');
        return false;
      }
      if (!isset($val['index']) || !is_int($val['index'])) {
        //fredLager(print_r($val,true));
        //fredLager('failed index');
        return false;
      }
      if ($val['which'] == 'item') {
        if (!isset($val['genre']) || preg_match('/[^a-zA-Z]/', $val['genre']) !== 0) {
          //fredLager(print_r($val,true));
          //fredLager('failed genre');
          return false;
        }
        if (!isset($val['id']) || !is_int($val['id'])) {
          //fredLager(print_r($val,true));
          //fredLager('failed id');
          return false;
        }
      } else {
        if (!isset($val['title']) || !$val['title']) {
          //fredLager(print_r($val,true));
          //fredLager('failed title');
          return false;
        }
        if (!$this->runContents($val['contents'])) {
          //fredLager(print_r($val,true));
          //fredLager('failed contents');
          return false;
        }
      }
    }

    return true;
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
    //

    if (empty($value)) {
      //fredLager('empty value');
      return true;
    }

    return $this->runContents($value);
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'The Contents JSON did not resolve to an array of the proper structure.';
  }
}
