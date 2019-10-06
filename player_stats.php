<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Player Stats</h2>
        <hr />

        <?php
        // make sure a player_id is present
        if (isset($get_player_id)) {
            // find current player's full name and the store they are associated with
            $query_name = $conn->query("SELECT s.store_name, p.full_name FROM players AS p JOIN stores AS s ON (p.store_id=s.store_id) WHERE p.player_id=$get_player_id LIMIT 1");
            if ($query_name->num_rows == 1) {
                // since the player is found in the database, proceed
                $result_name = $query_name->fetch_assoc();
                // display the store and player's full name
                echo '<p>' . $result_name['store_name'] . ' | <b><span class="text-danger">Player: </span>' . $result_name['full_name'] . '</b></p>';
                $query_name->free_result();
                // find all seasons this player has played in
                $query_seasons = $conn->query("SELECT DISTINCT(r.season_id), se.season_id, se.season_name, se.year FROM results AS r JOIN seasons AS se ON (r.season_id=se.season_id) WHERE r.player_id=$get_player_id ORDER BY se.season_id ASC");
                $num_seasons = $query_seasons->num_rows;
                if ($num_seasons > 0) {
                    echo '<p class="lh-2">View stats from: &nbsp;';
                    if (isset($get_season_id) && $get_season_id == 99) {
                        echo '<span class="text-success"><b>Career Totals</b></span> &nbsp;| &nbsp;';
                    } else {
                        echo '<a href="player_stats.php?player_id=' . $get_player_id . '&season_id=99">Career Totals</a> &nbsp;| &nbsp;';
                    }
                    $season_counter = 1;
                    while ($result_seasons = $query_seasons->fetch_assoc()) {
                        if ($season_counter > 1) {
                            echo ' &nbsp;| &nbsp;';
                        }
                        if (isset($get_season_id) && $get_season_id != $cur_season_id) {
                            if ($result_seasons['season_id'] == $get_season_id) {
                                echo '<span class="text-success"><b>' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</b></span>';
                            } else {
                                if ($result_seasons['season_id'] == $cur_season_id) {
                                    echo '<a href="player_stats.php?player_id=' . $get_player_id . '&season_id=' . $result_seasons['season_id'] . '\'>Current Season</a>';
                                } else {
                                    echo '<a href="player_stats.php?player_id=' . $get_player_id . '&season_id=' . $result_seasons['season_id'] . '">' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</a>';
                                }
                            }
                        } else {
                            if ($result_seasons['season_id'] == $cur_season_id) {
                                echo '<span class="text-success"><b>Current Season</b></span>';
                            } else {
                                echo '<a href="player_stats.php?player_id=' . $get_player_id . '&season_id=' . $result_seasons['season_id'] . '">' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</a>';
                            }
                        }
                        $season_counter++;
                    }
                    echo '</p>';
                    $query_seasons->free_result();
                } else {
                    echo '<p class="text-center text-danger"><b>There are no results for this player in the current season.</b></p>';
                }
                echo '<hr />';
                if (isset($get_season_id) && $get_season_id != $cur_season_id && $get_season_id != 99) {
                    $query_season_id = $get_season_id;
                    // find the particulars for the non-current season being displayed
                    $query_season = $conn->query("SELECT season_name, year FROM seasons WHERE season_id=$query_season_id LIMIT 1");
                    if ($query_season->num_rows == 1) {
                        $result_season = $query_season->fetch_assoc();
                        $archive_season = '<p class="text-center">(' . $result_season['season_name'] . ', ' . $result_season['year'] . ')</p>';
                        $query_season->free_result();
                    } else {
                        echo '<p class="text-center text-danger"><b>The season being selected is invalid!</b></span></p>';
                    }
                } else {
                    $query_season_id = $cur_season_id;
                }
                if (isset($get_season_id) && $get_season_id == 99) {
                    $query_stats = $conn->query("SELECT t.team_id, t.team_name, r.week_id, r.player_id, r.g1, r.g2, r.g3, r.g4, r.g5, r.g6, r.g7, r.g8, r.g9, r.g10 FROM results AS r JOIN teams AS t ON (r.team_id=t.team_id) WHERE player_id=$get_player_id ORDER BY week_id ASC, team_id ASC, player_num ASC");
                } else {
                    $query_stats = $conn->query("SELECT t.team_id, t.team_name, r.week_id, r.player_id, r.g1, r.g2, r.g3, r.g4, r.g5, r.g6, r.g7, r.g8, r.g9, r.g10 FROM results AS r JOIN teams AS t ON (r.team_id=t.team_id) WHERE season_id=$query_season_id && player_id=$get_player_id ORDER BY week_id ASC, team_id ASC, player_num ASC");
                }
                $misc_stats = array();
                $weekly_stats = array();
                if ($query_stats->num_rows > 0) {
                    while ($result_stats = $query_stats->fetch_assoc()) {
                        for ($g = 1; $g <= 10; $g++) {
                            $misc_stats[] = $result_stats['g' . $g . ''];
                        }
                        $weekly_stats[] = array("team_id" => $result_stats['team_id'], "team_name" => $result_stats['team_name'], "week_id" => $result_stats['week_id'], "player_id" => $result_stats['player_id'], "g1" => $result_stats['g1'], "g2" => $result_stats['g2'], "g3" => $result_stats['g3'], "g4" => $result_stats['g4'], "g5" => $result_stats['g5'], "g6" => $result_stats['g6'], "g7" => $result_stats['g7'], "g8" => $result_stats['g8'], "g9" => $result_stats['g9'], "g10" => $result_stats['g10']);
                    }
                    $query_stats->free_result();
                    // start misc stats display
                    $total_games = 0;
                    $total_points = 0;
                    $games_800 = 0;
                    $games_700 = 0;
                    $games_600 = 0;
                    $games_500 = 0;
                    $games_400 = 0;
                    $games_300 = 0;
                    $min_score = min($misc_stats);
                    $num_min_score = 0;
                    $max_score = max($misc_stats);
                    $num_max_score = 0;
                    foreach ($misc_stats as $ms) {
                        $total_games++;
                        $total_points = $total_points + $ms;
                        if ($ms >= 300) {
                            $games_300++;
                        }
                        if ($ms >= 400) {
                            $games_400++;
                        }
                        if ($ms >= 500) {
                            $games_500++;
                        }
                        if ($ms >= 600) {
                            $games_600++;
                        }
                        if ($ms >= 700) {
                            $games_700++;
                        }
                        if ($ms >= 800) {
                            $games_800++;
                        }
                        if ($ms == $min_score) {
                            $num_min_score++;
                        }
                        if ($ms == $max_score) {
                            $num_max_score++;
                        }
                    }
                    $max_total_points_finder = array();
                    foreach ($weekly_stats as $ws_max) {
                        $week_total = $ws_max['g1'] + $ws_max['g2'] + $ws_max['g3'] + $ws_max['g4'] + $ws_max['g5'] + $ws_max['g6'] + $ws_max['g7'] + $ws_max['g8'] + $ws_max['g9'] + $ws_max['g10'];
                        $max_total_points_finder[] = $week_total;
                    }
                    $best_10_game = max($max_total_points_finder);
                    echo '<div class="d-flex justify-content-center">';
                    echo '<div class="d-flex flex-column mt-2 mb-2">';
                    echo '<p><b>Total games played:</b> ' . $total_games . '</p>';
                    for ($s = 8; $s >= 3; $s--) {
                        if (${"games_{$s}00"} > 0) {
                            echo '<p><b>' . $s . '00+ games:</b> ' . ${"games_{$s}00"} . ' <span class="t12">(';
                            printf('%.1f', 100 * ${"games_{$s}00"} / $total_games);
                            echo '%)</span></p>';
                        }
                    }
                    printf("<p><b>Avg score per game:</b> %.1f", $total_points / $total_games) . "</p>";
                    echo '<p><b>High game:</b> ' . $max_score . ' (' . $num_max_score . ')</p>';
                    echo '<p><b>Low game:</b> ' . $min_score . ' (' . $num_min_score . ')</p>';
                    echo '<p><b>Best 10-game series:</b> ' . $best_10_game . '</p>';
                    echo '</div>';
                    echo '</div>';
                    if (!isset($get_season_id) || (isset($get_season_id) && $get_season_id != 99)) {
                        // start week by week stats for the selected season
                        echo '<hr />';
                        echo '<p class="text-center text-success mt-4"><b>Week by week results:</b></p>';
                        echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                        echo '<th>Week # - Team</th>';
                        for ($w = 1; $w <= 10; $w++) {
                            echo '<th class="text-center">' . $w . '</b></th>';
                        }
                        echo '<th class="text-center">Total</th></tr>';
                        $match_points = 0;
                        foreach ($weekly_stats as $ws) {
                            echo '<tr>';
                            echo '<td>' . $ws['week_id'] . ' - ' . '<a href="team_stats.php?team_id=' . $ws['team_id'] . '">' . $ws['team_name'] . '</a></td>';
                            for ($g = 1; $g <= 10; $g++) {
                                $match_points = $match_points + $ws['g' . $g];
                                echo '<td class="text-center">' . $ws['g' . $g] . '</td>';
                            }
                            echo '<td class="text-center">' . $match_points . '</td></tr>';
                            $match_points = 0;
                        }
                        echo '</table>';
                    }
                } else {
                    echo '<p class="text-center text-danger"><b>There are no results for this player in the current season.</b></p>';
                }
            } else {
                // since the player_id is not found in the players table
                echo '<hr />';
                echo '<p class="text-center text-danger"><b>No player has been found.</b></p>';
            }
        } else {
            // since no player_id was included
            echo '<hr />';
            echo '<p class="text-center text-danger"><b>No player has been selected.</b></p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
