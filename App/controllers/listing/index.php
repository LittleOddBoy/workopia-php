<?php

use Framework\Database;

$config = require base_path('config/db.php');
$db = new Database($config);

$listings = $db->query('SELECT * FROM listings')->fetchAll();

load_view('listings/index', ['listings' => $listings]);
