<?php
require_once('connections/conn.php');
include('components/header/header.php');

echo '<div class="row">';
    echo '<div class="col-sm-12 pt-4 pb-4">';
        // find heading and page content for the rules page and display it
        $query_rules_content = $conn->query("SELECT * FROM store_text WHERE store_id=97");
        $rules_content = $query_rules_content->fetch_assoc();
        echo '<h2 class="text-center">' . $rules_content['content_heading'] . '</h2>';
        echo '<hr />';
        echo $rules_content['page_content'];
    echo '</div>';
echo '</div>';

include('components/footer/footer.php');
