<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">All-Time League-Wide Leader Board</h2>
        <hr />
        <div class="d-flex justify-content-center">
            <div class="d-flex flex-column mr-5 mt-2 mb-2">
                <p class="mb-2"><b>INDIVIDUAL RECORDS</b></p>
                <p class="small">Most Points: &nbsp;
                    <a href="leaders_all-time.php?group=1&type=1&period=1">Game</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=1&period=2">Match</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=1&period=4">Season AVG</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=1&period=5">Career</a></p>

                <p class="small">Most Games Played: &nbsp;
                    <a href="leaders_all-time.php?group=1&type=2&period=5">Career</a></p>

                <p class="small">Most 800+ Games: &nbsp;
                    <a href="leaders_all-time.php?group=1&type=8&period=2">Match</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=8&period=3">Season</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=8&period=5">Career</a></p>

                <p class="small">Most 700+ Games: &nbsp;
                    <a href="leaders_all-time.php?group=1&type=7&period=2">Match</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=7&period=3">Season</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=7&period=5">Career</a></p>

                <p class="small">Most 600+ Games: &nbsp;
                    <a href="leaders_all-time.php?group=1&type=6&period=2">Match</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=6&period=3">Season</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=6&period=5">Career</a></p>

                <p class="small">Most 500+ Games: &nbsp;
                    <a href="leaders_all-time.php?group=1&type=5&period=2">Match</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=5&period=3">Season</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=5&period=5">Career</a></p>

                <p class="small">Most 400+ Games: &nbsp;
                    <a href="leaders_all-time.php?group=1&type=4&period=2">Match</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=4&period=3">Season</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=1&type=4&period=5">Career</a></p>
            </div>
            <div class="d-flex flex-column mr-5 mt-2 mb-2">
                <p class="mb-2"><b>TEAM RECORDS</b></p>
                <p class="small">Most Points: &nbsp;
                    <a href="leaders_all-time.php?group=2&type=1&period=1">Game</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=2&type=1&period=2">Match</a> &nbsp;|&nbsp;
                    <a href="leaders_all-time.php?group=2&type=1&period=4">Season AVG</a></p>

                <p class="small mb-5">Best Record: &nbsp;
                    <a href="leaders_all-time.php?group=2&type=3&period=3">Season</a></p>

                <p class="mb-2"><b>YEAR BY YEAR LEADERS</b></p>

                <p class="small">Player: &nbsp;
                    <a href="leaders_all-time.php?group=3&type=1&period=4">Season AVG</a></p>

                <p class="small">Team: &nbsp;
                    <a href="leaders_all-time.php?group=3&type=3&period=3">Best Record</a></p>
            </div>
        </div>
        <hr />

        <?php
        // $get_group -> 1=individual, 2=team, 3=yearly
        // $get_type -> 1=points, 2=games, 3=record, 4=400games, 5=500games, 6=600games, 7=700games, 8=800games
        // $get_period -> 1=game, 2=match, 3=season, 4=season_avg, 5=career
        if (isset($get_group) && isset($get_type) && isset($get_period)) {
            // find out what the most recent finised season is
            $query_recent_season = $conn->query("SELECT season_id FROM schedule GROUP BY season_id HAVING CURDATE()>MAX(week_date) ORDER BY season_id DESC LIMIT 1");
            $result_recent_season = $query_recent_season->fetch_assoc();
            $fin_season = $result_recent_season['season_id'];
            if ($get_group != 3) {
                if (isset($get_show)) {
                    $num_show = $get_show;
                    $num_leaders = $get_show;
                    echo '<p class="text-center"><span class="small">Show top: &nbsp;&nbsp;';
                    if ($get_show == 20) {
                        echo '<b>20</b> &nbsp;|&nbsp; ';
                    } else {
                        echo '<a href="leaders_all-time.php?group=' . $get_group . '&amp;type=' . $get_type . '&amp;period=' . $get_period . '&amp;show=20">20</a> &nbsp;|&nbsp; ';
                    }
                    if ($get_show == 50) {
                        echo '<b>50</b> &nbsp;|&nbsp; ';
                    } else {
                        echo '<a href="leaders_all-time.php?group=' . $get_group . '&amp;type=' . $get_type . '&amp;period=' . $get_period . '&amp;show=50">50</a> &nbsp;|&nbsp; ';
                    }
                    if ($get_show == 100) {
                        echo '<b>100</b>';
                    } else {
                        echo '<a href="leaders_all-time.php?group=' . $get_group . '&amp;type=' . $get_type . '&amp;period=' . $get_period . '&amp;show=100">100</a>';
                    }
                    echo '</span></p>';
                } else {
                    $num_show = 20;
                    $num_leaders = 20;
                    echo '<p class="text-center">Show top: &nbsp;&nbsp;<b>20</b> &nbsp;|&nbsp; <a href="leaders_all-time.php?group=' . $get_group . '&amp;type=' . $get_type . '&amp;period=' . $get_period . '&amp;show=50">50</a> &nbsp;|&nbsp; <a href="leaders_all-time.php?group=' . $get_group . '&amp;type=' . $get_type . '&amp;period=' . $get_period . '&amp;show=100">100</a></p>';
                }
                $ties = 0;
                $better_than_tie = $num_show;
                $equal_to_tie = 0;
                $num_leaders_ties = $num_show - 1;
            }
            if ($get_group == 1) {
                // start individual all-time leaders group ----------------------------------------------------------------------------------------------------------------------------------------------------
                if ($get_type == 1) {
                    if ($get_period == 4) {
                        // find best player average score per game for a season
                        $query_leaders = $conn->query("SELECT COUNT(*)*10 AS player_played, r.season_id, tp1.team_played, r.player_id, p.full_name, ROUND(AVG(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10)/10, 5) AS avg, se.season_name, se.year, s.store_city, tv.tv21 FROM results AS r JOIN seasons AS se ON (r.season_id=se.season_id) JOIN stores AS s ON (r.store_id=s.store_id) JOIN divisions AS d ON (r.division_id=d.division_id) JOIN players AS p ON (r.player_id=p.player_id) JOIN (SELECT season_id, season_name, year, (CASE WHEN season_games=5 THEN 50 WHEN season_games=7 THEN 70 ELSE season_games END) AS team_played FROM seasons) AS tp1 ON (r.season_id=tp1.season_id), (SELECT COUNT(*)*10 AS player_played, ROUND(AVG(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10)/10, 5) AS tv21, tp.team_played FROM results AS r JOIN (SELECT season_id, season_name, year, (CASE WHEN season_games=5 THEN 50 WHEN season_games=7 THEN 70 ELSE season_games END) AS team_played FROM seasons ORDER BY season_id DESC) AS tp ON (r.season_id=tp.season_id) WHERE r.season_id<=$fin_season && r.player_id!=100 GROUP BY r.season_id, r.player_id HAVING player_played>=team_played/2 ORDER BY tv21 DESC LIMIT $num_leaders, 1) AS tv WHERE r.season_id<=$fin_season && r.player_id!=100 GROUP BY r.season_id, r.player_id HAVING player_played>=team_played/2 && avg>=tv.tv21 ORDER BY avg DESC");
                        if ($query_leaders->num_rows > 0) {
                            $total_rows = $query_leaders->num_rows;
                            $counter = 0;
                            $rank = 1;
                            $previous_value = 0;
                            echo '<p class="text-center"><b>Player, high season average / game</b><br /><span class="small">(must play in a minimum of 50% of your team\'s games to qualify)</span></p>';
                            echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                            echo '<th class="text-center">Rank</th>';
                            echo '<th>Player</th>';
                            echo '<th class="text-center">Average</th>';
                            echo '</tr>';
                            while ($result_leaders = $query_leaders->fetch_assoc()) {
                                $tie_value = $result_leaders['tv21'];
                                if ($result_leaders['avg'] > $result_leaders['tv21']) {
                                    echo '<tr>';
                                    echo '<td class="text-center">';
                                    if ($result_leaders['avg'] != $previous_value) {
                                        echo $rank;
                                        $previous_rank = $rank;
                                    } else {
                                        echo $previous_rank;
                                    }
                                    echo '</td>';
                                    echo '<td><a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '">' . $result_leaders['full_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . '), <a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '&amp;season_id=' . $result_leaders['season_id'] . '">' . $result_leaders['season_name'] . '-' . $result_leaders['year'] . '</a></span>';
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                    printf('%.1f', $result_leaders['avg']) . '</td>';
                                    echo '</tr>';
                                    $rank++;
                                    $counter++;
                                    $previous_value = $result_leaders['avg'];
                                } else {
                                    break;
                                }
                            }
                            // if there are ties that overflow, make a row showing how many
                            if ($counter < $num_leaders && $total_rows >= $num_leaders + 1) {
                                $num_tied = $total_rows - $counter;
                                echo '<tr>';
                                echo '<td></td>';
                                echo '<td class="text-right"><span class="text-success">' . $num_tied . ' tied at: </span></td>';
                                echo '<td class="text-center">';
                                printf('%.1f', $tie_value) . '</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        } else {
                            // there are no individual best average leaders to display
                            echo '<p class="text-center text-danger"><b>There are no individual best average leaders to display.</b></p>';
                        }
                    } elseif ($get_period == 1 || $get_period == 2 || $get_period == 5) {
                        if ($get_period == 1) {
                            $query_leaders = $conn->query("SELECT p.player_id, p.full_name, r.player_game AS pts, s.store_city, se.season_id, se.season_name, se.year, r.week_id, tv2.tv21 FROM (SELECT season_id, store_id, week_id, player_id, g1 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g2 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g3 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g4 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g5 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g6 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g7 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g8 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g9 AS player_game FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g10 AS player_game FROM results) AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN players AS p ON (r.player_id=p.player_id) JOIN seasons AS se ON (r.season_id=se.season_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT season_id, store_id, week_id, player_id, g1 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g2 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g3 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g4 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g5 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g6 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g7 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g8 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g9 AS tv21 FROM results UNION ALL SELECT season_id, store_id, week_id, player_id, g10 AS tv21 FROM results ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.player_id!=100 HAVING r.player_game>=tv2.tv21 ORDER BY pts DESC, se.season_id ASC");
                            $header = 'Player, most points in a game';
                        } elseif ($get_period == 2) {
                            $query_leaders = $conn->query("SELECT p.player_id, p.full_name, (r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10) AS pts, s.store_city, se.season_id, se.season_name, se.year, r.week_id, tv2.tv21 FROM results AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN players AS p ON (r.player_id=p.player_id) JOIN seasons AS se ON (r.season_id=se.season_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT (r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10) AS tv21 FROM results AS r WHERE r.player_id!=100 GROUP BY r.player_id, r.season_id, r.week_id, r.team_id, r.player_num ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.player_id!=100 GROUP BY r.player_id, r.season_id, r.week_id, r.team_id, r.player_num HAVING pts>=tv2.tv21 ORDER BY pts DESC");
                            $header = 'Player, most points in a match';
                        } elseif ($get_period == 5) {
                            $query_leaders = $conn->query("SELECT r.player_id, p.full_name, SUM(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10) AS pts, s.store_city, tv2.tv21 FROM results AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN players AS p ON (r.player_id=p.player_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT SUM(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10) AS tv21 FROM results AS r WHERE r.player_id!=100 GROUP BY r.player_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.player_id!=100 GROUP BY r.player_id HAVING pts>=tv2.tv21 ORDER BY pts DESC");
                            $header = 'Player, most career points';
                        }
                        if ($query_leaders->num_rows > 0) {
                            $total_rows = $query_leaders->num_rows;
                            $counter = 0;
                            $rank = 1;
                            $previous_value = 0;
                            echo '<p class="text-center"><b>' . $header . '</b></p>';
                            echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                            echo '<th class="text-center">Rank</th>';
                            echo '<th>Player</th>';
                            echo '<th class="text-center">Points</th>';
                            echo '</tr>';
                            while ($result_leaders = $query_leaders->fetch_assoc()) {
                                $tie_value = $result_leaders['tv21'];
                                if ($result_leaders['pts'] > $result_leaders['tv21']) {
                                    echo '<tr>';
                                    echo '<td class="text-center">';
                                    if ($result_leaders['pts'] != $previous_value) {
                                        echo $rank;
                                        $previous_rank = $rank;
                                    } else {
                                        echo $previous_rank;
                                    }
                                    echo '</td>';
                                    echo '<td>';
                                    if ($get_period == 1 || $get_period == 2) {
                                        echo '<a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '">' . $result_leaders['full_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . '), <a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '&amp;season_id=' . $result_leaders['season_id'] . '">' . $result_leaders['season_name'] . '-' . $result_leaders['year'] . ' (Week ' . $result_leaders['week_id'] . ')</a></span>';
                                    } elseif ($get_period == 5) {
                                        echo '<a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '">' . $result_leaders['full_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . ')</span>';
                                    }
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                    echo $result_leaders['pts'] . '</td>';
                                    echo '</tr>';
                                    $rank++;
                                    $counter++;
                                    $previous_value = $result_leaders['pts'];
                                } else {
                                    break;
                                }
                            }
                            // if there are ties that overflow, make a row showing how many
                            if ($counter < $num_leaders && $total_rows >= $num_leaders + 1) {
                                $num_tied = $total_rows - $counter;
                                echo '<tr>';
                                echo '<td></td>';
                                echo '<td class="text-right"><span class="text-success">' . $num_tied . ' tied at: </span></td>';
                                echo '<td class="text-center">' . $result_leaders['pts'] . '</td>';
                                echo '</tr>';
                            }


                            echo '</table>';
                        } else {
                            // there are no individual best average leaders to display
                            echo '<p class="text-center text-danger"><b>There are no individual best scores to display.</b></p>';
                        }
                    }
                } elseif ($get_type == 2) {
                    // find individual most career games played
                    $query_leaders = $conn->query("SELECT p.player_id, p.full_name, COUNT(*)*10 AS games, s.store_city, tv2.tv21 FROM results AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN players AS p ON (r.player_id=p.player_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT COUNT(*)*10 AS tv21 FROM results AS r WHERE r.player_id!=100 GROUP BY r.player_id ORDER BY tv21 DESC LIMIT $num_show,1) AS tv) AS tv1) AS tv2 WHERE r.player_id!=100 GROUP BY r.player_id HAVING games>=tv2.tv21 ORDER BY games DESC");
                    if ($query_leaders->num_rows > 0) {
                        $total_rows = $query_leaders->num_rows;
                        $counter = 0;
                        $rank = 1;
                        $previous_value = 0;
                        echo '<p class="text-center"><b>Individual, Career Games Played</b></p>';
                        echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                        echo '<th class="text-center">Rank</th>';
                        echo '<th>Player</th>';
                        echo '<th class="text-center">Games</th>';
                        echo '</tr>';
                        while ($result_leaders = $query_leaders->fetch_assoc()) {
                            $tie_value = $result_leaders['tv21'];
                            if ($result_leaders['games'] > $result_leaders['tv21']) {
                                echo '<tr>';
                                echo '<td class="text-center">';
                                if ($result_leaders['games'] != $previous_value) {
                                    echo $rank;
                                    $previous_rank = $rank;
                                } else {
                                    echo $previous_rank;
                                }
                                echo '</td>';
                                echo '<td><a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '">' . $result_leaders['full_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . ')</span>';
                                echo '</td>';
                                echo '<td class="text-center">' . $result_leaders['games'] . '</td>';
                                echo '</tr>';
                                $rank++;
                                $counter++;
                                $previous_value = $result_leaders['games'];
                            } else {
                                break;
                            }
                        }
                        // if there are ties that overflow, make a row showing how many
                        if ($counter < $num_leaders && $total_rows >= $num_leaders + 1) {
                            $num_tied = $total_rows - $counter;
                            echo '<tr>';
                            echo '<td></td>';
                            echo '<td class="text-right"><span class="text-success">' . $num_tied . ' tied at: </span></td>';
                            echo '<td class="text-center">' . $result_leaders['games'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        // there are no individual best average leaders to display
                        echo '<p class="text-center text-danger"><b>There are no career games played leaders to display.</b></p>';
                    }
                } elseif ($get_type == 4 || $get_type == 5 || $get_type == 6 || $get_type == 7 || $get_type == 8) {
                    $get_value = $get_type * 100;
                    if ($get_period == 2) {
                        $query_leaders = $conn->query("SELECT r.week_id, r.player_id, p.full_name, COUNT(r.score) AS num, r.season_id, se.season_name, se.year, r.store_id, s.store_city, tv2.tv21 FROM (SELECT season_id, store_id, week_id, team_id, player_num, player_id, g1 AS score FROM results WHERE g1>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g2 FROM results WHERE g2>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g3 FROM results WHERE g3>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g4 FROM results WHERE g4>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g5 FROM results WHERE g5>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g6 FROM results WHERE g6>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g7 FROM results WHERE g7>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g8 FROM results WHERE g8>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g9 FROM results WHERE g9>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g10 FROM results WHERE g10>=$get_value) AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN players AS p ON (r.player_id=p.player_id) JOIN seasons AS se ON (r.season_id=se.season_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT COUNT(r.score) AS tv21 FROM (SELECT season_id, store_id, week_id, team_id, player_num, player_id, g1 AS score FROM results WHERE g1>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g2 FROM results WHERE g2>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g3 FROM results WHERE g3>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g4 FROM results WHERE g4>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g5 FROM results WHERE g5>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g6 FROM results WHERE g6>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g7 FROM results WHERE g7>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g8 FROM results WHERE g8>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g9 FROM results WHERE g9>=$get_value UNION ALL SELECT season_id, store_id, week_id, team_id, player_num, player_id, g10 FROM results WHERE g10>=$get_value) AS r WHERE r.score>=$get_value GROUP BY r.player_id, r.season_id, r.week_id, r.team_id, r.player_num ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.score>=$get_value GROUP BY r.player_id, r.season_id, r.week_id, r.team_id, r.player_num HAVING num>=tv2.tv21 ORDER BY num DESC");
                        $header = 'Player, most ' . $get_value . '+ games in a match';
                    } elseif ($get_period == 3) {
                        $query_leaders = $conn->query("SELECT r.player_id, p.full_name, COUNT(r.score) AS num, r.season_id, se.season_name, se.year, r.store_id, s.store_city, tv2.tv21 FROM (SELECT season_id, store_id, team_id, player_id, g1 AS score FROM results WHERE g1>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g2 FROM results WHERE g2>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g3 FROM results WHERE g3>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g4 FROM results WHERE g4>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g5 FROM results WHERE g5>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g6 FROM results WHERE g6>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g7 FROM results WHERE g7>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g8 FROM results WHERE g8>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g9 FROM results WHERE g9>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g10 FROM results WHERE g10>=$get_value) AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN players AS p ON (r.player_id=p.player_id) JOIN seasons AS se ON (r.season_id=se.season_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT COUNT(r.score) AS tv21 FROM (SELECT season_id, store_id, team_id, player_id, g1 AS score FROM results WHERE g1>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g2 FROM results WHERE g2>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g3 FROM results WHERE g3>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g4 FROM results WHERE g4>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g5 FROM results WHERE g5>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g6 FROM results WHERE g6>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g7 FROM results WHERE g7>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g8 FROM results WHERE g8>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g9 FROM results WHERE g9>=$get_value UNION ALL SELECT season_id, store_id, team_id, player_id, g10 FROM results WHERE g10>=$get_value ) AS r WHERE r.score>=$get_value GROUP BY r.player_id, r.season_id, r.team_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.score>=$get_value GROUP BY r.player_id, r.season_id, r.team_id HAVING num>=tv2.tv21 ORDER BY num DESC");
                        $header = 'Player, most ' . $get_value . '+ games in a season';
                    } elseif ($get_period == 5) {
                        $query_leaders = $conn->query("SELECT p.player_id, p.full_name, COUNT(r.score) AS num, s.store_id, s.store_city, tv2.tv21 FROM (SELECT store_id, player_id, g1 AS score FROM results WHERE g1>=$get_value UNION ALL SELECT store_id, player_id, g2 FROM results WHERE g2>=$get_value UNION ALL SELECT store_id, player_id, g3 FROM results WHERE g3>=$get_value UNION ALL SELECT store_id, player_id, g4 FROM results WHERE g4>=$get_value UNION ALL SELECT store_id, player_id, g5 FROM results WHERE g5>=$get_value UNION ALL SELECT store_id, player_id, g6 FROM results WHERE g6>=$get_value UNION ALL SELECT store_id, player_id, g7 FROM results WHERE g7>=$get_value UNION ALL SELECT store_id, player_id, g8 FROM results WHERE g8>=$get_value UNION ALL SELECT store_id, player_id, g9 FROM results WHERE g9>=$get_value UNION ALL SELECT store_id, player_id, g10 FROM results WHERE g10>=$get_value) AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN players AS p ON (r.player_id=p.player_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT COUNT(r.score) AS tv21 FROM (SELECT store_id, player_id, g1 AS score FROM results WHERE g1>=$get_value UNION ALL SELECT store_id, player_id, g2 FROM results WHERE g2>=$get_value UNION ALL SELECT store_id, player_id, g3 FROM results WHERE g3>=$get_value UNION ALL SELECT store_id, player_id, g4 FROM results WHERE g4>=$get_value UNION ALL SELECT store_id, player_id, g5 FROM results WHERE g5>=$get_value UNION ALL SELECT store_id, player_id, g6 FROM results WHERE g6>=$get_value UNION ALL SELECT store_id, player_id, g7 FROM results WHERE g7>=$get_value UNION ALL SELECT store_id, player_id, g8 FROM results WHERE g8>=$get_value UNION ALL SELECT store_id, player_id, g9 FROM results WHERE g9>=$get_value UNION ALL SELECT store_id, player_id, g10 FROM results WHERE g10>=$get_value ) AS r WHERE r.score>=$get_value GROUP BY r.player_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.score>=$get_value GROUP BY r.player_id HAVING num>=tv2.tv21 ORDER BY num DESC");
                        $header = 'Player, most career ' . $get_value . '+ games';
                    }
                    if ($query_leaders->num_rows > 0) {
                        $total_rows = $query_leaders->num_rows;
                        $counter = 0;
                        $rank = 1;
                        $previous_value = 0;
                        echo '<p class="text-center"><b>' . $header . '</b></p>';
                        echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                        echo '<th class="text-center">Rank</th>';
                        echo '<th>Player</th>';
                        echo '<th class="text-center">' . $get_value . '+ games</th>';
                        echo '</tr>';
                        while ($result_leaders = $query_leaders->fetch_assoc()) {
                            $tie_value = $result_leaders['tv21'];
                            if ($result_leaders['num'] > $result_leaders['tv21']) {
                                echo '<tr>';
                                echo '<td class="text-center">';
                                if ($result_leaders['num'] != $previous_value) {
                                    echo $rank;
                                    $previous_rank = $rank;
                                } else {
                                    echo $previous_rank;
                                }
                                echo '</td>';
                                echo '<td>';
                                if ($get_period == 2) {
                                    echo '<a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '">' . $result_leaders['full_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . '), <a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '&amp;season_id=' . $result_leaders['season_id'] . '">' . $result_leaders['season_name'] . '-' . $result_leaders['year'] . ' (Week ' . $result_leaders['week_id'] . ')</a></span>';
                                } elseif ($get_period == 3) {
                                    echo '<a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '">' . $result_leaders['full_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . '), <a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '&amp;season_id=' . $result_leaders['season_id'] . '">' . $result_leaders['season_name'] . '-' . $result_leaders['year'] . '</a></span>';
                                } elseif ($get_period == 5) {
                                    echo '<a href="player_stats.php?player_id=' . $result_leaders['player_id'] . '">' . $result_leaders['full_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . ')</span>';
                                }
                                echo '</td>';
                                echo '<td class="text-center">' . $result_leaders['num'] . '</td>';
                                echo '</tr>';
                                $rank++;
                                $counter++;
                                $previous_value = $result_leaders['num'];
                            } else {
                                break;
                            }
                        }
                        // if there are ties that overflow, make a row showing how many
                        if ($counter < $num_leaders && $total_rows >= $num_leaders + 1) {
                            $num_tied = $total_rows - $counter;
                            echo '<tr>';
                            echo '<td></td>';
                            echo '<td class="text-right"><span class="text-success">' . $num_tied . ' tied at: </span></td>';
                            echo '<td class="text-center">' . $result_leaders['num'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        // there are no individual best average leaders to display
                        echo '<p class="text-center text-danger"><b>There are no individual best scores to display.</b></p>';
                    }
                }
            } elseif ($get_group == 2) {
                // start team all-time leaders group ----------------------------------------------------------------------------------------------------------------------------------------------------
                if ($get_type == 1) {
                    if ($get_period == 1 || $get_period == 2) {
                        if ($get_period == 1) {
                            $query_leaders = $conn->query("SELECT t.team_id, t.team_name, r.division_id, s.store_city, se.season_name, se.year, r.week_id, r.team_game AS pts, tv2.tv21 FROM (SELECT season_id, store_id, division_id, week_id, team_id, SUM(g1) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g2) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g3) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g4) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g5) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g6) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g7) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g8) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g9) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT season_id, store_id, division_id, week_id, team_id, SUM(g10) AS team_game FROM results GROUP BY season_id, store_id, division_id, week_id, team_id ORDER BY team_game DESC) AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN teams AS t ON (r.team_id=t.team_id) JOIN seasons AS se ON (r.season_id=se.season_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, SUM(tv.tv21) AS tv21 FROM (SELECT SUM(g1) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g2) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g3) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g4) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g5) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g6) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g7) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g8) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g9) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id UNION ALL SELECT SUM(g10) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.team_game>=tv2.tv21 GROUP BY r.season_id, r.store_id, r.division_id, r.week_id, r.team_id ORDER BY pts DESC");
                        } elseif ($get_period == 2) {
                            $query_leaders = $conn->query("SELECT t.team_id, t.team_name, s.store_city, se.season_name, se.year, r.week_id, r.pts AS pts, tv2.tv21 FROM (SELECT season_id, store_id, team_id, week_id, (SUM(g1)+SUM(g2)+SUM(g3)+SUM(g4)+SUM(g5)+SUM(g6)+SUM(g7)+SUM(g8)+SUM(g9)+SUM(g10)) AS pts FROM results GROUP BY season_id, store_id, division_id, week_id, team_id ORDER BY pts DESC) AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN teams AS t ON (r.team_id=t.team_id) JOIN seasons AS se ON (r.season_id=se.season_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, SUM(tv.tv21) AS tv21 FROM (SELECT (SUM(g1)+SUM(g2)+SUM(g3)+SUM(g4)+SUM(g5)+SUM(g6)+SUM(g7)+SUM(g8)+SUM(g9)+SUM(g10)) AS tv21 FROM results GROUP BY season_id, store_id, division_id, week_id, team_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 GROUP BY r.season_id, r.week_id, r.team_id HAVING pts>=tv2.tv21 ORDER BY pts DESC");
                        }
                        if ($query_leaders->num_rows > 0) {
                            $total_rows = $query_leaders->num_rows;
                            $counter = 0;
                            $rank = 1;
                            $previous_value = 0;
                            echo '<p class="text-center"><b>';
                            if ($get_period == 1) {
                                echo 'Team, 1-game high';
                            } elseif ($get_period == 2) {
                                echo 'Team, 10-game high';
                            }
                            echo '</b></p>';
                            echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                            echo '<th class="text-center">Rank</th>';
                            echo '<th>Team</th>';
                            echo '<th class="text-center">Points</th>';
                            echo '</tr>';
                            while ($result_leaders = $query_leaders->fetch_assoc()) {
                                $tie_value = $result_leaders['tv21'];
                                if ($result_leaders['pts'] > $result_leaders['tv21']) {
                                    echo '<tr>';
                                    echo '<td class="text-center">';
                                    if ($result_leaders['pts'] != $previous_value) {
                                        echo $rank;
                                        $previous_rank = $rank;
                                    } else {
                                        echo $previous_rank;
                                    }
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="team_stats.php?team_id=' . $result_leaders['team_id'] . '">' . $result_leaders['team_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . '), ' . $result_leaders['season_name'] . '-' . $result_leaders['year'] . ' (Week ' . $result_leaders['week_id'] . ')</span>';
                                    echo '</td>';
                                    echo '<td class="text-center">' . $result_leaders['pts'] . '</td>';
                                    echo '</tr>';
                                    $rank++;
                                    $counter++;
                                    $previous_value = $result_leaders['pts'];
                                } else {
                                    break;
                                }
                            }
                            // if there are ties that overflow, make a row showing how many
                            if ($counter < $num_leaders && $total_rows >= $num_leaders + 1) {
                                $num_tied = $total_rows - $counter;
                                echo '<tr>';
                                echo '<td></td>';
                                echo '<td class="text-right"><span class="text-success">' . $num_tied . ' tied at: </span></td>';
                                echo '<td class="text-center">' . $result_leaders['pts'] . '</td>';
                            }
                            echo '</tr>';
                            echo '</table>';
                        }
                    } elseif ($get_period == 4) {
                        // find best team average per match in a season
                        $query_leaders = $conn->query("SELECT st.team_id, t.team_name, ROUND(10*total_points/tp1.team_played,5) AS avg, s.store_id, s.store_city, se.season_name, se.year, tv.tv21 FROM standings AS st JOIN seasons AS se ON (st.season_id=se.season_id) JOIN teams AS t ON (st.team_id=t.team_id) JOIN stores AS s ON (st.store_id=s.store_id) JOIN (SELECT season_id, season_name, year, (CASE WHEN season_games=5 THEN 50 WHEN season_games=7 THEN 70 ELSE season_games END) AS team_played FROM seasons) AS tp1 ON (st.season_id=tp1.season_id), (SELECT ROUND(10*total_points/tp.team_played,5) AS tv21, tp.team_played FROM standings AS st JOIN (SELECT season_id, season_name, year, (CASE WHEN season_games=5 THEN 50 WHEN season_games=7 THEN 70 ELSE season_games END) AS team_played FROM seasons ORDER BY season_id DESC) AS tp ON (st.season_id=tp.season_id) WHERE st.season_id<=$fin_season GROUP BY st.season_id, st.team_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv WHERE st.season_id<=$fin_season GROUP BY st.season_id, st.team_id HAVING avg>=tv.tv21 ORDER BY avg DESC");
                        $header = 'Team, best average score per match for a season';
                        if ($query_leaders->num_rows > 0) {
                            $total_rows = $query_leaders->num_rows;
                            $counter = 0;
                            $rank = 1;
                            $previous_value = 0;
                            echo '<p class="text-center"><b>' . $header . '</b></p>';
                            echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                            echo '<th class="text-center">Rank</th>';
                            echo '<th>Team</th>';
                            echo '<th class="text-center">Average</th>';
                            echo '</tr>';
                            while ($result_leaders = $query_leaders->fetch_assoc()) {
                                $tie_value = $result_leaders['tv21'];
                                if ($result_leaders['avg'] > $result_leaders['tv21']) {
                                    echo '<tr>';
                                    echo '<td class="text-center">';
                                    if ($result_leaders['avg'] != $previous_value) {
                                        echo $rank;
                                        $previous_rank = $rank;
                                    } else {
                                        echo $previous_rank;
                                    }
                                    echo '</td>';
                                    echo '<td><a href="team_stats.php?team_id=' . $result_leaders['team_id'] . '">' . $result_leaders['team_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . '), ' . $result_leaders['season_name'] . '-' . $result_leaders['year'] . '</span>';
                                    echo '</td>';
                                    echo '<td class="text-center">';
                                    printf("%.1f", $result_leaders['avg']) . '</td>';
                                    echo '</tr>';
                                    $rank++;
                                    $counter++;
                                    $previous_value = $result_leaders['avg'];
                                } else {
                                    break;
                                }
                            }
                            // if there are ties that overflow, make a row showing how many
                            if ($counter < $num_leaders && $total_rows >= $num_leaders + 1) {
                                $num_tied = $total_rows - $counter;
                                echo '<tr>';
                                echo '<td></td>';
                                echo '<td class="text-right"><span class="text-success">' . $num_tied . ' tied at: </span></td>';
                                echo '<td class="text-center">' . $result_leaders['avg'] . '</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        } else {
                            // there are no individual best average leaders to display
                            echo '<p class="text-center"><b>There are no team best average leaders to display.</b></p>';
                        }
                    }
                } elseif ($get_type == 3) {
                    // find team, best record in a season (by winning pct)
                    $query_leaders = $conn->query("SELECT t.team_id, t.team_name, s.store_city, se.season_name, se.year, st.wins, st.losses, st.ties, ROUND(((st.wins+(.5*st.ties))/(st.wins+st.losses+st.ties)),5) AS pct, tv2.tv21 FROM standings AS st JOIN seasons AS se ON (st.season_id=se.season_id) JOIN stores AS s ON (st.store_id=s.store_id) JOIN teams AS t ON (st.team_id=t.team_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT ROUND(((st.wins+(.5*st.ties))/(st.wins+st.losses+st.ties)),5) AS tv21 FROM standings AS st WHERE st.season_id<=$fin_season && st.season_id>5 ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE st.season_id<=$fin_season && st.season_id>5 HAVING pct>=tv2.tv21 ORDER BY pct DESC");
                    if ($query_leaders->num_rows > 0) {
                        $total_rows = $query_leaders->num_rows;
                        $counter = 0;
                        $rank = 1;
                        $previous_value = 0;
                        echo '<p class="text-center"><b>Best record by a team in a season</b></p>';
                        echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                        echo '<th class="text-center">Rank</th>';
                        echo '<th>Team</th>';
                        echo '<th class="text-center">PCT</th>';
                        echo '</tr>';
                        while ($result_leaders = $query_leaders->fetch_assoc()) {
                            $tie_value = $result_leaders['tv21'];
                            if ($result_leaders['pct'] > $result_leaders['tv21']) {
                                echo '<tr>';
                                echo '<td class="text-center">';
                                if ($result_leaders['pct'] != $previous_value) {
                                    echo $rank;
                                    $previous_rank = $rank;
                                } else {
                                    echo $previous_rank;
                                }
                                echo '</td>';
                                echo '<td><a href="team_stats.php?team_id=' . $result_leaders['team_id'] . '">' . $result_leaders['team_name'] . '</a> <span class="small">- (' . $result_leaders['store_city'] . '), ' . $result_leaders['season_name'] . '-' . $result_leaders['year'] . '</span>';
                                echo '</td>';
                                echo '<td class="text-center">' . $result_leaders['wins'] . '-' . $result_leaders['losses'] . '-' . $result_leaders['ties'];
                                echo '</td>';
                                echo '<td class="text-center">';
                                printf('%.3f', $result_leaders['pct']) . '</td>';
                                echo '</tr>';
                                $rank++;
                                $counter++;
                                $previous_value = $result_leaders['pct'];
                            } else {
                                break;
                            }
                        }
                        // if there are ties that overflow, make a row showing how many
                        if ($counter < $num_leaders && $total_rows >= $num_leaders + 1) {
                            $num_tied = $total_rows - $counter;
                            echo '<tr>';
                            echo '<td></td>';
                            echo '<td class="text-right" colspan="2"><span class="text-success">' . $num_tied . ' tied at: </span></td>';
                            echo '<td class="text-center">';
                            printf("%.3f", $tie_value) . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        // there are no individual best average leaders to display
                        echo '<p class="text-center text-danger"><b>There are no best record leaders to display.</b></p>';
                    }
                }
            } elseif ($get_group == 3) {
                // start yearly all-time leaders group ----------------------------------------------------------------------------------------------------------------------------------------------------
                if ($get_type == 1 && $get_period == 4) {
                    if ($get_period == 4) {
                        // find individual best average year by year
                        // first, loop through all seasons in the range
                        $query_seasons = $conn->query("SELECT season_id, season_name, year FROM seasons WHERE season_id<=$fin_season ORDER BY season_id DESC");
                        echo '<p class="text-center"><b>Best Indiviual Average, Year by Year</b><br /><span class="small"><b>Note: </b>Must play in a minimum of 50% of your team\'s games to qualify.</span></p>';
                        if ($query_seasons->num_rows > 0) {
                            echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                            echo '<th>Season</th>';
                            echo '<th>Player</th>';
                            echo '<th class="text-center">Average</th>';
                            echo '</tr>';
                            while ($result_seasons = $query_seasons->fetch_assoc()) {
                                $query_leaders = $conn->query("SELECT COUNT(*)*10 AS player_played, r.season_id, tp1.team_played, r.player_id, p.full_name, ROUND(AVG(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10)/10,5) AS avg, se.season_name, se.year, s.store_city, tv.tv21 FROM results AS r JOIN seasons AS se ON (r.season_id=se.season_id) JOIN stores AS s ON (r.store_id=s.store_id) JOIN divisions AS d ON (r.division_id=d.division_id)JOIN players AS p ON (r.player_id=p.player_id) JOIN (SELECT season_id, season_name, year, (CASE WHEN season_games>7 THEN season_games WHEN season_games=7 THEN 70 WHEN season_games=5 THEN 50 ELSE NULL END) AS team_played FROM seasons WHERE season_id={$result_seasons['season_id']}) AS tp1 ON (r.season_id=tp1.season_id), (SELECT COUNT(*)*10 AS player_played, ROUND(AVG(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10)/10,5) AS tv21, tp.team_played FROM results AS r JOIN (SELECT season_id, season_name, year, (CASE WHEN season_games>7 THEN season_games WHEN season_games=7 THEN 70 WHEN season_games=5 THEN 50 ELSE NULL END) AS team_played FROM seasons WHERE season_id={$result_seasons['season_id']}) AS tp WHERE r.season_id={$result_seasons['season_id']} && r.player_id!=100 GROUP BY r.player_id HAVING player_played>=team_played/2 ORDER BY tv21 DESC LIMIT 1) AS tv WHERE r.season_id={$result_seasons['season_id']} && r.player_id!=100 GROUP BY r.player_id HAVING player_played>=team_played/2 && avg>=tv.tv21 ORDER BY avg DESC");
                                if ($query_leaders->num_rows > 0) {
                                    $cur_num_leaders = $query_leaders->num_rows;
                                    $counter = 1;
                                    while ($result_leaders = $query_leaders->fetch_assoc()) {
                                        if ($counter == 1) {
                                            echo '<tr>';
                                            echo '<td>' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</td>';
                                            echo '<td>';
                                        }
                                        ${"cur_player_id{$counter}"} = $result_leaders['player_id'];
                                        ${"cur_full_name{$counter}"} = $result_leaders['full_name'];
                                        ${"cur_store_city{$counter}"} = $result_leaders['store_city'];
                                        $cur_player_avg = $result_leaders['avg'];
                                        $counter++;
                                    }
                                    for ($c = 1; $c <= $cur_num_leaders; $c++) {
                                        if ($c > 1) {
                                            echo '<br />';
                                        }
                                        echo '<a href="player_stats.php?player_id=' . ${"cur_player_id{$c}"} . '">' . ${"cur_full_name{$c}"} . '</a><span class="small"> - (' . ${"cur_store_city{$c}"} . ')</span>';
                                        if ($c == $cur_num_leaders) {
                                            echo '</td>';
                                            echo '<td class="text-center">';
                                            printf("%.1f", $cur_player_avg);
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    $query_leaders->free_result();
                                } else {
                                    echo '<tr>';
                                    echo '<td>' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</td>';
                                    echo '<td class="text-center" colspan="2">There are currently no Best Average leaders to display for this season</td>';
                                    echo '</tr>';
                                }
                            }
                            echo '</table>';
                            $query_seasons->free_result();
                        } else {
                            echo 'There are currently no Best Average leaders to display';
                        }
                    }
                } elseif ($get_type == 3 && $get_period == 3) {
                    // find best team season record year by year
                    // first, loop through all seeasons in the range
                    $query_seasons = $conn->query("SELECT season_id, season_name, year FROM seasons WHERE season_id<=$fin_season && season_id >=6 ORDER BY season_id DESC");
                    echo '<p class="text-center"><b>Best Season Record, Year by Year</b><br /><span class="small"><b>Note: </b>This does not include seasons before Fall of 2011 because there were only 7 games per season.</span></p>';
                    if ($query_seasons->num_rows > 0) {
                        echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                        echo '<th>Season</th>';
                        echo '<th>Player</th>';
                        echo '<th class="text-center">Record</th>';
                        echo '</tr>';
                        while ($result_seasons = $query_seasons->fetch_assoc()) {
                            $query_leaders = $conn->query("SELECT st.season_id, s.store_city, st.team_id, t.team_name, st.wins, st.losses, st.ties, ROUND(((st.wins+(.5*st.ties))/(st.wins+st.losses+st.ties)),5) AS pct, st.total_points, st1.pct1 FROM standings AS st JOIN (SELECT st.season_id, MAX(ROUND(((st.wins+(.5*st.ties))/(st.wins+st.losses+st.ties)),5)) AS pct1 FROM standings AS st WHERE st.season_id={$result_seasons['season_id']} GROUP BY st.season_id ORDER BY st.season_id DESC) AS st1 ON (st.season_id=st1.season_id) JOIN stores AS s ON (st.store_id=s.store_id) JOIN teams AS t ON (st.team_id=t.team_id) HAVING pct=st1.pct1 ORDER BY st.season_id DESC, pct DESC, st.total_points DESC");
                            if ($query_leaders->num_rows > 0) {
                                $cur_num_leaders = $query_leaders->num_rows;
                                $counter = 1;
                                while ($result_leaders = $query_leaders->fetch_assoc()) {
                                    if ($counter == 1) {
                                        echo '<tr>';
                                        echo '<td>' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</td>';
                                        echo '<td>';
                                    }
                                    ${"cur_team_id{$counter}"} = $result_leaders['team_id'];
                                    ${"cur_team_name{$counter}"} = $result_leaders['team_name'];
                                    ${"cur_team_city{$counter}"} = $result_leaders['store_city'];
                                    $cur_team_record = $result_leaders['wins'] . '-' . $result_leaders['losses'] . '-' . $result_leaders['ties'];
                                    $counter++;
                                }
                                for ($c = 1; $c <= $cur_num_leaders; $c++) {
                                    if ($c > 1) {
                                        echo '<br />';
                                    }
                                    echo '<a href="team_stats.php?team_id=' . ${"cur_team_id{$c}"} . '">' . ${"cur_team_name{$c}"} . '</a><span class="small"> - (' . ${"cur_team_city{$c}"} . ')</span>';
                                    if ($c == $cur_num_leaders) {
                                        echo '</td>';
                                        echo '<td class="text-center">' . $cur_team_record . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                $query_leaders->free_result();
                            } else {
                                echo '<tr>';
                                echo '<td>' . $result_seasons['season_name'] . '-' . $result_seasons['year'] . '</td>';
                                echo '<td class="text-center" colspan="2">There are currently no Best Record leaders to display for this season</td>';
                                echo '</tr>';
                            }
                        }
                        echo '</table>';
                        $query_seasons->free_result();
                    } else {
                        echo 'There are currently no Team, year by year Best Record leaders to display';
                    }
                }
            }
        } else {
            echo '<p class="text-center">Please select a category from the above list.</p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
