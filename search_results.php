<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Search Results</h2>
        <hr />

        <?php
        if (isset($get_search_string) && !empty($get_search_string)) {
            echo '<p><b>Search results for:</b> ' . $get_search_string . '</p>';
            echo '<div class="row">';
            // search the database for players that match
            $query_players = $conn->query("SELECT p.player_id, p.full_name, s.store_id, s.store_city FROM players AS p JOIN stores AS s ON (p.store_id=s.store_id) WHERE (p.full_name LIKE '%$get_search_string%') ORDER BY p.full_name ASC");
            echo '<div class="col-sm">';
            $num_players = $query_players->num_rows;
            echo '<p class="text-success"><b>Player Matches:</b> ' . $num_players . '</p>';
            if ($num_players == 0) {
                echo '<p>No players match the search criteria.</p>';
            } elseif ($num_players > 0) {
                while ($result_players = $query_players->fetch_assoc()) {
                    echo '<p><a href="player_stats.php?player_id=' . $result_players['player_id'] . '">' . $result_players['full_name'] . '</a> (' . $result_players['store_city'] . ')</p>';
                }
            }
            echo '</div>';
            // search the database for teams that match
            $query_teams = $conn->query("SELECT t.team_id, t.team_name, s.store_city FROM teams AS t JOIN stores AS s ON (t.store_id=s.store_id) WHERE (t.team_name LIKE '%$get_search_string%') ORDER BY t.team_name ASC");
            echo '<div class="col-sm">';
            $num_teams = $query_teams->num_rows;
            echo '<p class="text-success"><b>Team Matches:</b> ' . $num_teams . '</p>';
            if ($num_teams == 0) {
                echo '<p>No teams match the search criteria.</p>';
            } elseif ($num_teams > 0) {
                while ($result_teams = $query_teams->fetch_assoc()) {
                    echo '<p><a href="team_stats.php?team_id=' . $result_teams['team_id'] . '">' . $result_teams['team_name'] . '</a> (' . $result_teams['store_city'] . ')</p>';
                }
            }
            echo '</div>';
            echo '</div>';
        } else {
            echo '<p class="text-center text-danger"><b>No search criteria has been entered.</b></p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
