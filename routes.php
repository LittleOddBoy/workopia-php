<?php

$router->get('/', 'controllers/home.php');
$router->get('/listings', 'controllers/listing/index.php');
$router->get('/listings/create', 'controllers/listing/create.php');
