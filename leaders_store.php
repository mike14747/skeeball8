<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Single Store Leader Board</h2>
        <hr />

        <?php
        if (isset($get_season_id)) {
            $query_season_id = $get_season_id;
        } else {
            $query_season_id = $cur_season_id;
        }
        // find the store name based on the store_id
        $query_stores = $conn->query("SELECT s.store_name, d.day_name FROM stores AS s, divisions AS d WHERE s.store_id=$get_store_id && d.division_id=$get_division_id && s.active=1");
        if ($query_stores->num_rows == 1) {
            $result_store = $query_stores->fetch_assoc();
            echo '<p><a href="store_home.php?store_id=' . $get_store_id . '&division_id=' . $get_division_id . '"><b>' . $result_store['store_name'] . '</b> (' . $result_store['day_name'] . ')</a></p>';
            echo '<hr />';
            $cat_header1 = 'Player, high season average / game';
            $cat_header2 = 'Player, 10-game high';
            $cat_header3 = 'Player, 1-game high';
            $cat_header4 = 'Team, 10-game high average';
            $cat_header5 = 'Team, 10-game high';
            $cat_header6 = 'Team, 1-game high';
            $heading1 = 'Average';
            $heading2 = 'Points';
            $heading3 = 'Points';
            $heading4 = 'Average';
            $heading5 = 'Points';
            $heading6 = 'Points';
            for ($l = 1; $l <= 6; $l++) {
                if ($l == 1) {
                    // start individual player leaders
                    echo '<h4 class="text-center mb-4 text-success">Individual Leaders</h4>';
                }
                if ($l == 4) {
                    // start team leaders
                    echo '<hr class="mt-4 mb-4"/>';
                    echo '<h4 class="text-center mb-4 text-success">Team Leaders</h4>';
                }
                if ($l == 1) {
                    // find best player average score per game for the season
                    $query_leaders1 = $conn->query("SELECT COUNT(*)*10 AS player_played, p.player_id, p.full_name, ROUND(AVG(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10)/10,5) AS avg, tp.season_id, tp.team_played, tv2.tv21 FROM results AS r JOIN players AS p ON (r.player_id=p.player_id) JOIN (SELECT (st.wins+st.losses+st.ties) AS team_played, st.season_id, st.store_id, st.division_id, st.team_id FROM standings AS st JOIN seasons AS s ON (st.season_id=s.season_id) ORDER BY st.season_id DESC, st.store_id ASC, st.division_id ASC, team_played DESC) AS tp ON (r.season_id=tp.season_id && r.store_id=tp.store_id && r.division_id=tp.division_id && r.team_id=tp.team_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT COUNT(*)*10 AS player_played, ROUND(AVG(r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10)/10,5) AS tv21, tp.team_played FROM results AS r JOIN (SELECT (wins+losses+ties) AS team_played, season_id, store_id, division_id, team_id FROM standings ORDER BY season_id DESC, store_id ASC, division_id ASC, team_played DESC) AS tp ON (r.season_id=tp.season_id && r.store_id=tp.store_id && r.division_id=tp.division_id && r.team_id=tp.team_id) WHERE r.season_id=$query_season_id && r.store_id=$get_store_id && r.division_id=$get_division_id && r.player_id!=100 GROUP BY r.season_id, r.player_id HAVING player_played>=team_played/2 ORDER BY tv21 DESC LIMIT 20,1) AS tv) AS tv1) AS tv2 WHERE r.season_id=$query_season_id && r.store_id=$get_store_id && r.division_id=$get_division_id && r.player_id!=100 GROUP BY r.season_id, r.player_id HAVING player_played>=team_played/2 && avg>=tv2.tv21 ORDER BY avg DESC");
                } elseif ($l == 2) {
                    // find player 10-game total points leaders
                    $query_leaders2 = $conn->query("SELECT p.full_name, r.week_id, r.team_id, r.player_id, (r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10) as pts, d.division_id, s.store_id, s.store_city, tv2.tv21 FROM results AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN divisions AS d ON (r.division_id=d.division_id) JOIN teams AS t ON (r.team_id=t.team_id) JOIN players AS p ON (r.player_id=p.player_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT (g1+g2+g3+g4+g5+g6+g7+g8+g9+g10) as tv21 FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id && player_id!=100 GROUP BY player_id, week_id, team_id, player_num ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.season_id=$query_season_id && r.store_id=$get_store_id && r.division_id=$get_division_id && r.player_id!=100 GROUP BY r.player_id, r.week_id, r.team_id, r.player_num HAVING pts>=tv2.tv21 ORDER BY pts DESC, p.full_name ASC");
                } elseif ($l == 3) {
                    // find individual 1-game high
                    $query_leaders3 = $conn->query("SELECT tg2.season_id, tg2.store_id, tg2.division_id, tg2.player_id, p.full_name, s.store_city, tg2.pts, tv2.tv21 FROM (SELECT tg1.season_id, tg1.store_id, tg1.division_id, tg1.player_id, tg1.player_game AS pts FROM (SELECT season_id, store_id, division_id, player_id, g1 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g2 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g3 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g4 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g5 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g6 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g7 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g8 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g9 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, store_id, division_id, player_id, g10 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id) AS tg1) AS tg2 JOIN stores AS s ON (tg2.store_id=s.store_id) JOIN players AS p ON (tg2.player_id=p.player_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT tg.player_game AS tv21 FROM (SELECT season_id, player_id, g1 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g2 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g3 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g4 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g5 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g6 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g7 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g8 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g9 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id UNION ALL SELECT season_id, player_id, g10 AS player_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id) AS tg ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 HAVING pts>=tv2.tv21 ORDER BY pts DESC");
                } elseif ($l == 4) {
                    // find team 10-game average
                    $query_leaders4 = $conn->query("SELECT st.team_id, t.team_name, ROUND(10*total_points/(st.wins+st.losses+st.ties),5) AS avg, s.store_id, s.store_city, st.division_id, tv2.tv21 FROM standings AS st JOIN stores AS s ON (st.store_id=s.store_id) JOIN teams AS t ON (st.team_id=t.team_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT ROUND(10*total_points/(st.wins+st.losses+st.ties),5) AS tv21, st.store_id, st.division_id FROM standings AS st WHERE st.season_id=$query_season_id && st.store_id=$get_store_id && st.division_id=$get_division_id GROUP BY st.team_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE st.season_id=$query_season_id && st.store_id=$get_store_id && st.division_id=$get_division_id HAVING avg>=tv2.tv21 ORDER BY avg DESC");
                } elseif ($l == 5) {
                    // find team 10-game high
                    $query_leaders5 = $conn->query("SELECT r.team_id, t.team_name, (SUM(r.g1)+SUM(r.g2)+SUM(r.g3)+SUM(r.g4)+SUM(r.g5)+SUM(r.g6)+SUM(r.g7)+SUM(r.g8)+SUM(r.g9)+SUM(r.g10)) as pts, d.division_id, s.store_id, s.store_city, tv2.tv21 FROM results AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN divisions AS d ON (r.division_id=d.division_id) JOIN teams AS t ON (r.team_id=t.team_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT (SUM(g1)+SUM(g2)+SUM(g3)+SUM(g4)+SUM(g5)+SUM(g6)+SUM(g7)+SUM(g8)+SUM(g9)+SUM(g10)) as tv21 FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY team_id, week_id ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 WHERE r.season_id=$query_season_id && r.store_id=$get_store_id && r.division_id=$get_division_id GROUP BY r.team_id, r.week_id HAVING pts>=tv2.tv21 ORDER BY pts DESC");
                } elseif ($l == 6) {
                    // find team 1-game high
                    $query_leaders6 = $conn->query("SELECT tg2.season_id, tg2.store_id, tg2.division_id, tg2.team_id, t.team_name, s.store_city, tg2.pts, tv2.tv21 FROM (SELECT tg1.season_id, tg1.store_id, tg1.division_id, tg1.team_id, tg1.team_game AS pts FROM (SELECT season_id, store_id, division_id, team_id, SUM(g1) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g2) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g3) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g4) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g5) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g6) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g7) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g8) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g9) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, store_id, division_id, team_id, SUM(g10) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id) AS tg1) AS tg2 JOIN stores AS s ON (tg2.store_id=s.store_id) JOIN teams AS t ON (tg2.team_id=t.team_id), (SELECT MAX(CASE WHEN tv21_row=1 THEN tv1.tv21 ELSE 0 END) AS tv21 FROM (SELECT COUNT(*) AS tv21_row, tv.tv21 FROM (SELECT tg.team_game AS tv21 FROM (SELECT season_id, team_id, SUM(g1) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g2) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g3) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g4) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g5) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g6) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g7) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g8) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g9) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id UNION ALL SELECT season_id, team_id, SUM(g10) AS team_game FROM results WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id GROUP BY week_id, team_id) AS tg ORDER BY tv21 DESC LIMIT $num_leaders,1) AS tv) AS tv1) AS tv2 HAVING pts>=tv2.tv21 ORDER BY pts DESC");
                }
                if (${"query_leaders{$l}"}->num_rows > 0) {
                    // there are individual best average leaders to display
                    ${"total_rows{$l}"} = ${"query_leaders{$l}"}->num_rows;
                    ${"counter{$l}"} = 0;
                    ${"rank{$l}"} = 1;
                    ${"previous_value{$l}"} = 0;
                    if ($l == 1) {
                        echo '<p class="text-center"><b>' . ${"cat_header{$l}"} . '</b><br /><span class="small">(must play in a minimum of 50% of your team\'s games to qualify)</span></p>';
                    } else {
                        echo '<p class="text-center"><b>' . ${"cat_header{$l}"} . '</b></p>';
                    }
                    echo '<table class="table table-bordered table1 mb-4"><tr class="bg-table-header">';
                    echo '<th class="text-center">Rank</th>';
                    echo '<th>Player</th>';
                    echo '<th class="text-center">' . ${"heading{$l}"} . '</th>';
                    echo '</tr>';


                    while (${"result_leaders{$l}"} = ${"query_leaders{$l}"}->fetch_assoc()) {
                        ${"tie_value{$l}"} = ${"result_leaders{$l}"}['tv21'];
                        if ($l == 1 || $l == 4) {
                            if (${"result_leaders{$l}"}['avg'] > ${"result_leaders{$l}"}['tv21']) {
                                echo '<tr>';
                                echo '<td class="text-center">';
                                if (${"result_leaders{$l}"}['avg'] != ${"previous_value{$l}"}) {
                                    echo ${"rank{$l}"};
                                    ${"previous_rank{$l}"} = ${"rank{$l}"};
                                } else {
                                    echo ${"previous_rank{$l}"};
                                }
                                echo '</td>';
                                echo '<td>';
                                if ($l == 1) {
                                    echo '<a href="player_stats.php?player_id=' . ${"result_leaders{$l}"}['player_id'] . '">' . ${"result_leaders{$l}"}['full_name'] . '</a>';
                                } elseif ($l == 4) {
                                    echo '<a href="team_stats.php?team_id=' . ${"result_leaders{$l}"}['team_id'] . '">' . ${"result_leaders{$l}"}['team_name'] . '</a>';
                                }
                                echo '</td>';
                                echo '<td class="text-center">';
                                printf('%.1f', ${"result_leaders{$l}"}['avg']) . '</td>';
                                echo '</tr>';
                                ${"rank{$l}"}++;
                                ${"counter{$l}"}++;
                                ${"previous_value{$l}"} = ${"result_leaders{$l}"}['avg'];
                            } else {
                                break;
                            }
                        } else {
                            if (${"result_leaders{$l}"}['pts'] > ${"result_leaders{$l}"}['tv21']) {
                                echo '<tr>';
                                echo '<td class="text-center">';
                                if (${"result_leaders{$l}"}['pts'] != ${"previous_value{$l}"}) {
                                    echo ${"rank{$l}"};
                                    ${"previous_rank{$l}"} = ${"rank{$l}"};
                                } else {
                                    echo ${"previous_rank{$l}"};
                                }
                                echo '</td>';
                                echo '<td>';
                                if ($l == 2 || $l == 3) {
                                    echo '<a href="player_stats.php?player_id=' . ${"result_leaders{$l}"}['player_id'] . '">' . ${"result_leaders{$l}"}['full_name'] . '</a>';
                                } elseif ($l == 5 || $l == 6) {
                                    echo '<a href="team_stats.php?team_id=' . ${"result_leaders{$l}"}['team_id'] . '">' . ${"result_leaders{$l}"}['team_name'] . '</a>';
                                }
                                echo '</td>';
                                echo '<td class="text-center">';
                                echo ${"result_leaders{$l}"}['pts'] . "</td>";
                                echo '</tr>';
                                ${"rank{$l}"}++;
                                ${"counter{$l}"}++;
                                ${"previous_value{$l}"} = ${"result_leaders{$l}"}['pts'];
                            } else {
                                break;
                            }
                        }
                    }
                    ${"query_leaders{$l}"}->free_result();
                    // if there are ties that overflow, make a row showing how many
                    if (${"counter{$l}"} < $num_leaders && ${"total_rows{$l}"} >= $num_leaders + 1) {
                        ${"num_tied{$l}"} = ${"total_rows{$l}"} - ${"counter{$l}"};
                        echo '<tr>';
                        echo '<td></td>';
                        echo '<td class="text-right"><span class="text-success">' . ${"num_tied{$l}"} . ' tied at: </span></td>';
                        echo '<td class="text-center">';
                        if ($l == 1 || $l == 4) {
                            printf('%.1f', ${"tie_value{$l}"});
                        } else {
                            echo ${"tie_value{$l}"};
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    // there are no leaders to display
                    echo '<p class="text-center text-danger"><b>There are no leaders to display for the selected season.</b></p>';
                }
            }
        } else {
            echo '<hr />';
            echo '<p>No leaders found for this store.</p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
