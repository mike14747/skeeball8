<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Champions</h2>
        <hr />

        <?php
        // find the yearly champions, their team names, the seasons and the store name/division of the team
        $query_champions_seasons = $conn->query("SELECT s.season_name, s.year, s.tourny_team_id, t.team_name, st.store_city, s.comments FROM seasons AS s JOIN teams AS t ON t.team_id=s.tourny_team_id JOIN stores AS st ON st.store_id=t.store_id WHERE s.tourny_team_id>0 ORDER by s.season_id ASC");
        if ($query_champions_seasons->num_rows > 0) {
            echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
            echo '<th>Season</th>';
            echo '<th>Champion</th>';
            echo '<th>Store</th></tr>';
            while ($result_champions_seasons = $query_champions_seasons->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $result_champions_seasons['season_name'] . "-" . $result_champions_seasons['year'] . '</td>';
                echo '<td><a href="team_stats.php?team_id=' . $result_champions_seasons['tourny_team_id'] . '>' . $result_champions_seasons['team_name'] . '</a>';
                if ($result_champions_seasons['comments'] != "") {
                    echo '<span class="small"> &nbsp;(*' . $result_champions_seasons['comments'] . ')</span>';
                }
                echo '</td>';
                echo '<td>' . $result_champions_seasons['store_city'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        $query_champions_seasons->free_result();
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
