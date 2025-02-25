<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
  protected $db;

  public function __construct()
  {
    $config = require(base_path('config/db.php'));
    $this->db = new Database($config);
  }

  /**
   * Show the login page
   * 
   * @return void
   */
  public function login(): void
  {
    load_view('users/login');
  }

  /**
   * Show the register page
   * 
   * @return void
   */
  public function create(): void
  {
    load_view('users/create');
  }

  /**
   * Store user in db
   *
   * @return void
   */
  public function store(): void
  {
    // get the data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    $errors = [];

    // validate email address
    if (!Validation::email($email)) {
      $errors['email'] = "Please enter a valid email address!";
    }

    // validate name string 
    if (!Validation::string($name, min: 2, max: 50)) {
      $errors['name'] = "Name must be between 2 and 50 long!";
    }

    // validate password string
    if (!Validation::string($password, min: 8, max: 50)) {
      $errors['password'] = "Password must be at least 8 and at last 50 characters!";
    }

    // validate password and its confirmation matching
    if (!Validation::match($password, $password_confirmation)) {
      $errors['password_confirmation'] = "Password didn't confirmed correctly!";
    }

    // render the errors if they exist
    if (!empty($errors)) {
      load_view('users/create', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
        ],
      ]);
      exit;
    }

    // check if the email does exist in db
    $search_params = ['email' => $email];
    $user = $this->db->query("SELECT * FROM users WHERE email = :email", $search_params)->fetch();

    if ($user) {
      $errors['email'] = "The email already exists!";
      load_view('users/create', [
        'errors' => $errors,
      ]);
      exit;
    }

    // create user account
    $create_params = [
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'password' => password_hash($password, PASSWORD_DEFAULT),
    ];
    $this->db->query('INSERT INTO users (name, email, city, password) VALUES (:name, :email, :city, :password', $create_params);

    // get new user's id
    $user_id = $this->db->conn->lastInsertId();

    Session::set(key: 'user', value: [
      'id' => $user_id,
      'name' => $name,
      'email' => $email,
      'city' => $city,
    ]);

    redirect('/');
  }

  public function logout(): void
  {
    Session::clear_all();

    $cookie_params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 86400, $cookie_params['path'], $cookie_params['domain']);

    redirect('/');
  }
}
