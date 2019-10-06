<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <!-- <h2 class="text-center">Page Heading</h2> -->
        <!-- <hr /> -->

        <?php
        // set the format for the number of teams in the tournament
        // the 2 options tested to work are 32 and 64, with 64 meaning 64 minus the number of store nights in the league... ie: usually around 54 teams
        $tournament_format = 32;
        if (isset($tourny_rankings_status) && ($tourny_rankings_status == 1 || $tourny_rankings_status == 2)) {
            echo '<h2 class="text-center">';
            if ($tourny_rankings_status == 2) {
                echo 'Final ';
            } elseif ($tourny_rankings_status == 1) {
                echo 'Current ';
            }
            echo 'Tournament Qualifier Rankings</h2>';
            echo '<hr />';
            // find the number of store_divisions plus the number of teams at each
            $query_stores_divisions = $conn->query("SELECT COUNT(*) AS num_teams, s.store_id, d.division_id, s.store_city, d.day_name FROM (SELECT DISTINCT away_team_id AS team_id, store_id, division_id FROM schedule WHERE season_id=$cur_season_id UNION SELECT DISTINCT home_team_id, store_id, division_id FROM schedule WHERE season_id=$cur_season_id ORDER BY store_id ASC, division_id ASC) AS team_list JOIN stores AS s ON (team_list.store_id=s.store_id) JOIN divisions AS d ON (team_list.division_id=d.division_id) GROUP BY store_id, division_id ORDER BY store_id ASC, division_id ASC");
            $num_stores_divisions = $query_stores_divisions->num_rows;
            // find the numbers of automatic qualifiers
            $fp_teams = array(); // initialize first place teams array
            $aq_teams = array(); // initialize other automatic qualifiers array
            $wc_teams = array(); // initialize wild card teams array
            $tot_auto_qualifiers = 0;
            $tourny_short1 = 0;
            $tourny_short2 = 0;
            while ($result_stores_divisions = $query_stores_divisions->fetch_assoc()) {
                $num_auto_qualifiers = 0;
                $cur_auto_qualifiers = 0;
                if ($result_stores_divisions['num_teams'] > 15) {
                    $num_auto_qualifiers = $num_auto_qualifiers + 4;
                    $cur_auto_qualifiers = 3;
                } elseif ($result_stores_divisions['num_teams'] > 10) {
                    $num_auto_qualifiers = $num_auto_qualifiers + 3;
                    $cur_auto_qualifiers = 2;
                } elseif ($result_stores_divisions['num_teams'] > 5) {
                    $num_auto_qualifiers = $num_auto_qualifiers + 2;
                    $cur_auto_qualifiers = 1;
                } elseif ($result_stores_divisions['num_teams'] > 0) {
                    $num_auto_qualifiers = $num_auto_qualifiers + 1;
                    $cur_auto_qualifiers = 0;
                }
                // $tot_auto_qualifiers = $tot_auto_qualifiers + $num_auto_qualifiers;
                // order all the teams by fp, aq and wc in the same query
                $query_team_groups = $conn->query("(SELECT 'fp' AS designation, t.team_id, t.team_name, (st.wins-st.losses) AS pct, st.wins, st.losses, st.ties, st.total_points, (st.total_points/(st.wins+st.losses+st.ties)) AS avg, st.standings_order FROM standings AS st JOIN teams AS t ON (st.team_id=t.team_id) WHERE st.season_id=$cur_season_id && st.store_id={$result_stores_divisions['store_id']} && st.division_id={$result_stores_divisions['division_id']} && t.tourny_show=1 ORDER BY st.standings_order ASC LIMIT 1) UNION (SELECT 'aq' AS designation, t.team_id, t.team_name, (st.wins-st.losses) AS pct, st.wins, st.losses, st.ties, st.total_points, (st.total_points/(st.wins+st.losses+st.ties)) AS avg, st.standings_order FROM standings AS st JOIN teams AS t ON (st.team_id=t.team_id) WHERE st.season_id=$cur_season_id && st.store_id={$result_stores_divisions['store_id']} && st.division_id={$result_stores_divisions['division_id']} && t.tourny_show=1 ORDER BY st.standings_order ASC LIMIT 1,$cur_auto_qualifiers) UNION (SELECT 'wc' AS designation, t.team_id, t.team_name, (st.wins-st.losses) AS pct, st.wins, st.losses, st.ties, st.total_points, (st.total_points/(st.wins+st.losses+st.ties)) AS avg, st.standings_order FROM standings AS st JOIN teams AS t ON (st.team_id=t.team_id) WHERE st.season_id=$cur_season_id && st.store_id={$result_stores_divisions['store_id']} && st.division_id={$result_stores_divisions['division_id']} && t.tourny_show=1 ORDER BY st.standings_order ASC LIMIT $num_auto_qualifiers,99)");
                $cur_num_teams = $query_team_groups->num_rows;
                if ($cur_num_teams == 0) {
                    // no teams at this store are coming to the tournament
                    $tourny_short1++;
                    $tourny_short2 = $tourny_short2 + $cur_auto_qualifiers;
                } elseif ($cur_num_teams < $cur_auto_qualifiers + 1) {
                    // there is a first place team coming, but there are not enough total teams coming to the tournament at this store to meet their allotment
                    $tourny_short2 = $tourny_short2 + $cur_auto_qualifiers - $cur_num_teams + 1;
                }
                while ($result_team_groups = $query_team_groups->fetch_assoc()) {
                    if ($result_team_groups['designation'] == 'fp') {
                        $tot_auto_qualifiers++;
                        $fp_teams[] = array('team_id' => $result_team_groups['team_id'], 'team_name' => $result_team_groups['team_name'], 'pct' => $result_team_groups['pct'], 'wins' => $result_team_groups['wins'], 'losses' => $result_team_groups['losses'], 'ties' => $result_team_groups['ties'], 'avg' => $result_team_groups['avg'], 'total_points' => $result_team_groups['total_points'], 'store_id' => $result_stores_divisions['store_id'], 'store_city' => $result_stores_divisions['store_city'], 'division_id' => $result_stores_divisions['division_id'], 'day_name' => $result_stores_divisions['day_name']);
                    } elseif ($result_team_groups['designation'] == 'aq') {
                        $tot_auto_qualifiers++;
                        $aq_teams[] = array('team_id' => $result_team_groups['team_id'], 'team_name' => $result_team_groups['team_name'], 'pct' => $result_team_groups['pct'], 'wins' => $result_team_groups['wins'], 'losses' => $result_team_groups['losses'], 'ties' => $result_team_groups['ties'], 'avg' => $result_team_groups['avg'], 'total_points' => $result_team_groups['total_points'], 'store_id' => $result_stores_divisions['store_id'], 'store_city' => $result_stores_divisions['store_city'], 'division_id' => $result_stores_divisions['division_id'], 'day_name' => $result_stores_divisions['day_name']);
                    } elseif ($result_team_groups['designation'] == 'wc') {
                        $wc_teams[] = array('team_id' => $result_team_groups['team_id'], 'team_name' => $result_team_groups['team_name'], 'pct' => $result_team_groups['pct'], 'wins' => $result_team_groups['wins'], 'losses' => $result_team_groups['losses'], 'ties' => $result_team_groups['ties'], 'avg' => $result_team_groups['avg'], 'total_points' => $result_team_groups['total_points'], 'store_id' => $result_stores_divisions['store_id'], 'store_city' => $result_stores_divisions['store_city'], 'division_id' => $result_stores_divisions['division_id'], 'day_name' => $result_stores_divisions['day_name']);
                    }
                }
                $query_team_groups->free_result();
            }
            $query_stores_divisions->free_result();
            // find the total number of tournament teams
            if ($tournament_format == 64) {
                $total_tourny_teams = 64 - $num_stores_divisions;
            } else {
                $total_tourny_teams = $tournament_format; // was used in the spring 2014 league
            }
            $num_stores_divisions1 = $num_stores_divisions - $tourny_short1; // adjusts the number of first place teams based on whether there are stores that don't have any teams coming
            $rem_auto_qualifiers = $tot_auto_qualifiers - $num_stores_divisions1 - $tourny_short2; // finds the number of non-first place automatic qualifiers
            $num_wildcards = $total_tourny_teams - $num_stores_divisions1 - $rem_auto_qualifiers; // finds the number of wildcard teams
            // first place teams
            foreach ($fp_teams as $key => $row) {
                $avg[$key] = $row['avg'];
                $pct[$key] = $row['pct'];
            }
            // sort the first place teams array by avg, pct
            array_multisort($avg, SORT_DESC, $pct, SORT_DESC, $fp_teams);
            // wild card teams
            foreach ($wc_teams as $key => $row) {
                $avg[$key] = $row['avg'];
                $pct[$key] = $row['pct'];
            }
            array_multisort($avg, SORT_DESC, $pct, SORT_DESC, $wc_teams); // sorts the wildcard teams array by avg, pct
            $wc_teams_reduced = array_slice($wc_teams, 0, $num_wildcards); // reduce the number of wildcard teams to the what the number allowed
            $additional_qualifiers = array_merge($aq_teams, $wc_teams_reduced); // merge the automatic qualifiers and wildcard teams together
            $temp1 = array();
            $temp2 = array();
            foreach ($additional_qualifiers as $temp3) {
                $temp1[] = $temp3['avg'];
                $temp2[] = $temp3['pct'];
            }
            array_multisort($temp1, SORT_DESC, $temp2, SORT_DESC, $additional_qualifiers); // sorts the non-first place qualifiers by avg, pct
            echo 'The ' . $total_tourny_teams . ' tournament qualifiers are determined based on the following criteria:<br />';
            echo '<ol>';
            echo '<li>First place teams are ranked 1 through ' . $num_stores_divisions1 . ' by total points with ties being broken by record.</li>';
            echo '<li>In the group below the first place teams are the ' . $rem_auto_qualifiers . ' other automatic qualifiers*, which are now combined with the ' . $num_wildcards . ' wildcard teams. This group is sorted by total points with ties being broken by record.</li>';
            echo '</ol>';
            // echo '* - The number of other automatic qualifiers (in addition to first place teams) from each stores varies depending on how many teams are at that store. If a store has at least 17 teams, it will have 5 other automatic qualifiers. If a store has from 13 to 17 teams, it will have 4 other automatic qualifiers.  If a store has from 9 to 12 teams, it will have 3 other automatic qualifiers. If a store has from 5 to 8 teams, it will have 2 other automatic qualifiers. If a store has less than 5 teams, it will have only 1 other automatic quailfier.<br /><br />';
            echo '* - The number of other automatic qualifiers (in addition to first place teams) from each stores varies depending on how many teams are at that store. If a store has at least 16 teams, it will have 3 other automatic qualifiers. If a store has from 11 to 15 teams, it will have 2 other automatic qualifiers.  If a store has from 6 to 10 teams, it will have 1 other automatic qualifiers.<br /><br />';
            if ($tourny_rankings_status == 1) {
                echo '<b>NOTE:</b> Since teams from different stores don\'t always have the same number of games played during the season, listing total points on this page isn\'t helpful in determining rankings. So, even though the final rankings are determined by total points, average score is listed here instead when calculating the current tournament qualifiers.<br /><br />';
                echo '<hr  />';
            }
            $team_rank = 1;
            // display the first place teams
            echo '<p class="text-center text-success"><b>First Place Teams:</b></p>';
            // echo '<p class="text-success"><b>First Place Teams (earning a first round bye):</b></p>';
            echo '<table class="table table-bordered mb-5 table1"><tr class="bg-table-header">';
            echo '<th class="text-center">Ranking</th>';
            echo '<th>Team Name</th>';
            echo '<th>Store</th>';
            echo '<th class="text-center">Wins</th>';
            echo '<th class="text-center">Losses</th>';
            echo '<th class="text-center">Ties</th>';
            echo '<th class="text-center">';
            // check show status to set the table heading
            if ($tourny_rankings_status == 1) {
                echo '<b>Avg Points</b></td></tr>';
            } else {
                echo '<b>Tot Points</b></td></tr>';
            }
            echo '</th></tr>';
            foreach ($fp_teams as $key => $value) {
                echo '<tr>';
                echo '<td class="text-center">' . $team_rank . '</td>';
                echo '<td><a href="team_stats.php?store_id=' . $value['store_id'] . '&division_id=' . $value['division_id'] . '&team_id=' . $value['team_id'] . '">' . $value['team_name'] . '</a></td>';
                echo '<td><a href="store_home.php?store_id=' . $value['store_id'] . '&division_id=' . $value['division_id'] . '">' . $value['store_city'] . ' (' . $value['day_name'] . ')</a></td>';
                echo '<td class="text-center">' . $value['wins'] . '</td>';
                echo '<td class="text-center">' . $value['losses'] . '</td>';
                echo '<td class="text-center">' . $value['ties'] . '</td>';
                echo '<td class="text-center">';
                // check show status to determine whether avg or total points are displyed
                if ($tourny_rankings_status == 1) {
                    printf("%.1f", $value['avg']);
                } else {
                    echo $value['total_points'];
                }
                echo '</td></tr>';
                $team_rank++;
            }
            echo '</table>';
            // display all additional qualifiers
            echo '<p class="text-center text-success"><b>Additional Qualifiers:</b></p>';
            echo '<table class="table table-bordered mb-5 table1"><tr class="bg-table-header">';
            echo '<th class="text-center">Ranking</th>';
            echo '<th>Team Name</th>';
            echo '<th>Store</th>';
            echo '<th class="text-center">Wins</th>';
            echo '<th class="text-center">Losses</th>';
            echo '<th class="text-center">Ties</th>';
            echo '<th class="text-center">';
            // check show status to set the table heading
            if ($tourny_rankings_status == 1) {
                echo '<b>Avg Points</b></td></tr>';
            } else {
                echo '<b>Tot Points</b></td></tr>';
            }
            echo '</th></tr>';
            foreach ($additional_qualifiers as $key => $value) {
                echo '<tr>';
                echo '<td class="text-center">' . $team_rank . '</td>';
                echo '<td><a href="team_stats.php?store_id=' . $value['store_id'] . '&division_id=' . $value['division_id'] . '&team_id=' . $value['team_id'] . '">' . $value['team_name'] . '</a></td>';
                echo '<td><a href="store_home.php?store_id=' . $value['store_id'] . '&division_id=' . $value['division_id'] . '">' . $value['store_city'] . ' (' . $value['day_name'] . ')</a></td>';
                echo '<td class="text-center">' . $value['wins'] . '</td>';
                echo '<td class="text-center">' . $value['losses'] . '</td>';
                echo '<td class="text-center">' . $value['ties'] . '</td>';
                echo '<td class="text-center">';
                // check show status to determine whether avg or total points are displyed
                if ($tourny_rankings_status == 1) {
                    printf('%.1f', $value['avg']);
                } else {
                    echo $value['total_points'];
                }
                echo '</td></tr>';
                $team_rank++;
            }
            echo '</table>';
        } else {
            echo '<h2 class="text-center">Tournament Qualifier Rankings</h2>';
            echo '<hr />';
            echo '<b>Please check back a little later in the season to see the current/final tournament rankings.</b>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
