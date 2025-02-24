<?php

namespace App\Controllers;

use Framework\Database;
use App\Controllers\ErrorController;
use Framework\Validation;

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

  /**
   * Store data in the DB
   *
   * @return void
   */
  public function store()
  {
    // filter the data that have being send to get the allowed ones
    $allowed_fields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'phone', 'email', 'requirements', 'benefits'];

    $new_listing_data = array_intersect_key($_POST, array_flip($allowed_fields));
    $new_listing_data['user_id'] = 1;

    // sanitize the input
    $new_listing_data = array_map('sanitize', $new_listing_data);

    $required_fields = ['title', 'description', 'salary', 'email', 'city'];

    $errors = [];

    foreach ($required_fields as $field) {
      if (empty($new_listing_data[$field]) or !Validation::string($new_listing_data[$field])) {
        $errors[$field] = ucfirst($field) . "is required";
      }
    }

    if (!empty($errors)) {
      // reload view with errors
      load_view("listings/create", [
        "errors" => $errors,
        "listings" => $new_listing_data,
      ]);
    } else {
      // submit data

      // create fields
      $fields = [];
      foreach ($new_listing_data as $field => $value) {
        $fields[] = $field;
      }

      $fields = implode(", ", $fields);

      $values = [];
      foreach ($new_listing_data as $field => $value) {
        // convert empty strings to null
        if ($value === "") {
          $new_listing_data[$field] = null;
        }

        $values[] = ":" . $field;
      }

      $values = implode(
        ", ",
        $values
      );

      // create the query
      $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

      // run the query
      $this->db->query($query, $new_listing_data);

      redirect("/listings");
    }
  }
}
