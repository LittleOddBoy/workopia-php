<?php

class Router
{
  protected $routes = [];

  /**
   * Register a route in routes array
   *
   * @param string $method
   * @param string $uri
   * @param string $controller
   * @return void
   */
  private function register_route(string $method, string $uri, string $controller): void
  {
    $this->routes = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller
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
   * Load 404 page with proper response code
   *
   * @return void
   */
  private function load_404(): void
  {
    http_response_code(404);
    load_view("error/404");
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
        require(base_path($r['controller']));
        return;
      }
    }

    // load the 404 if the uri and/or method doesn't found
    $this->load_404();
    exit;
  }
}
