<?php

namespace App\Controllers;

use Framework\Database;
use App\Controllers\ErrorController;

class ListingController
{
  protected $db;
  public function __construct()
  {
    $config = require base_path('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Show all the listings
   *
   * @return void
   */
  public function index()
  {
    $listings = $this->db->query('SELECT * FROM listings')->fetchAll();

    load_view("listings/index", [
      "listings" => $listings,
    ]);
  }

  /**
   * Create a new listing
   *
   * @return void
   */
  public function create()
  {
    load_view("listings/create");
  }

  /**
   * Show a single specific listing
   *
   * @return void
   */
  public function show($params)
  {
    $id = $params['id'] ?? "";
    $search_params = ['id' => $id];
    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $search_params)->fetch();

    // check if listing exists
    if (!$listing) {
      ErrorController::not_found("Listing not found!");
      return;
    }

    load_view("listings/show", ['listing' => $listing]);
  }
}
