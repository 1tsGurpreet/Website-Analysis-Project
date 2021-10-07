<?php
require 'vendor/autoload.php';

use Src\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$dbConnection = (new Database(true))->connet();
$DOdbConnection = (new Database(false))->connet();

#echo $_ENV['DB_HOST'];

// test code:
// it will output: localhost
// when you run $ php start.php
