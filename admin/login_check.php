<?php
require_once("connections/conn1.php");
session_start();
if (isset($_POST['login']) && $_POST['login'] == "Log In") {
	if (isset($_POST['username'])) {
		$username = $conn1->real_escape_string($_POST['username']);
	}
	if (isset($_POST['password'])) {
		$password = $conn1->real_escape_string($_POST['password']);
	}
	if ((isset($username) && $username != "" && preg_match("/^[a-zA-Z][a-zA-Z0-9]+$/", $username)) && (isset($password) && $password != "" && preg_match("/^[a-zA-Z0-9]+$/", $password))) {
		$salt = "skeeball_world_tour_salt";
		$password = hash('sha256', $salt . $password);
		$query_user = $conn1->query("SELECT store_id, division_id, username FROM users WHERE username='$username' && hashed_password='$password'");
		if ($query_user->num_rows == 1) {
			$result_user = $query_user->fetch_assoc();
			if ($result_user['store_id'] == 99) {
				$_SESSION['access_level'] = 2;
				$_SESSION['swt_username'] = "admin";
				$_SESSION['store_id'] = 99;
			} else {
				$_SESSION['access_level'] = 1;
				$_SESSION['swt_username'] = $result_user['username'];
				$_SESSION['store_id'] = $result_user['store_id'];
				$_SESSION['division_id'] = $result_user['division_id'];
			}
			$query_user->free_result();
			header("Location: admin_central.php");
		} else {
			header("Location: index.php?status=failed");
		}
	} else {
		header("Location: index.php?status=failed");
	}
} else {
	header("Location: index.php?status=failed");
}
?>