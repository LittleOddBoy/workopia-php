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

  /**
   * Set a session key/value pair
   * 
   * @param string $key the session key
   * @param mixed $value the key's value 
   * @return void
   */
  public static function set(string $key, mixed $value): void
  {
    $_SESSION[$key] = $value;
  }

  /**
   * Get a session's value by its key
   *
   * @param string $key the session key
   * @param mixed $default the default value that is going to be returned if the session doesn't exist
   * @return mixed
   */
  public static function get(string $key, mixed $default = null): mixed
  {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
  }

  /**
   * Check if the session exits
   *
   * @param string $key the session's key
   * @return boolean
   */
  public static function has(string $key): bool
  {
    return isset($_SESSION[$key]);
  }

  /**
   * Clear a session by its key
   *
   * @param string $key the session's key
   * @return void
   */
  public static function clear(string $key): void
  {
    if (self::has($key)) {
      unset($_SESSION[$key]);
    }
  }

  /**
   * Clear all session data
   *
   * @return void
   */
  public static function clear_all(): void
  {
    session_unset();
    session_destroy();
  }
}
