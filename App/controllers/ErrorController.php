<?php

namespace App\Controllers;

class ErrorController
{
  /**
   * 404 - not not found
   *
   * @param string $message
   * @return void
   */
  public static function not_found(string $message = "Resource not found") 
  {
    http_response_code(404);
    load_view("error", [
      "status" => "404",
      "message" => $message,
    ]);
  }
}
