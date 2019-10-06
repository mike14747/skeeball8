<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-center">Results</h2>
        <hr />

        <?php
        if (isset($get_store_id) && isset($get_division_id)) {
            if (isset($get_season_id)) {
                $query_season_id = $get_season_id;
            } else {
                $query_season_id = $cur_season_id;
            }
            // find the store and division names based on the store_id
            $query_stores = $conn->query("SELECT s.store_id, s.store_name, store_city, d.division_id, d.day_name FROM results AS r JOIN stores AS s ON (r.store_id=s.store_id) JOIN divisions AS d ON (r.division_id=d.division_id) WHERE s.store_id=$get_store_id && d.division_id=$get_division_id LIMIT 1");
            if ($query_stores->num_rows == 1) {
                $query_season = $conn->query("SELECT season_id, season_name, year FROM seasons WHERE season_id=$query_season_id LIMIT 1");
                // since store_id and division_id are set, show the store link for this store
                $result_store = $query_stores->fetch_assoc();
                echo '<p><a href="store_home.php?store_id=' . $get_store_id . '&division_id=' . $get_division_id . '"><b>' . $result_store['store_name'] . ' (' . $result_store['day_name'] . ')</a></b>';
                if ($query_season->num_rows == 1) {
                    $result_season = $query_season->fetch_assoc();
                    echo ' &nbsp;| &nbsp; Season: ' . $result_season['season_name'] . ', ' . $result_season['year'];
                    $query_season->free_result();
                    echo '</p>';
                    echo '<hr />';
                    $query_matchups = $conn->query("SELECT s.week_id, s.week_date1, s.away_team_id, s.home_team_id, s.alley, s.start_time, MAX(CASE WHEN t.team_id=s.away_team_id THEN t.team_name ELSE NULL END) AS at, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.player_id ELSE NULL END) AS ap1id, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN p.full_name ELSE NULL END) AS ap1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g1 ELSE NULL END) AS ap1g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g2 ELSE NULL END) AS ap1g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g3 ELSE NULL END) AS ap1g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g4 ELSE NULL END) AS ap1g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g5 ELSE NULL END) AS ap1g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g6 ELSE NULL END) AS ap1g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g7 ELSE NULL END) AS ap1g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g8 ELSE NULL END) AS ap1g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g9 ELSE NULL END) AS ap1g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g10 ELSE NULL END) AS ap1g10, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.player_id ELSE NULL END) AS ap2id, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN p.full_name ELSE NULL END) AS ap2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g1 ELSE NULL END) AS ap2g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g2 ELSE NULL END) AS ap2g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g3 ELSE NULL END) AS ap2g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g4 ELSE NULL END) AS ap2g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g5 ELSE NULL END) AS ap2g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g6 ELSE NULL END) AS ap2g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g7 ELSE NULL END) AS ap2g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g8 ELSE NULL END) AS ap2g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g9 ELSE NULL END) AS ap2g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g10 ELSE NULL END) AS ap2g10, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.player_id ELSE NULL END) AS ap3id, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN p.full_name ELSE NULL END) AS ap3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g1 ELSE NULL END) AS ap3g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g2 ELSE NULL END) AS ap3g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g3 ELSE NULL END) AS ap3g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g4 ELSE NULL END) AS ap3g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g5 ELSE NULL END) AS ap3g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g6 ELSE NULL END) AS ap3g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g7 ELSE NULL END) AS ap3g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g8 ELSE NULL END) AS ap3g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g9 ELSE NULL END) AS ap3g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g10 ELSE NULL END) AS ap3g10, MAX(CASE WHEN t.team_id=s.home_team_id THEN t.team_name ELSE NULL END) AS ht, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.player_id ELSE NULL END) AS hp1id, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN p.full_name ELSE NULL END) AS hp1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g1 ELSE NULL END) AS hp1g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g2 ELSE NULL END) AS hp1g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g3 ELSE NULL END) AS hp1g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g4 ELSE NULL END) AS hp1g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g5 ELSE NULL END) AS hp1g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g6 ELSE NULL END) AS hp1g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g7 ELSE NULL END) AS hp1g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g8 ELSE NULL END) AS hp1g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g9 ELSE NULL END) AS hp1g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g10 ELSE NULL END) AS hp1g10, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.player_id ELSE NULL END) AS hp2id, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN p.full_name ELSE NULL END) AS hp2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g1 ELSE NULL END) AS hp2g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g2 ELSE NULL END) AS hp2g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g3 ELSE NULL END) AS hp2g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g4 ELSE NULL END) AS hp2g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g5 ELSE NULL END) AS hp2g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g6 ELSE NULL END) AS hp2g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g7 ELSE NULL END) AS hp2g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g8 ELSE NULL END) AS hp2g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g9 ELSE NULL END) AS hp2g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g10 ELSE NULL END) AS hp2g10, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.player_id ELSE NULL END) AS hp3id, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN p.full_name ELSE NULL END) AS hp3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g1 ELSE NULL END) AS hp3g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g2 ELSE NULL END) AS hp3g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g3 ELSE NULL END) AS hp3g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g4 ELSE NULL END) AS hp3g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g5 ELSE NULL END) AS hp3g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g6 ELSE NULL END) AS hp3g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g7 ELSE NULL END) AS hp3g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g8 ELSE NULL END) AS hp3g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g9 ELSE NULL END) AS hp3g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g10 ELSE NULL END) AS hp3g10 FROM results AS r JOIN players AS p ON (r.player_id=p.player_id) JOIN teams AS t ON (r.team_id=t.team_id) JOIN (SELECT week_id, DATE_FORMAT(week_date, '%b-%d, %Y') AS week_date1, away_team_id, home_team_id, alley, start_time FROM schedule WHERE season_id=$query_season_id && store_id=$get_store_id && division_id=$get_division_id ORDER BY week_id DESC, start_time ASC, alley ASC) AS s ON (r.week_id=s.week_id AND (r.team_id=s.away_team_id || r.team_id=s.home_team_id)) WHERE r.season_id=$query_season_id && r.store_id=$get_store_id && r.division_id=$get_division_id GROUP BY r.week_id, s.start_time, s.alley ORDER BY r.week_id DESC, s.start_time ASC, s.alley ASC, r.team_id ASC, r.player_num ASC");
                    if ($query_matchups->num_rows > 0) {
                        // since there are results for this store and division, display them
                        while ($result_matchups = $query_matchups->fetch_assoc()) {
                            if (isset($cur_week) && $cur_week != $result_matchups['week_id']) {
                                echo '<hr class="w-100 mb-4" />';
                            }
                            if (!isset($cur_week) || (isset($cur_week) && $cur_week != $result_matchups['week_id'])) {
                                echo '<p class="text-center mt-4"><span class="text-success"><b>Week ' . $result_matchups['week_id'] . ' (' . $result_matchups['week_date1'] . ')</b></span></p>';
                            }
                            $cur_week = $result_matchups['week_id'];
                            // calculate away team game totals
                            $a_tot = 0;
                            for ($agt = 1; $agt <= 10; $agt++) {
                                ${"ag{$agt}t"} = $result_matchups['ap1g' . $agt] + $result_matchups['ap2g' . $agt] + $result_matchups['ap3g' . $agt];
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
                            // start table for current matchup
                            echo '<table class="table table-bordered mb-5"><tr class="bg-table-header">';
                            // start rows for away team
                            echo '<th><a href="team_stats.php?team_id=' . $result_matchups['away_team_id'] . '">' . $result_matchups['at'] . '</a> (';
                            if ($away_wins > $away_losses) {
                                echo '<span class="text-success">';
                            } elseif ($away_wins < $away_losses) {
                                echo '<span class="text-danger">';
                            } elseif ($away_wins == $away_losses) {
                                echo '<span class="text-primary">';
                            }
                            echo $away_wins . '-' . $away_losses . '-' . $away_ties . '</span>)</th>';
                            for ($ag = 1; $ag <= 10; $ag++) {
                                echo '<th class="text-center">' . $ag . '</th>';
                            }
                            echo '<th class="text-center">Total</th>';
                            echo '</tr>';
                            for ($a = 1; $a <= 3; $a++) {
                                // initialize the current player's total points
                                ${"ap{$a}t"} = 0;
                                echo '<tr>';
                                echo '<td><a href="player_stats.php?player_id=' . $result_matchups['ap' . $a . 'id'] . '">' . $result_matchups['ap' . $a] . '</a></td>';
                                for ($b = 1; $b <= 10; $b++) {
                                    echo '<td class="text-center">';
                                    // if a player gets a score of 500 or higher, make that score show up bold
                                    if ($result_matchups['ap' . $a . 'g' . $b] >= 500) {
                                        echo '<b>' . $result_matchups['ap' . $a . 'g' . $b] . '</b>';
                                    } else {
                                        echo $result_matchups['ap' . $a . 'g' . $b];
                                    }
                                    echo '</td>';
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
                            echo $home_wins . '-' . $home_losses . '-' . $home_ties . '</span>)</th>';
                            for ($hg = 1; $hg <= 10; $hg++) {
                                echo '<th class="text-center">' . $hg . '</th>';
                            }
                            echo '<th class="text-center">Total</th>';
                            echo '</tr>';
                            for ($h = 1; $h <= 3; $h++) {
                                // initialize the current player's total points
                                ${"hp{$h}t"} = 0;
                                echo '<tr>';
                                echo '<td><a href="player_stats.php?player_id=' . $result_matchups['hp' . $h . 'id'] . '">' . $result_matchups['hp' . $h] . '</a></td>';
                                for ($i = 1; $i <= 10; $i++) {
                                    echo '<td class="text-center">';
                                    // if a player gets a score of 500 or higher, make that score show up bold
                                    if ($result_matchups['hp' . $h . 'g' . $i] >= 500) {
                                        echo '<span class="strong">' . $result_matchups['hp' . $h . 'g' . $i] . '</span>';
                                    } else {
                                        echo $result_matchups['hp' . $h . 'g' . $i];
                                    }
                                    echo '</td>';
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
                        }
                        $query_matchups->free_result();
                    } else {
                        echo '<p class="text-center text-danger"><b>There are no results for the selected season.</b></p>';
                    }
                } else {
                    echo '<hr />';
                    echo '<p class="text-center text-danger"><b>The selected store is not valid.</b></p>';
                }
                $query_stores->free_result();
            } else {
                echo '<hr />';
                echo '<p class="text-center text-danger"><b>The selected store is not valid.</b></p>';
            }
        } else {
            echo '<hr />';
            echo '<p class="text-center text-danger"><b>No store has been selected.</b></p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
