<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Team Stats</h2>
        <hr />

        <?php
        // make sure the team_id are present via $_GET
        if (isset($get_team_id)) {
            if (isset($get_season_id)) {
                $query_season_id = $get_season_id;
            } else {
                $query_season_id = $cur_season_id;
            }
            // find current team's name and the store they are associated with
            $query_name = $conn->query("SELECT s.store_name, t.team_name FROM teams AS t JOIN stores AS s ON (t.store_id=s.store_id) WHERE t.team_id=$get_team_id LIMIT 1");
            $result_name = $query_name->fetch_assoc();
            if ($query_name->num_rows == 1) {
                // display the store and team's name
                echo '<p>' . $result_name['store_name'] . ' | <b><span class="text-danger">Team: </span>' . $result_name['team_name'] . '</b></p>';
                // find all seasons this team has played in
                $query_seasons = $conn->query("SELECT DISTINCT(r.season_id), se.season_id, se.season_name, se.year FROM results AS r JOIN seasons AS se ON (r.season_id=se.season_id) WHERE r.team_id=$get_team_id ORDER BY se.season_id ASC");
                $num_seasons = $query_seasons->num_rows;
                if ($num_seasons > 0) {
                    echo '<p class="lh-2">View stats from: &nbsp;';
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
                                    echo '<a href="team_stats.php?team_id=' . $get_team_id . '&season_id=' . $result_seasons['season_id'] . '">Current Season</a>';
                                } else {
                                    echo '<a href="team_stats.php?team_id=' . $get_team_id . '&season_id=' . $result_seasons['season_id'] . '">' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</a>';
                                }
                            }
                        } else {
                            if ($result_seasons['season_id'] == $cur_season_id) {
                                echo '<span class="text-success"><b>Current Season</b></span>';
                            } else {
                                echo '<a href="team_stats.php?team_id=' . $get_team_id . '&season_id=' . $result_seasons['season_id'] . '">' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</a>';
                            }
                        }
                        $season_counter++;
                    }
                    echo '</p>';
                    $query_seasons->free_result();
                }
                // find out what store and division this team is in the schedule for the current season
                $query_store_schedule = $conn->query("SELECT s.store_id, s.store_name, d.division_id, d.day_name FROM stores AS s, divisions AS d WHERE s.store_id IN (SELECT DISTINCT store_id FROM schedule WHERE season_id=$query_season_id && (away_team_id=$get_team_id || home_team_id=$get_team_id)) && d.division_id IN (SELECT DISTINCT division_id FROM schedule WHERE season_id=$query_season_id && (away_team_id=$get_team_id || home_team_id=$get_team_id)) ORDER BY d.division_id ASC LIMIT 1");
                if ($query_store_schedule->num_rows == 1) {
                    // since the team is in this (or the selected) season's schedule, proceed
                    $query_store_schedule->free_result();
                    echo '<hr />';
                    // find out if there are any results for this team in the currently selected season
                    $query_teams_stats = $conn->query("SELECT tg1.team_id, s.wins, s.losses, s.ties, s.total_points, ROUND((s.total_points/(s.wins+s.losses+s.ties)),1) AS one_game_avg, ROUND((s.total_points/((s.wins+s.losses+s.ties)/10)),1) AS ten_game_avg, (SELECT (SUM(g1)+SUM(g2)+SUM(g3)+SUM(g4)+SUM(g5)+SUM(g6)+SUM(g7)+SUM(g8)+SUM(g9)+SUM(g10)) AS tgh FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id ORDER BY tgh DESC LIMIT 1) AS ten_game_high, (SELECT (SUM(g1)+SUM(g2)+SUM(g3)+SUM(g4)+SUM(g5)+SUM(g6)+SUM(g7)+SUM(g8)+SUM(g9)+SUM(g10)) AS tgl FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id ORDER BY tgl ASC LIMIT 1) AS ten_game_low, tg1.one_game_low, tg1.one_game_high FROM standings AS s JOIN (SELECT season_id, team_id, MIN(tg.team_game) AS one_game_low, MAX(tg.team_game) AS one_game_high FROM (SELECT season_id, team_id, SUM(g1) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g2) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g3) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g4) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g5) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g6) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g7) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g8) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g9) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id UNION ALL SELECT season_id, team_id, SUM(g10) AS team_game FROM results WHERE season_id=$query_season_id && team_id=$get_team_id GROUP BY week_id) AS tg) AS tg1 ON (s.season_id=tg1.season_id AND s.team_id=tg1.team_id) WHERE s.season_id=$query_season_id && s.team_id=$get_team_id GROUP BY tg1.team_id");
                    if ($query_teams_stats->num_rows == 1) {
                        // since this team has played this season, show their stats
                        // find the players that have played for this team
                        $query_players = $conn->query("SELECT p.full_name, r.player_id, COUNT(*)*10 AS games_played, AVG(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10)/10 AS avg_score FROM players AS p JOIN results AS r ON (p.player_id=r.player_id) WHERE r.season_id=$query_season_id && team_id=$get_team_id && r.player_id!=100 GROUP BY r.player_id ORDER BY avg_score DESC");
                        if ($query_players->num_rows > 0) {
                            echo '<div class="d-flex justify-content-center">';
                            echo '<div class="d-flex flex-column mr-auto mt-2 mb-2">';
                            echo '<table class="table table-bordered mb-4"><tr class="bg-table-header">';
                            echo '<th>PLAYER</th>';
                            echo '<th class="text-center">GAMES</th>';
                            echo '<th class="text-center">GAMES</th>';
                            echo '</tr>';
                            while ($result_players = $query_players->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td><a href="player_stats.php?player_id=' . $result_players['player_id'] . '">' . $result_players['full_name'] . '</a></td>';
                                echo '<td class="text-center">' . $result_players['games_played'] . '</td>';
                                echo '<td class="text-center">';
                                printf('%.1f', $result_players['avg_score']);
                                echo '</td>';
                                echo '</tr>';
                            }
                            $query_players->free_result();
                            echo '</table>';
                            echo '</div>';
                            echo '<div class="d-flex flex-column mt-2 mb-2">';
                            $result_teams_stats = $query_teams_stats->fetch_assoc();
                            echo '<p><b>Record:</b> ' . $result_teams_stats['wins'] . '-' . $result_teams_stats['losses'] . '-' . $result_teams_stats['ties'] . '</p>';
                            echo '<p><b>Total Points:</b> ' . $result_teams_stats['total_points'] . '</p>';
                            echo '<p>--------------------</p>';
                            printf('<p><b>1-game average:</b> %.1f', $result_teams_stats['one_game_avg']) . '</p>';
                            echo '<p><b>1-game high:</b> ' . $result_teams_stats['one_game_high'] . '</p>';
                            echo '<p><b>1-game low:</b> ' . $result_teams_stats['one_game_low'] . '</p>';
                            echo '<p>--------------------</p>';
                            printf('<p><b>10-game average:</b> %.1f', $result_teams_stats['ten_game_avg']) . '</p>';
                            echo '<p><b>10-game high:</b> ' . $result_teams_stats['ten_game_high'] . '</p>';
                            echo '<p><b>10-game low:</b> ' . $result_teams_stats['ten_game_low'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '<hr />';
                        }
                        $query_teams_stats->free_result();
                    } else {
                        // since there are no results for this team in the current season, display the error message
                        echo 'There are no stats for <b>' . $result_name['team_name'] . '</b> in the current season.';
                    }
                    // find the schedule for the current team in the current season
                    $query_matchups = $conn->query("SELECT s.week_id, s.week_date1, s.alley, s.start_time, s.away_team_id, (SELECT team_name FROM teams WHERE team_id=s.away_team_id) AS away_team_name, s.home_team_id, (SELECT team_name FROM teams WHERE team_id=s.home_team_id) AS home_team_name FROM teams AS t, (SELECT week_id, DATE_FORMAT(week_date, '%M %d, %Y') AS week_date1, away_team_id, home_team_id, alley, start_time FROM schedule WHERE season_id=$query_season_id && (away_team_id=$get_team_id || home_team_id=$get_team_id) ORDER BY week_date ASC, start_time ASC, alley ASC) AS s GROUP BY s.week_date1, s.start_time, s.alley ORDER BY week_id ASC");
                    // if there are matchups in the schedule, proceed
                    if ($query_matchups->num_rows > 0) {
                        echo '<p class="text-center">' . $result_name['team_name'] . ' <b>Schedule</b></p>';
                        echo '<table class="table table-bordered table1 mb-5"><tr class="bg-table-header">';
                        echo '<th class="text-center">WEEK #</th>';
                        echo '<th>Away Team</th>';
                        echo '<th>Home Team</th>';
                        echo '<th class="text-center">Alley</th>';
                        echo '<th class="text-center">Start Time, Date</th>';
                        echo '</tr>';
                        while ($result_matchups = $query_matchups->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td class="text-center">' . $result_matchups['week_id'] . '</td>';
                            echo '<td><a href="team_stats.php?team_id=' . $result_matchups['away_team_id'] . '">' . $result_matchups['away_team_name'] . '</a></td>';
                            echo '<td><a href="team_stats.php?team_id=' . $result_matchups['home_team_id'] . '">' . $result_matchups['home_team_name'] . '</a></td>';
                            echo '<td class="text-center">' . $result_matchups['alley'] . '</td>';
                            echo '<td class="text-center">' . $result_matchups['start_time'] . ', ' . $result_matchups['week_date1'] . '</td>';
                            echo '</tr>';
                        }
                        $query_matchups->free_result();
                        echo '</table><hr />';
                    }
                    // display week by week results for this team in the currently selected season
                    $query_matchups = $conn->query("SELECT s.week_id, s.week_date1, s.away_team_id, s.home_team_id, s.alley, s.start_time, MAX(CASE WHEN t.team_id=s.away_team_id THEN t.team_name ELSE NULL END) AS at, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.player_id ELSE NULL END) AS ap1id, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN p.full_name ELSE NULL END) AS ap1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g1 ELSE NULL END) AS ap1g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g2 ELSE NULL END) AS ap1g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g3 ELSE NULL END) AS ap1g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g4 ELSE NULL END) AS ap1g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g5 ELSE NULL END) AS ap1g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g6 ELSE NULL END) AS ap1g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g7 ELSE NULL END) AS ap1g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g8 ELSE NULL END) AS ap1g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g9 ELSE NULL END) AS ap1g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g10 ELSE NULL END) AS ap1g10, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.player_id ELSE NULL END) AS ap2id, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN p.full_name ELSE NULL END) AS ap2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g1 ELSE NULL END) AS ap2g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g2 ELSE NULL END) AS ap2g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g3 ELSE NULL END) AS ap2g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g4 ELSE NULL END) AS ap2g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g5 ELSE NULL END) AS ap2g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g6 ELSE NULL END) AS ap2g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g7 ELSE NULL END) AS ap2g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g8 ELSE NULL END) AS ap2g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g9 ELSE NULL END) AS ap2g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g10 ELSE NULL END) AS ap2g10, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.player_id ELSE NULL END) AS ap3id, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN p.full_name ELSE NULL END) AS ap3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g1 ELSE NULL END) AS ap3g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g2 ELSE NULL END) AS ap3g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g3 ELSE NULL END) AS ap3g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g4 ELSE NULL END) AS ap3g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g5 ELSE NULL END) AS ap3g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g6 ELSE NULL END) AS ap3g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g7 ELSE NULL END) AS ap3g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g8 ELSE NULL END) AS ap3g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g9 ELSE NULL END) AS ap3g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g10 ELSE NULL END) AS ap3g10, MAX(CASE WHEN t.team_id=s.home_team_id THEN t.team_name ELSE NULL END) AS ht, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.player_id ELSE NULL END) AS hp1id, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN p.full_name ELSE NULL END) AS hp1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g1 ELSE NULL END) AS hp1g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g2 ELSE NULL END) AS hp1g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g3 ELSE NULL END) AS hp1g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g4 ELSE NULL END) AS hp1g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g5 ELSE NULL END) AS hp1g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g6 ELSE NULL END) AS hp1g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g7 ELSE NULL END) AS hp1g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g8 ELSE NULL END) AS hp1g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g9 ELSE NULL END) AS hp1g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g10 ELSE NULL END) AS hp1g10, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.player_id ELSE NULL END) AS hp2id, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN p.full_name ELSE NULL END) AS hp2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g1 ELSE NULL END) AS hp2g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g2 ELSE NULL END) AS hp2g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g3 ELSE NULL END) AS hp2g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g4 ELSE NULL END) AS hp2g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g5 ELSE NULL END) AS hp2g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g6 ELSE NULL END) AS hp2g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g7 ELSE NULL END) AS hp2g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g8 ELSE NULL END) AS hp2g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g9 ELSE NULL END) AS hp2g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g10 ELSE NULL END) AS hp2g10, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.player_id ELSE NULL END) AS hp3id, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN p.full_name ELSE NULL END) AS hp3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g1 ELSE NULL END) AS hp3g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g2 ELSE NULL END) AS hp3g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g3 ELSE NULL END) AS hp3g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g4 ELSE NULL END) AS hp3g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g5 ELSE NULL END) AS hp3g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g6 ELSE NULL END) AS hp3g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g7 ELSE NULL END) AS hp3g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g8 ELSE NULL END) AS hp3g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g9 ELSE NULL END) AS hp3g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g10 ELSE NULL END) AS hp3g10 FROM results AS r JOIN players AS p ON (r.player_id=p.player_id) JOIN teams AS t ON (r.team_id=t.team_id) JOIN (SELECT week_id, DATE_FORMAT(week_date, '%b-%d, %Y') AS week_date1, away_team_id, home_team_id, alley, start_time FROM schedule WHERE season_id=$query_season_id && (away_team_id=$get_team_id || home_team_id=$get_team_id) ORDER BY week_id DESC, start_time ASC, alley ASC) AS s ON (r.week_id=s.week_id AND (r.team_id=s.away_team_id || r.team_id=s.home_team_id)) WHERE r.season_id=$query_season_id GROUP BY r.week_id, s.start_time, s.alley ORDER BY r.week_id DESC, s.start_time ASC, s.alley ASC, r.team_id ASC, r.player_num ASC");
                    if ($query_matchups->num_rows > 0) {
                        $counter = $query_matchups->num_rows;
                        echo '<p class="text-center">' . $result_name['team_name'] . ' <b>Weekly Results</b></p>';
                        // since there are results for this store and division, display them
                        while ($result_matchups = $query_matchups->fetch_assoc()) {
                            // calculate away team game totals
                            $a_tot = 0;
                            for ($agt = 1; $agt <= 10; $agt++) {
                                ${"ag{$agt}t"} = $result_matchups['ap1g' . $agt] + $result_matchups['ap2g' . $agt] + $result_matchups['ap3g' . $agt];
                                // ${"ag{$agt}t"} = ${"ap1g{$agt}"} + ${"ap2g{$agt}"} + ${"ap3g{$agt}"};
                                $a_tot = $a_tot + ${"ag{$agt}t"};
                            }
                            // calculate home team game totals
                            $h_tot = 0;
                            for ($hgt = 1; $hgt <= 10; $hgt++) {
                                ${"hg{$hgt}t"} = $result_matchups['hp1g' . $hgt] + $result_matchups['hp2g' . $hgt] + $result_matchups['hp3g' . $hgt];
                                $h_tot = $h_tot + ${"hg{$hgt}t"};
                            }
                            // calculate match records for away and home teams
                            // first initialize variables
                            $away_wins = 0;
                            $away_losses = 0;
                            $away_ties = 0;
                            $home_wins = 0;
                            $home_losses = 0;
                            $home_ties = 0;
                            for ($r = 1; $r <= 10; $r++) {
                                if (${"ag{$r}t"} > ${"hg{$r}t"}) {
                                    $away_wins++;
                                    $home_losses++;
                                } elseif (${"ag{$r}t"} < ${"hg{$r}t"}) {
                                    $away_losses++;
                                    $home_wins++;
                                } elseif (${"ag{$r}t"} == ${"hg{$r}t"}) {
                                    $away_ties++;
                                    $home_ties++;
                                }
                            }
                            echo '<p class="text-center text-success"><b>Week ' . $result_matchups['week_id'] . ' (' . $result_matchups['week_date1'] . ')</b></p>';
                            // start table for current matchup
                            echo '<table class="table table-bordered mb-4"><tr class="bg-table-header">';
                            // start rows for away team
                            echo '<th><a href="team_stats.php?team_id=' . $result_matchups['away_team_id'] . '">' . $result_matchups['at'] . '</a> (';
                            if ($away_wins > $away_losses) {
                                echo '<span class="text-success">';
                            } elseif ($away_wins < $away_losses) {
                                echo '<span class="text-danger">';
                            } elseif ($away_wins == $away_losses) {
                                echo '<span class="text-primary">';
                            }
                            echo $away_wins . '-' . $away_losses . '-' . $away_ties . '</span>)</td>';
                            for ($ag = 1; $ag <= 10; $ag++) {
                                echo '<th class="text-center">' . $ag . '</td>';
                            }
                            echo '<th class="text-center">Total</td>';
                            echo '</tr>';
                            for ($a = 1; $a <= 3; $a++) {
                                // initialize the current player's total points
                                ${"ap{$a}t"} = 0;
                                echo '<tr>';
                                echo '<td><a href="player_stats.php?player_id=' . $result_matchups['ap' . $a . 'id'] . '">' . $result_matchups['ap' . $a] . '</a></td>';
                                for ($b = 1; $b <= 10; $b++) {
                                    echo '<td class="text-center">' . $result_matchups['ap' . $a . 'g' . $b] . '</td>';
                                    ${"ap{$a}t"} = ${"ap{$a}t"} + $result_matchups['ap' . $a . 'g' . $b];
                                }
                                echo '<td class="text-center">' . ${"ap{$a}t"} . '</td>';
                                echo '</tr>';
                            }
                            echo '<tr class="bg-table-header">';
                            echo '<td><b>Total</b></td>';
                            for ($c = 1; $c <= 10; $c++) {
                                echo '<td class="text-center"><b>';
                                if (${"ag{$c}t"} > ${"hg{$c}t"}) {
                                    echo '<span class="text-success">';
                                } elseif (${"ag{$c}t"} < ${"hg{$c}t"}) {
                                    echo '<span class="text-danger">';
                                } elseif (${"ag{$c}t"} == ${"hg{$c}t"}) {
                                    echo '<span class="text-primary">';
                                }
                                echo ${"ag{$c}t"} . '</span></b></td>';
                            }
                            echo '<td class="text-center"><b>' . $a_tot . '</b></td>';
                            echo '</tr>';
                            echo '<tr height="15px"></tr>';
                            // start rows for home team
                            echo '<tr class="bg-table-header">';
                            echo '<th><a href="team_stats.php?team_id=' . $result_matchups['home_team_id'] . '">' . $result_matchups['ht'] . '</a> (';
                            if ($home_wins > $home_losses) {
                                echo '<span class="text-success">';
                            } elseif ($home_wins < $home_losses) {
                                echo '<span class="text-danger">';
                            } elseif ($home_wins == $home_losses) {
                                echo '<span class="text-primary">';
                            }
                            echo $home_wins . '-' . $home_losses . '-' . $home_ties . '</span>)</td>';
                            for ($hg = 1; $hg <= 10; $hg++) {
                                echo '<th class="text-center"><b>' . $hg . '</td>';
                            }
                            echo '<td class="text-center"><b>Total</b></td>';
                            echo '</tr>';
                            for ($h = 1; $h <= 3; $h++) {
                                // initialize the current player's total points
                                ${"hp{$h}t"} = 0;
                                echo '<tr>';
                                echo '<td><a href="player_stats.php?player_id=' . $result_matchups['hp' . $h . 'id'] . '">' . $result_matchups['hp' . $h] . '</a></td>';
                                for ($i = 1; $i <= 10; $i++) {
                                    echo '<td class="text-center">' . $result_matchups['hp' . $h . 'g' . $i] . '</td>';
                                    ${"hp{$h}t"} = ${"hp{$h}t"} + $result_matchups['hp' . $h . 'g' . $i];
                                }
                                echo '<td class="text-center">' . ${"hp{$h}t"} . '</td>';
                                echo '</tr>';
                            }
                            echo '<tr class="bg-table-header">';
                            echo '<td><b>Total</b></td>';
                            for ($j = 1; $j <= 10; $j++) {
                                echo '<td class="text-center"><b>';
                                if (${"hg{$j}t"} > ${"ag{$j}t"}) {
                                    echo '<span class="text-success">';
                                } elseif (${"hg{$j}t"} < ${"ag{$j}t"}) {
                                    echo '<span class="text-danger">';
                                } elseif (${"hg{$j}t"} == ${"ag{$j}t"}) {
                                    echo '<span class="text-primary">';
                                }
                                echo ${"hg{$j}t"} . '</span></b></td>';
                            }
                            echo '<td class="text-center"><b>' . $h_tot . '</b></td>';
                            echo '</tr>';
                            echo '</table>';
                            $counter--;
                            if ($counter > 0) {
                                echo '<hr />';
                            }
                        }
                        $query_matchups->free_result();
                    } else {
                        echo '<p class="text-center text-danger"><b>There are no results for the selected season.</b></p>';
                    }
                } else {
                    echo '<hr />';
                    echo '<b>' . $result_name['team_name'] . '</b> are not in the league in the current season.';
                }
                $query_name->free_result();
            } else {
                echo '<hr />';
                echo '<p class="text-center text-danger"><b>No record of this team has been found.</b></p>';
            }
        } else {
            echo '<hr />';
            echo '<p class="text-center text-danger"><b>No team has been selected.</b></p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
