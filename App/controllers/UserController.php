<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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
}
