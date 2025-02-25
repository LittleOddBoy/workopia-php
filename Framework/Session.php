<?php

namespace Framework;

class Session
{
  /**
   * Start a session
   *
   * @return void
   */
  public static function start(): void
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }
}
