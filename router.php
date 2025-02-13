<?php

$routes = require base_path('routes.php');

if (array_key_exists($uri, $routes)) {
  require base_path($routes[$uri]);
} else {
  require base_path($routes['404']);
}
