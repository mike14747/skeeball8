<?php
session_start();
if (isset($_SESSION['swt_username']) && isset($_SESSION['store_id']) && isset($_SESSION['access_level']) && $_SESSION['access_level'] > 0) {
    header("Location: admin_central.php");
} else {
    require_once('connections/conn1.php');
    include('admin_header.php');
    if (isset($_GET['status'])) {
        $get_status = $conn1->real_escape_string($_GET['status']);
    }
    if (isset($get_status) && $get_status == "failed") {
        echo '<p class="red">Invalid Login!</p><br />';
    } elseif (isset($get_status) && $get_status == "logged_out") {
        echo '<p class="red">You are now logged out!</p><br />';
    }
    echo '<form action="login_check.php" method="post">';
    echo 'Username:<br />';
    echo '<input type="text" name="username" value="" /><br /><br />';
    echo 'Password:<br />';
    echo '<input type="password" name="password" value="" /><br /><br />';
    echo '<input type="submit" name="login" value="Log In" />';
    echo '</form>';
}
include('admin_footer.php');
