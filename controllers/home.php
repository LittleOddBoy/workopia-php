<?php
$config = require base_path('config/db.php');
$db = new Database($config);

$listings = $db->query('SELECT * FROM listings LIMIT 2')->fetchAll();

load_view("home");
