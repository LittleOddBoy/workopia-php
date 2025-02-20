<?php

class Database
{
  public $conn;

  /**
   * Construct for database class
   *
   * @param array $config - Database and connection config
   */
  public function __construct(array $config)
  {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbName={$config['dbName']}";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
      $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
    } catch (PDOException $e) {
      throw new Exception("Database connection failed: {$e->getMessage()}");
    }
  }
}
