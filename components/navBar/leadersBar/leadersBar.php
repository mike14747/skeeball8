<?php
echo '<p class="mt-2"><b>League Leaders:</b> &nbsp;<a href="leaders_all.php">Combined</a> &nbsp;| &nbsp;';
if (isset($get_store_id) && isset($get_division_id)) {
    echo '<a href="leaders_store.php?store_id=' . $get_store_id . '&amp;division_id=' . $get_division_id . '">Individual Store</a>';
} else {
    echo '<a href="stores.php?page=3">Individual Store</a>';
}
echo '&nbsp;| &nbsp;<a href="leaders_all-time.php">All-Time Leaders</a></p>';
