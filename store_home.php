<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <!-- <h2 class="text-center">Page Heading</h2> -->
        <!-- <hr /> -->

        <?php
        // find current store and division info
        $query_store = $conn->query("SELECT * FROM stores AS s, divisions AS d WHERE s.store_id=$get_store_id && d.division_id=$get_division_id LIMIT 1");
        if ($query_store->num_rows == 1) {
            $result_store = $query_store->fetch_assoc();
            echo '<div><b>' . $result_store['store_name'] . ' (' . $result_store['day_name'] . ')</b>';
            if ($result_store['map_url'] != '') {
                echo ' - <a href="' . $result_store['map_url'] . ' " target="_blank"><i><b>MAP</b></i></span></a>';
            }
            echo '</div>';
            if ($result_store['store_address'] != '') {
                echo '<div>' . $result_store['store_address'] . '</div>';
            }
            if ($result_store['store_city'] != '') {
                echo '<div>' . $result_store['store_city'];
                if ($result_store['store_state'] != '') {
                    echo ', ' . $result_store['store_state'];
                }
                if ($result_store['store_zip'] != '') {
                    echo ' ' . $result_store['store_zip'];
                }
                echo '</div>';
            }
            if ($result_store['store_phone'] != '') {
                echo '<div>' . $result_store['store_phone'] . '</div>';
            }
            $query_store->free_result();
            echo '<hr  />';
            // find page content for this page and display it
            $query_page_content = $conn->query("SELECT store_id, content_heading, page_content, DATE_FORMAT(text_date, '%M %d, %Y') AS text_date1 FROM store_text WHERE (store_id=98 || store_id=$get_store_id || store_id=99) && display_content=1");
            if ($query_page_content->num_rows > 0) {
                while ($result_page_content = $query_page_content->fetch_assoc()) {
                    if ($result_page_content['store_id'] == 98) {
                        if ($result_page_content['page_content'] != '') {
                            echo '<h5 class="text-danger mb-0">' . strtoupper($result_page_content['content_heading']) . '</h5>';
                            echo '<p><span class="small">' . $result_page_content['text_date1'] . '</span></p>';
                            echo $result_page_content['page_content'];
                            echo '<hr class="mt-4 mb-4" />';
                        }
                    }
                    if ($result_page_content['store_id'] == $get_store_id) {
                        if ($result_page_content['page_content'] != '') {
                            echo '<h5 class="text-danger mb-0">' . strtoupper($result_page_content['content_heading']) . '</h5>';
                            echo '<p><span class="small">' . $result_page_content['text_date1'] . '</span></p>';
                            echo $result_page_content['page_content'];
                            echo '<hr class="mt-4 mb-4" />';
                        }
                    }
                    if ($result_page_content['store_id'] == 99) {
                        if ($result_page_content['page_content'] != '') {
                            echo '<h5 class="text-danger mb-0">' . strtoupper($result_page_content['content_heading']) . '</h5>';
                            echo '<p><span class="small">' . $result_page_content['text_date1'] . '</span></p>';
                            echo $result_page_content['page_content'];
                            echo '<hr class="mt-4 mb-4" />';
                        }
                    }
                }
                $query_page_content->free_result();
            }
            // start upcoming matchups table
            $query_matchups = $conn->query("SELECT s.week_id, s.week_date1, s.alley, s.start_time, s.away_team_id, (SELECT team_name FROM teams WHERE team_id=s.away_team_id) AS away_team_name, s.home_team_id, (SELECT team_name FROM teams WHERE team_id=s.home_team_id) AS home_team_name FROM teams AS t, (SELECT week_id, DATE_FORMAT(week_date, '%M %d, %Y') AS week_date1, away_team_id, home_team_id, alley, start_time FROM schedule WHERE season_id=$cur_season_id && store_id=$get_store_id && division_id=$get_division_id && CURDATE()<=week_date && DATE_ADD(CURDATE(),INTERVAL 7 DAY)>week_date ORDER BY week_date ASC, start_time ASC, alley ASC) AS s GROUP BY s.week_date1, s.start_time, s.alley");
            // if there are rows meting the above criteria, proceed
            if ($query_matchups->num_rows > 0) {
                echo '<p class="text-center text-success"><b>Upcoming Matchups:</b></p>';
                echo '<table class="table table-bordered table1 mb-5"><tr class="bg-table-header">';
                echo '<th>Away Team</th>';
                echo '<th>Home Team</th>';
                echo '<th class="text-center">Alley</th>';
                echo '<th class="text-center">Start Time, Date</th></tr>';
                while ($result_matchups = $query_matchups->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><a href="team_stats.php?team_id=' . $result_matchups['away_team_id'] . '">' . $result_matchups['away_team_name'] . '</a></td>';
                    echo '<td><a href="team_stats.php?team_id=' . $result_matchups['home_team_id'] . '">' . $result_matchups['home_team_name'] . '</a></td>';
                    echo '<td class="text-center">' . $result_matchups['alley'] . '</td>';
                    echo '<td class="text-center">' . $result_matchups['start_time'] . ', ' . $result_matchups['week_date1'] . '</td></tr>';
                }
                $query_matchups->free_result();
                echo '</table>';
            }
        } else {
            echo '<p class="text-center text-danger"><b>No store has been found!</b></p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
