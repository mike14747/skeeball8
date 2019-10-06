<?php
session_start();
if (isset($_SESSION['swt_username']) && isset($_SESSION['store_id']) && isset($_SESSION['access_level']) && $_SESSION['access_level'] > 0) {
	$access_level = $_SESSION['access_level'];
	$store_id = $_SESSION['store_id'];
	$swt_username = $_SESSION['swt_username'];
} else {
	header("Location: index.php");
}
?>