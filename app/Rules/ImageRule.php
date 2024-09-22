<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\FredOS\Services\SeedsGetter;

class ImageRule implements Rule
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
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    //
    if ($value === '') {
      return true;
    }

    $mimes = SeedsGetter::getAllowed('image');
    if (is_string($value)) {
      $mimeValue = substr($value, strrpos($value, '.') + 1);
      if (!in_array($mimeValue, $mimes)) {
        FredLager("image string failed mime check: $mimeValue");
        return false;
      }
      $basename = substr($value, strrpos($value, '/') + 1);
      if (!file_exists(image_path($basename))) {
        FredLager("image file does not exist: $basename");
        return false;
      }
    } else {
      if (!in_array($value->extension(), $mimes)) {
        FredLager("image file failed extension test");
        return false;
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
    return 'The validation error message.';
  }
}
