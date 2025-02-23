<?php

use Framework\Database;

$config = require base_path('config/db.php');
$db = new Database($config);

$id = $_GET['id'] ?? "";
$params = ['id' => $id];
$listing = $db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

load_view("listings/show", ['listing' => $listing]);
