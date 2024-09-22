<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BodyRule implements ValidationRule
{

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
   * Run the validation rule.
   *
   * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {

    if (empty($value)) {
      //fredLager('empty value');
      $fail('Volume requires a body');
    }

    if (is_string($value)) {
    }

    if (!$this->runContents($value)) {
      $fail('Invalid volume body format');
    }
  }
}
