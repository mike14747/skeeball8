 <?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");

// find distinct teams for all stores in the schedule for seasons 1 through 5
$query_teams = $conn1->query("SELECT season_id, store_id, division_id, team_id FROM (SELECT season_id, store_id, division_id, away_team_id AS team_id FROM schedule WHERE season_id<6 UNION ALL SELECT season_id, store_id, division_id, home_team_id FROM schedule WHERE season_id<6) AS ct GROUP BY season_id, store_id, division_id, team_id ORDER BY season_id ASC, store_id ASC, division_id ASC, team_id ASC");
if ($query_teams->num_rows > 0) {
	while ($result_teams = $query_teams->fetch_assoc()) {
		$wins = 0;
		$losses = 0;;
		$ties = 0;
		$total_points = 0;
		// find all matchups for the current team and update their info in the standings table
		$query_matchups = $conn1->query("SELECT r.season_id, r.store_id, r.division_id, r.week_id, MAX(CASE WHEN r.team_id={$result_teams['team_id']} THEN r.team_id ELSE NULL END) AS team_id, SUM(CASE WHEN r.team_id={$result_teams['team_id']} THEN r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10 ELSE 0 END) AS current_team_points, MAX(CASE WHEN r.team_id!={$result_teams['team_id']} THEN r.team_id ELSE NULL END) AS opposing_team_id, SUM(CASE WHEN r.team_id!={$result_teams['team_id']} THEN r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10 ELSE 0 END) AS opposing_team_points FROM schedule AS sch JOIN results AS r ON (sch.season_id=r.season_id AND sch.store_id=r.store_id AND sch.division_id=r.division_id AND sch.week_id=r.week_id AND (sch.away_team_id=r.team_id OR sch.home_team_id=r.team_id)) WHERE sch.season_id={$result_teams['season_id']} && sch.store_id={$result_teams['store_id']} && sch.division_id={$result_teams['division_id']} && (sch.away_team_id={$result_teams['team_id']} OR sch.home_team_id={$result_teams['team_id']}) GROUP BY r.week_id ORDER BY r.week_id ASC");
		if ($query_matchups->num_rows > 0) {
			while ($result_matchups = $query_matchups->fetch_assoc()) {
				// loop through each match and adjust the above variables
				$total_points = $total_points + $result_matchups['current_team_points'];
				if ($result_matchups['current_team_points'] > $result_matchups['opposing_team_points']) {
					$wins++;
				} elseif ($result_matchups['opposing_team_points'] > $result_matchups['current_team_points']) {
					$losses++;
				} elseif ($result_matchups['current_team_points'] == $result_matchups['opposing_team_points']) {
					$ties++;
				}
			}
			// echo "S" . $result_teams['season_id'] . "-S" . $result_teams['store_id'] . "-D" . $result_teams['division_id'] . ", Team: " . $result_teams['team_id'] . " (" . $wins . "-" . $losses . "-" . $ties . ", " . $total_points . ")<br />";
			$query_matchups->free_result();
		}
		// now that the results are known for this season, store, division and team, update their data in the standings table
		$conn1->query("INSERT INTO standings (standings_id, season_id, store_id, division_id, team_id, wins, losses, ties, total_points, standings_order) VALUES (null, {$result_teams['season_id']}, {$result_teams['store_id']}, {$result_teams['division_id']}, {$result_teams['team_id']}, $wins, $losses, $ties, $total_points, 0) ON DUPLICATE KEY UPDATE wins=$wins, losses=$losses, ties=$ties, total_points=$total_points, standings_order=0");
	}
	$query_teams->free_result();
}
// now that the standings table has been populated with new data, set the standings_order for each team
$query_distinct_standings = $conn1->query("SELECT season_id, store_id, division_id FROM schedule WHERE season_id<6 GROUP BY season_id, store_id, division_id ORDER BY season_id, store_id, division_id");
if ($query_distinct_standings->num_rows > 0) {
	while ($result_distinct_standings = $query_distinct_standings->fetch_assoc()) {
		$query_teams_standings = $conn1->query("SELECT team_id, (wins+(.5*ties))/(wins+losses+ties) AS pct, total_points FROM standings WHERE season_id={$result_distinct_standings['season_id']} && store_id={$result_distinct_standings['store_id']} && division_id={$result_distinct_standings['division_id']} ORDER BY pct DESC, total_points DESC");
		if ($query_teams_standings->num_rows > 0) {
			$rank = 1;
			while ($result_teams_standings = $query_teams_standings->fetch_assoc()) {
				$conn1->query("UPDATE standings SET standings_order=$rank WHERE season_id={$result_distinct_standings['season_id']} && store_id={$result_distinct_standings['store_id']} && division_id={$result_distinct_standings['division_id']} && team_id={$result_teams_standings['team_id']}");
				$rank++;
			}
			$query_teams_standings->free_result();
		}
	}
	$query_distinct_standings->free_result();
}
echo "Done!";
?>