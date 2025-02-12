<?php
require '../helpers.php';


$routes = [
  "/" => "controllers/home.php",
  "/listings" => "controllers/listing/index.php",
  "/listings/create" => "controllers/listing/create.php",
  "404" => "controllers/error/404.php"
];

$uri = $_SERVER['REQUEST_URI'];

if (array_key_exists($uri, $routes)) {
  require base_path($routes[$uri]);
} else {
  require base_path($routes['404']);
}
