<?php
require '../helpers.php';
require base_path('Database.php');
require base_path('Router.php');

// instantiate router
$router = new Router();
$routes = require base_path("routes.php");

// get the current URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// setup the router
$router->route($uri, $method);
