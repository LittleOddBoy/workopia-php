<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
  protected $routes = [];

  /**
   * Register a route in routes array
   *
   * @param string $method
   * @param string $uri
   * @param string $action
   * @return void
   */
  private function register_route(string $method, string $uri, string $action): void
  {
    list($controller, $controller_method) = explode("@", $action);
    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
      'controller_method' => $controller_method,
    ];
  }

  /**
   * Add a GET route
   *
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function get(string $uri, string $controller): void
  {
    $this->register_route('GET', $uri, $controller);
  }

  /**
   * Add a POST route
   *
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function post(string $uri, string $controller): void
  {
    $this->register_route('POST', $uri, $controller);
  }

  /**
   * Add a PUT route
   *
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function put(string $uri, string $controller): void
  {
    $this->register_route('PUT', $uri, $controller);
  }

  /**
   * Add a DELETE route
   *
   * @param string $uri
   * @param string $controller
   * @return void
   */
  public function delete(string $uri, string $controller): void
  {
    $this->register_route('DELETE', $uri, $controller);
  }

  /**
   * Route the request
   *
   * @param string $req_uri
   * @param string $req_method
   * @return void
   */
  public function route(string $req_uri, string $req_method): void
  {
    foreach ($this->routes as $r) {
      // load the controller of the route if the uri and method matches
      if ($r['uri'] === $req_uri and $r['method'] === $req_method) {
        // Extract controller and controller method
        $controller = 'App\\Controller\\' . $r['controller'];
        $controller_method = $r['controller_method'];

        // instantiate the controller and call the method 
        $controller_instance = new $controller;
        $controller_instance->$controller_method();

        return;
      }
    }

    // load the 404 if the uri and/or method doesn't found
    ErrorController::not_found();
  }
}
