<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Stores in the Current League</h2>
        <hr />

        <?php
        // query for stores set as active
        $query_stores = $conn->query("SELECT DISTINCT s.division_id, d.day_name, st.*  FROM schedule AS s JOIN divisions AS d ON (s.division_id=d.division_id) JOIN stores AS st ON (s.store_id=st.store_id) WHERE s.season_id=$cur_season_id && st.active=1 ORDER BY st.store_name, d.division_id ASC");
        if ($query_stores->num_rows > 0) {
            // set counter for leaving out the hr after the final store
            $counter = $query_stores->num_rows;
            echo '<div class="d-flex justify-content-center">';
            echo '<div class="d-flex flex-column mt-2 mb-2">';
            while ($result_stores = $query_stores->fetch_assoc()) {
                echo '<p><b>';
                // $get_page values: 1=results, 2=schedule, 3=leaders
                if (isset($get_page) && ($get_page == 1 || $get_page == 2 || $get_page == 3)) {
                    echo '<a href="';
                    if ($get_page == 1) {
                        echo 'results';
                    } elseif ($get_page == 2) {
                        echo 'schedule';
                    } elseif ($get_page == 3) {
                        echo 'leaders_store';
                    }
                    echo '.php?store_id=' . $result_stores['store_id'] . '&division_id=' . $result_stores['division_id'] . '">';
                } else {
                    echo '<a href="store_home.php?store_id=' . $result_stores['store_id'] . '&division_id=' . $result_stores['division_id'] . '">';
                }
                echo $result_stores['store_name'];
                echo '</b> (' . $result_stores['day_name'] . ')';
                echo '</a><br />';
                if ($result_stores['store_address'] != '') {
                    echo $result_stores['store_address'] . '<br />';
                }
                if ($result_stores['store_city'] != '') {
                    echo $result_stores['store_city'];
                    if ($result_stores['store_state'] != '') {
                        echo ', ' . $result_stores['store_state'];
                    }
                    if ($result_stores['store_zip'] != '') {
                        echo ' ' . $result_stores['store_zip'];
                    }
                }
                if ($result_stores['store_phone'] != '') {
                    echo '<br />Phone: ' . $result_stores['store_phone'];
                }
                echo '</p>';
                $counter--;
                if ($counter > 0) {
                    echo '<hr class="w-100 mb-4" />';
                }
            }
            $query_stores->free_result();
            echo '</div>';
            echo '</div>';
        } else {
            echo 'The database is currently being updated for the new season. Check back soon.';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
