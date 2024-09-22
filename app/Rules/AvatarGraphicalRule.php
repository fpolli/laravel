<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\FredOS\Services\SeedsGetter;

class AvatarGraphicalRule implements Rule
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
    $allowedAvs = SeedsGetter::getMediaSettings('avatar')['allowed'];
    $allowed = SeedsGetter::getAllowed();
    foreach ($allowedAvs as $mimeAv) {
      if (in_array($value->extension(), $allowed[$mimeAv])) {
        return true;
      }
    }
    return false;
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
