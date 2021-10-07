<?php
namespace Src;

class Database {

  private $dbConnection = null;

  public function __construct($selfhost)
  {
    if (!isset($selfhost))
        $selfhost = false;
    $host = (selfhost) ? $_ENV['DB_HOST'] : $_ENV['DODB_HOST'];
    $port = (selfhost) ? $_ENV['DB_PORT'] : $_ENV['DODB_PORT'];
    $db   = (selfhost) ? $_ENV['DB_DATABASE'] : $_ENV['DODB_DATABASE'];
    $user = (selfhost) ? $_ENV['DB_USERNAME'] : $_ENV['DODB_USERNAME'];
    $pass = (selfhost) ? $_ENV['DB_PASSWORD'] : $_ENV['DODB_PASSWORD'];

    try {
      $this->dbConnection = new \PDO(
          "mysql:host=$host;port=$port;dbname=$db",
          $user,
          $pass
      );
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function connet()
  {
    return $this->dbConnection;
  }
}
