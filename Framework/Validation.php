<?php

namespace Framework;

class Validation 
{
  /**
   * Validate a string
   *
   * @param string $value - the value that is going to be validated
   * @param integer $min - required minimum length of value
   * @param integer $max - required maximum length of value
   * @return boolean
   */
  public static function string(string $value, int $min = 1, int $max = 255): bool
  {
    if (is_string($value)) {
      $value = trim($value);
      $length = strlen($value);

      return $length >= $min and $length <= $max;
    }

    return false;
  }

  /**
   * Validate email address
   *
   * @param string $value - the email address that is going to be validated
   * @return mixed
   */
  public static function email(string $value): mixed
  {
    $value = trim($value);

    return filter_var($value, FILTER_VALIDATE_EMAIL);
  }
}