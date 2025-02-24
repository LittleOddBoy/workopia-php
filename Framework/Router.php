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
   * @return void
   */
  public function route(string $req_uri): void
  {
    $req_method = $_SERVER['REQUEST_METHOD'];

    foreach ($this->routes as $r) {
      // split the current URI into segments
      $uri_segments = explode("/", trim($req_uri, "/"));

      // split the route URI into segments
      $route_segments = explode("/", trim($r['uri'], '/'));

      $match = true;
      $params_regex = '/\{(.+?)\}/';


      // check if the number of segments match
      if (
        count($uri_segments) == count($route_segments) and
        strtoupper($r['method']) == $req_method
      ) {
        global $match, $params_regex;
        $params = [];

        $match = true;

        for ($i = 0; $i < count($uri_segments); $i++) {

          // break and leave if the URIs do *not* match and no param is there
          if (
            $route_segments[$i] != $uri_segments[$i] and
            !preg_match($params_regex, $route_segments[$i])
          ) {
            $match = false;
            break;
          }

          // match and set the param to its value
          // ? example: in `/path/to/{here}` -> $params['here'] = its value
          if (preg_match($params_regex, $route_segments[$i], $matches)) {
            $params[$matches[1]] = $uri_segments[$i];
          }
        }

        // load the controller and pass in params if everything is matched
        if ($match) {
          // Extract controller and controller method
            $controller = 'App\\Controller\\' . $r['controller'];
            $controller_method = $r['controller_method'];

            // instantiate the controller and call the method 
            $controller_instance = new $controller;
            $controller_instance->$controller_method($params);

            return;
        }
      }
    }

    // load the 404 if the uri and/or method doesn't found
    ErrorController::not_found();
  }
}
