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
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbName']}";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ];

    try {
      $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
    } catch (PDOException $e) {
      throw new Exception("Database connection failed: {$e->getMessage()}");
    }
  }


  /**
   * Query on the database
   * 
   * @param string $query 
   * @return PDOStatement
   * @throws PDOException
   */
  public function query(string $query): PDOStatement
  {
    try {
      $statement = $this->conn->prepare($query);
      $statement->execute();
      return $statement;
    } catch (PDOException $e) {
      throw new Exception("Query failed to execute: " . $e->getMessage());
    }
  }
}
