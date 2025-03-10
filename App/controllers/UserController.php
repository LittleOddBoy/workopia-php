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
      'fullname' => $name,
      'email' => $email,
      'city' => $city,
      'user_password' => password_hash($password, PASSWORD_DEFAULT),
    ];
    $this->db->query('INSERT INTO users (`fullname`, `email`, `city`, `user_password`) VALUES (:fullname, :email, :city, :user_password)', $create_params);

    // get new user's id
    $user_id = $this->db->conn->lastInsertId();

    // set user session and login
    Session::set(key: 'user', value: [
      'id' => $user_id,
      'name' => $name,
      'email' => $email,
      'city' => $city,
    ]);

    redirect('/');
  }

  /**
   * Log the user out
   *
   * @return void
   */
  public function logout(): void
  {
    Session::clear_all();

    $cookie_params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 86400, $cookie_params['path'], $cookie_params['domain']);

    redirect('/');
  }

  /**
   * Login and authenticate a user
   *
   * @return void
   */
  public function authenticate(): void
  {
    // get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];

    // validate the email address
    if (!Validation::email($email)) {
      $errors['email'] = "You must enter a valid email address!";
    }

    // validate the password string
    if (!Validation::string($password, min: 8, max: 50)) {
      $errors['password'] = "The password should be 8 to 50 characters long!";
    }

    // return to view and render errors if they exist
    if (!empty($errors)) {
      load_view('users/login', [
        'errors' => $errors,
      ]);
      exit;
    }

    // check if such email does exist in db
    $search_params = ['email' => $email];
    $user = $this->db->query("SELECT * FROM users WHERE email = :email", $search_params)->fetch();

    // return to view and render error if no such user does exist 
    if (!$user) {
      $errors['user'] = "No such email does exist!";
      load_view('users/login', [
        'errors' => $errors,
      ]);
      exit;
    }

    // check if password is correct 
    if (!password_verify(password: $password, hash: $user->user_password)) {
      $errors['password'] = "Incorrect password!";
      load_view('users/login', [
        'errors' => $errors,
      ]);
      exit;
    }

    // set user session and login
    Session::set(key: 'user', value: [
      'id' => $user->id,
      'name' => $user->fullname,
      'email' => $user->email,
      'city' => $user->city,
    ]);

    redirect('/');
  }
}
