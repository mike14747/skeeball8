<?php
echo '<!DOCTYPE html>';
echo '<html lang="en">';

echo '<head>';
echo '<meta charset="utf-8">';
echo '<title>Admin Central</title>';
echo '<link rel="stylesheet" href="../css/main.css?v=1.1" type="text/css" />';

include('scripts/disable_enter_key.php');

// start the tinymce3 script if one of the following pages is loaded
if (basename($_SERVER['PHP_SELF']) == 'update_store_text.php' || basename($_SERVER['PHP_SELF']) == 'edit_rules_text.php' || basename($_SERVER['PHP_SELF']) == 'add_homepage_news.php' || basename($_SERVER['PHP_SELF']) == 'edit_homepage_news.php' || basename($_SERVER['PHP_SELF']) == 'update_settings.php') {
    include('scripts/tinymce3_init.php');
}
// start the jquery datepicker script if one of the following pages is loaded
if (basename($_SERVER['PHP_SELF']) == 'add_homepage_news.php' || basename($_SERVER['PHP_SELF']) == 'edit_homepage_news.php' || basename($_SERVER['PHP_SELF']) == 'add_edit_seasons.php' || basename($_SERVER['PHP_SELF']) == 'multiple_datepicker.php') {
    include('scripts/datepicker_init.php');
    if (basename($_SERVER['PHP_SELF']) == 'add_edit_seasons.php') {
        include('scripts/datepicker_init3.php');
    }
}
echo '</head>';
echo '<body>';

echo '<div class="wrapper">';
echo '<div class="content">';
// check to see if $cur_season_id isset... if not, find it
if (!isset($cur_season_id)) {
    // check to see what the current season_id is
    $query_season = $conn1->query("SELECT current_season FROM settings WHERE setting_id=1");
    $result_season = $query_season->fetch_assoc();
    // set the current season_id to a variable
    $cur_season_id = $result_season['current_season'];
    $query_season->free_result();
}
if (isset($_GET['status']) && $_GET['status'] == "done" && isset($_GET['store_id']) && isset($_GET['week_id'])) {
    if (isset($_SESSION['swt_username']) && isset($_SESSION['access_level']) && $_SESSION['access_level'] == 2) {
        echo '<div class="right"><span class="t14">Logged in as: ' . $_SESSION['swt_username'] . '<br /><a href="logout.php">Logout</a></span></div>';
    }
} elseif (isset($access_level) && $access_level > 0) {
    echo '<div class="right"><span class="t14">Logged in as: ' . $_SESSION['swt_username'] . '<br /><a href="logout.php">Logout</a></span></div>';
}
echo '<h3><a href="admin_central.php">Admin Central</a></h3>';
echo '<div class="t14"><a href="../index.php" target="_blank">Public website in a new window</a></div>';
echo '<hr /><br />';
