<?php

namespace App\Controllers;

use Framework\Database;
use App\Controllers\ErrorController;
use Framework\Session;
use Framework\Validation;
use Framework\Authorization;

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
    $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();

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
   * @param array $params - the uri's params
   * @return void
   */
  public function show(array $params)
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
    $new_listing_data['user_id'] = Session::get('user')['id'];

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

      // set a flash message
      Session::set_flash_message(
        key: 'success_message',
        message: 'Listing created successfully!'
      );

      redirect("/listings");
    }
  }

  /**
   * Destroy a listing
   *
   * @param array $params - the uri's params
   * @return void
   */
  public function destroy(array $params): void
  {
    $id = $params['id'];
    $search_params = [
      'id' => $id
    ];

    // get the listing to ensure it does exist
    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $search_params)->fetch();

    // throw a not found page if the entity doesn't exist at all
    if (!$listing) {
      ErrorController::not_found("Listing not found");
      return;
    }

    // check if the request is from the owner
    if (!Authorization::is_owner($listing->id)) {
      Session::set_flash_message(
        key: 'error_message',
        message: 'You are not permitted to delete this listing!'
      );
      redirect("/listings/{$listing->id}");
      exit;
    }

    // respect the clean code rules :))
    $delete_params = $search_params;

    // delete the listing 
    $this->db->query("DELETE FROM listings WHERE id = :id", $delete_params);

    // set flash message 
    Session::set_flash_message(
      key: 'success_message',
      message: 'Listing Deleted Successfully!'
    );

    redirect('/listings');
  }

  /**
   * Show the listing edit form
   *
   * @param array $params - the uri's params
   * @return void
   */
  public function edit(array $params)
  {
    $id = $params['id'] ?? "";
    $search_params = ['id' => $id];
    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $search_params)->fetch();

    // check if listing exists
    if (!$listing) {
      ErrorController::not_found("Listing not found!");
      return;
    }

    // check if the request is from the owner
    if (!Authorization::is_owner($listing->id)) {
      Session::set_flash_message(
        key: 'error_message',
        message: 'You are not permitted to update this listing!'
      );
      redirect("/listings/{$listing->id}");
      exit;
    }

    load_view("listings/edit", ['listing' => $listing]);
  }

  /**
   * Update a listings
   *
   * @param array $params
   * @return void
   */
  public function update(array $params): void
  {
    // get the target listing's id
    $id = $params['id'] ?? "";

    // search for the listing
    $search_params = ['id' => $id];
    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $search_params)->fetch();

    // check if listing exists
    if (!$listing) {
      ErrorController::not_found("Listing not found!");
      return;
    }

    // check if the request is from the owner
    if (!Authorization::is_owner($listing->id)) {
      Session::set_flash_message(
        key: 'error_message',
        message: 'You are not permitted to update this listing!'
      );
      redirect("/listings/{$listing->id}");
      exit;
    }

    // filter for allowed fields
    $allowed_fields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'phone', 'email', 'requirements', 'benefits'];
    $updated_values = array_intersect_key($_POST, array_flip($allowed_fields));

    // sanitize all the fields
    $updated_values = array_map('sanitize', $updated_values);

    // set required fields
    $required_fields = ['title', 'description', 'salary', 'email', 'city'];

    // set and show errors if the required fields aren't satisfied
    $errors = [];
    foreach ($required_fields as $field) {
      if (empty($updated_values[$field]) or !Validation::string($updated_values[$field])) {
        $errors[$field] = ucfirst($field) . "is required";
      }
    }

    // pass errors and load edit view if any error does exist
    if (!empty($errors)) {
      load_view('listings/edit', [
        'listing' => $listing,
        'errors' => $errors,
      ]);
      exit;
    } else {
      // get the updated fields ready
      $updated_fields = [];
      foreach (array_keys($updated_values) as $field) {
        $updated_fields = "{$field} = :{$field}";
      }

      $updated_fields = implode(', ', $updated_fields);

      // get the query ready and run it  
      $update_query = "UPDATE listings SET {$updated_fields} WHERE id = :id";
      $updated_values['id'] = $id;
      $this->db->query($update_query, $updated_values);

      // set flash message and redirect
      Session::set_flash_message(
        key: 'success_message',
        message: 'Listing Got Updated'
      );
      redirect("/listings/{$id}");
    }
  }

  /**
   * Search listings by keyword/location
   *
   * @return void
   */
  public function search(): void
  {
    $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';

    $search_params = ['keywords' => "%{$keywords}%", 'location' => "%{$location}%"];
    $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords) AND city LIKE :location";

    $listings = $this->db->query($query, $search_params)->fetchAll();

    load_view('/listings/index', [
      'listings' => $listings,
      'keywords' => $keywords,
      'location' => $location,
    ]);
  }
}
