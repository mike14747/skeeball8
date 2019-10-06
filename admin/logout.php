<?php
session_start();
if (isset($_SESSION['swt_username']) && isset($_SESSION['access_level'])) {
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-3600, '/');
	}
	session_unset();
	session_destroy();
	header("Location: index.php?status=logged_out");
} else  {
	header("Location: index.php?status=logged_out");
}
?>