<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Standings</h2>
        <hr />

        <?php
        if (isset($get_season_id) && $get_season_id != $cur_season_id) {
            $query_season_id = $get_season_id;
            // find the particulars for the non-current season being displayed
            $query_season = $conn->query("SELECT season_name, year FROM seasons WHERE season_id=$query_season_id LIMIT 1");
            if ($query_season->num_rows == 1) {
                $result_season = $query_season->fetch_assoc();
                $archive_season = '<p class="text-center">(' . $result_season['season_name'] . ', ' . $result_season['year'] . ')</p>';
                $query_season->free_result();
            } else {
                echo '<p class="text-center text-danger font-weight-bolder">The season being selected is invalid!</p>';
            }
        } else {
            $query_season_id = $cur_season_id;
            // find the particulars for the current season being displayed
            $query_season = $conn->query("SELECT season_name, year FROM seasons WHERE season_id=$query_season_id LIMIT 1");
            $result_season = $query_season->fetch_assoc();
            $archive_season = '<p class="text-center">(' . $result_season['season_name'] . ', ' . $result_season['year'] . ')</p>';
            $query_season->free_result();
        }
        if (isset($archive_season)) {
            echo $archive_season;
        }
        echo '<hr />';
        // find the standings for the season being displayed
        $query_stores_standings = $conn->query("SELECT CONCAT(st.store_id,st.division_id) AS store_division, s.store_id, s.store_city, t.team_id, t.team_name, st.wins, st.losses, st.ties, st.total_points, d.division_id, d.day_name FROM standings AS st JOIN stores AS s ON (st.store_id=s.store_id) JOIN teams AS t ON (st.team_id=t.team_id) JOIN divisions AS d ON (st.division_id=d.division_id) WHERE st.season_id=$query_season_id ORDER BY s.store_city ASC, d.division_id ASC, standings_order ASC");
        if ($query_stores_standings->num_rows > 0) {
            // there's at least 1 store with standings for the current season being displayed
            $cur_store_division = 0;
            $counter = 1;
            while ($result_stores_standings = $query_stores_standings->fetch_assoc()) {
                if ($cur_store_division != $result_stores_standings['store_division']) {
                    if ($counter != 1) {
                        echo '</table>';
                    }
                    echo '<h5 class="text-center"><b><a href="store_home.php?store_id=' . $result_stores_standings['store_id'] . '&division_id=' . $result_stores_standings['division_id'] . '">' . $result_stores_standings['store_city'] . ' (' . $result_stores_standings['day_name'] . ')</a></b></h5>';
                    echo '<p class="text-center"><a href="standings_pdf.php?pdf=1&season_id=' . $query_season_id . '&store_id=' . $result_stores_standings['store_id'] . '&division_id=' . $result_stores_standings['division_id'] . '" target="_blank">printable pdf of ' . $result_stores_standings['store_city'] . ' (' . $result_stores_standings['day_name'] . ') standings</a></p>';
                    echo '<table class="table table-bordered mb-5 table1"><tr class="bg-table-header">';
                    echo '<th>TEAM</th>';
                    echo '<th class="text-center">W</th>';
                    echo '<th class="text-center">L</th>';
                    echo '<th class="text-center">T</th>';
                    echo '<th class="text-center">TOTAL POINTS</th></tr>';
                }
                echo '<tr>';
                echo '<td><a href="team_stats.php?team_id=' . $result_stores_standings['team_id'] . '">' . $result_stores_standings['team_name'] . '</a></td>';
                echo '<td class="text-center">' . $result_stores_standings['wins'] . '</td>';
                echo '<td class="text-center">' . $result_stores_standings['losses'] . '</td>';
                echo '<td class="text-center">' . $result_stores_standings['ties'] . '</td>';
                echo '<td class="text-center">' . $result_stores_standings['total_points'];
                echo '</td></tr>';
                $cur_store_division = $result_stores_standings['store_division'];
                $counter++;
            }
            $query_stores_standings->free_result();
            echo '</table>';
            echo '<hr />';
            echo '<p class="smaller"><span class="font-weight-bolder">Note:</span> Standings order is set just like major sports leagues. Think "games behind"... and in our case, ties are broken by total points. So, it is possible for a team with fewer wins to be ahead of a team with more wins, since the team with more wins could also have more losses (because of ties).</p>';
        } else {
            echo '<p class="text-center text-danger"><b>There are no standings for the selected season.</b></p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
