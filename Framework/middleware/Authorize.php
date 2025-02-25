<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
  /**
   * Check if the user is authenticated
   *
   * @return bool
   */
  public function is_authenticated(): bool
  {
    return Session::has('user');
  }

  /**
   * Handle user's request
   *
   * @param string $role
   * @return void
   */
  public function handle(string $role): void
  {
    if ($role == "gust" and $this->is_authenticated()) {
      redirect('/');
      exit;
    } elseif ($role == "auth" and !$this->is_authenticated()) {
      redirect('/auth/login');
      exit;
    }
  }
}
