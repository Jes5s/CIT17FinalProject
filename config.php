<?php
// config.php
session_start();


define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'jamespogi');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP default is empty


define('BASE_URL', 'http://localhost/cit17final');


// error display for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function pdo_connect()
{
    static $pdo;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
    }
    return $pdo;
}