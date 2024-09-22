<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\FredOS\Services\SeedsGetter;

class VideobodyRule implements Rule
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
    $mimes = SeedsGetter::getAllowed('video');
    if (is_string($value)) {
      $mimeValue = substr($value, strrpos($value, '.') + 1);
      if (!in_array($mimeValue, $mimes)) {
        FredLager("Videobody string failed mime check: $mimeValue");
        return false;
      }
      $basename = substr($value, strrpos($value, '/') + 1);
      if (!file_exists(video_path($basename))) {
        FredLager("Videobody file does not exist: $basename");
        return false;
      }
    } else {
      if (!in_array($value->extension(), $mimes)) {
        FredLager("Videobody file failed extension test");
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
