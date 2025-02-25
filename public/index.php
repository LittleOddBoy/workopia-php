<?php
require __DIR__ . "/../vendor/autoload.php";
require '../helpers.php';

use Framework\Router;
use Framework\Session;

// start the session
Session::start();

// ! custom (previous and old) auto-loader
// spl_autoload_register(function ($class) {
//   $path = base_path("Framework/" . $class . ".php");
//   if (file_exists($path)) {
//     require $path;
//   }
// });

// instantiate router
$router = new Router();
$routes = require base_path("routes.php");

// get the current URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// setup the router
$router->route($uri);
