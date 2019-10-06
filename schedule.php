<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Schedule</h2>
        <hr />

        <?php
        if (!isset($get_store_id) || !isset($get_division_id)) {
            echo '<p>Please select a store from the Schedule dropdown on the Navigation Bar to view its Schedule.</p>';
        } elseif (isset($get_store_id) || isset($get_division_id)) {
            // find the store city and division based on the suuplied input
            $query_store = $conn->query("SELECT s.store_name, s.store_city, d.day_name FROM stores AS s, divisions AS d WHERE s.store_id=$get_store_id && d.division_id=$get_division_id && s.active=1 LIMIT 1");
            // check to make sure there is a matching store and division in the database
            if ($query_store->num_rows == 1) {
                $result_store = $query_store->fetch_assoc();
                $query_store->free_result();
                echo '<p><a href="store_home.php?store_id=' . $get_store_id . '&division_id=' . $get_division_id . '"><b>' . $result_store['store_name'] . ' (' . $result_store['day_name'] . ')</b></a></p>';
                echo '<hr />';
                // find the schedule meeting this criteria in the database
                $query_matchups = $conn->query("SELECT ds.week_id, DATE_FORMAT(ds.week_date, '%M %d, %Y') AS week_date1, ds.away_team_id, (SELECT t.team_name FROM teams AS t WHERE t.team_id=ds.away_team_id) AS away_team, ds.home_team_id, (SELECT t.team_name FROM teams AS t WHERE t.team_id=ds.home_team_id) AS home_team, ds.alley, ds.start_time FROM (SELECT s.week_id, s.week_date, s.away_team_id AS away_team_id, s.home_team_id AS home_team_id, s.alley AS alley, s.start_time AS start_time FROM schedule AS s WHERE s.season_id=$cur_season_id && s.store_id=$get_store_id && s.division_id=$get_division_id ORDER BY s.week_id ASC, s.start_time ASC, s.alley ASC) AS ds JOIN teams AS t ON (ds.away_team_id=t.team_id) ORDER BY ds.week_id ASC, ds.start_time ASC, ds.alley ASC");
                if ($query_matchups->num_rows > 0) {
                    $current_week = 0;
                    while ($result_matchups = $query_matchups->fetch_assoc()) {
                        if ($result_matchups['week_id'] > $current_week) {
                            if ($current_week != 0) {
                                echo '</table><hr style="width:80%;" /><br />';
                            }
                            $current_week = $result_matchups['week_id'];
                            echo '<p class="text-center"><b><span class="text-success">Week ' . $result_matchups['week_id'] . ' - ' . $result_matchups['week_date1'] . '</span></b></p>';
                            echo '<p class="text-center small"><a href="schedule_pdf.php?pdf=1&season_id=' . $cur_season_id . '&store_id=' . $get_store_id . '&division_id=' . $get_division_id . '&week_id=' . $result_matchups['week_id'] . '" target="_blank">printable PDF schedule for ' . $result_store['store_city'] . ' (' . $result_store['day_name'] . ') - Week ' . $result_matchups['week_id'] . '</a></span></p>';
                            echo '<table class="table table-bordered table1 mb-5"><tr class="bg-table-header">';
                            echo '<th>Away Team</th>';
                            echo '<th>Home Team</th>';
                            echo '<th class="text-center">Alley</th>';
                            echo '<th class="text-center">Start Time, Date</th></tr>';
                        }
                        echo '<tr>';
                        echo '<td><a href="team_stats.php?team_id=' . $result_matchups['away_team_id'] . '">' . $result_matchups['away_team'] . '</a></td>';
                        echo '<td><a href="team_stats.php?team_id=' . $result_matchups['home_team_id'] . '">' . $result_matchups['home_team'] . '</a></td>';
                        echo '<td class="text-center">' . $result_matchups['alley'] . '</td>';
                        echo '<td class="text-center">' . $result_matchups['start_time'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p class="text-center">There are currently no matchups for this store in the schedule... check back soon.</p>';
                }
            }
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
