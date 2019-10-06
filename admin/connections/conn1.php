<?php
require_once('../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::create(__DIR__, '../../.env');
$dotenv->load();
$dotenv->required(['DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE']);

$hostname_conn = getenv('DB_HOSTNAME');
$username_conn = getenv('DB_USERNAME');
$password_conn = getenv('DB_PASSWORD');
$database_conn = getenv('DB_DATABASE');

$conn1 = new mysqli($hostname_conn, $username_conn, $password_conn, $database_conn);
// check connection
if ($conn1->connect_error) {
    die('Connect Error (' . $conn1->connect_errno . ') ' . $conn1->connect_error);
}
