<?php
require_once('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::create(__DIR__, '../.env');
$dotenv->load();
$dotenv->required(['DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE']);

$hostname_conn = getenv('DB_HOSTNAME');
$username_conn = getenv('DB_USERNAME');
$password_conn = getenv('DB_PASSWORD');
$database_conn = getenv('DB_DATABASE');

$conn = new mysqli($hostname_conn, $username_conn, $password_conn, $database_conn);
// check connection
if ($conn->connect_error) {
    die('Connect Error (' . $conn->connect_errno . ') ' . $conn->connect_error);
}
if (isset($_GET['season_id'])) {
    $get_season_id = (int)$_GET['season_id'];
}
if (isset($_GET['store_id'])) {
    $get_store_id = (int)$_GET['store_id'];
}
if (isset($_GET['division_id'])) {
    $get_division_id = (int)$_GET['division_id'];
}
if (isset($_GET['team_id'])) {
    $get_team_id = (int)$_GET['team_id'];
}
if (isset($_GET['player_id'])) {
    $get_player_id = (int)$_GET['player_id'];
}
if (isset($_GET['week_id'])) {
    $get_week_id = (int)$_GET['week_id'];
}
if (isset($_GET['pdf'])) {
    $get_pdf = (int)$_GET['pdf'];
}
if (isset($_GET['page'])) {
    $get_page = (int)$_GET['page'];
}
if (isset($_GET['group'])) {
    $get_group = (int)$_GET['group'];
}
if (isset($_GET['type'])) {
    $get_type = (int)$_GET['type'];
}
if (isset($_GET['period'])) {
    $get_period = (int)$_GET['period'];
}
if (isset($_GET['show'])) {
    $get_show = (int)$_GET['show'];
}
if (isset($_GET['search_string'])) {
    $get_search_string = $conn->real_escape_string($_GET['search_string']);
}
