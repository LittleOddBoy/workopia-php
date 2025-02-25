<?php

namespace Framework;

use Framework\Session;

class Authorization
{
  /**
   * Check if the current logged in user owns a resource
   *
   * @param int $resource_id
   * @return bool
   */
  public static function is_owner(int $resource_id): bool
  {
    $session_user = Session::get('user');

    if ($session_user !== null and isset($session_user['id'])) {
      $session_user_id = (int) $session_user['id'];
      return $session_user_id === $resource_id;
    }
  }
}
