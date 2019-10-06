<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");

if (isset($_SESSION['status']) && $_SESSION['status'] == "update_standings" && isset($_SESSION['season_id']) && isset($_SESSION['store_id'])  && isset($_SESSION['division_id'])&& isset($_SESSION['week_id'])) {
	$season_id = $_SESSION['season_id'];
	$store_id = $_SESSION['store_id'];
	$division_id = $_SESSION['division_id'];
	$week_id = $_SESSION['week_id'];
	// find distinct teams in the selected season at the selected store and division
	$query_teams = $conn1->query("SELECT DISTINCT team FROM (SELECT away_team_id AS team FROM schedule WHERE season_id=$season_id && store_id=$store_id && division_id=$division_id UNION ALL SELECT home_team_id FROM schedule WHERE season_id=$season_id && store_id=$store_id && division_id=$division_id) AS ct ORDER BY team ASC");
	if ($query_teams->num_rows > 0) {
		while ($result_teams = $query_teams->fetch_assoc()) {
			$wins = 0;
			$losses = 0;;
			$ties = 0;
			$total_points = 0;
			// find all matchups for the current team and update their info in the standings table
			$query_matchups = $conn1->query("SELECT r.week_id, sch.alley, sch.start_time, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g1 ELSE 0 END) AS 1_g1, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g2 ELSE 0 END) AS 1_g2, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g3 ELSE 0 END) AS 1_g3, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g4 ELSE 0 END) AS 1_g4, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g5 ELSE 0 END) AS 1_g5, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g6 ELSE 0 END) AS 1_g6, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g7 ELSE 0 END) AS 1_g7, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g8 ELSE 0 END) AS 1_g8, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g9 ELSE 0 END) AS 1_g9, SUM(CASE WHEN r.team_id={$result_teams['team']} THEN r.g10 ELSE 0 END) AS 1_g10, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g1 ELSE 0 END) AS 2_g1, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g2 ELSE 0 END) AS 2_g2, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g3 ELSE 0 END) AS 2_g3, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g4 ELSE 0 END) AS 2_g4, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g5 ELSE 0 END) AS 2_g5, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g6 ELSE 0 END) AS 2_g6, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g7 ELSE 0 END) AS 2_g7, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g8 ELSE 0 END) AS 2_g8, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g9 ELSE 0 END) AS 2_g9, SUM(CASE WHEN r.team_id!={$result_teams['team']} THEN r.g10 ELSE 0 END) AS 2_g10 FROM schedule AS sch JOIN results AS r ON (sch.season_id=r.season_id AND sch.store_id=r.store_id AND sch.division_id=r.division_id AND sch.week_id=r.week_id AND (sch.away_team_id=r.team_id OR sch.home_team_id=r.team_id)) WHERE sch.season_id=$season_id && sch.store_id=$store_id && sch.division_id=$division_id && (sch.away_team_id={$result_teams['team']} OR sch.home_team_id={$result_teams['team']}) GROUP BY r.week_id, sch.start_time, sch.alley ORDER BY r.week_id, sch.start_time ASC, sch.alley ASC");
			if ($query_matchups->num_rows > 0) {
				while ($result_matchups = $query_matchups->fetch_assoc()) {
					// loop through each game and adjust the above variables
					for ($g_num=1; $g_num<=10; $g_num++) {
						$total_points = $total_points + $result_matchups['1_g'.$g_num];
						if ($result_matchups['1_g'.$g_num] > $result_matchups['2_g'.$g_num]) {
							$wins++;
						} elseif ($result_matchups['2_g'.$g_num] > $result_matchups['1_g'.$g_num]) {
							$losses++;
						} elseif ($result_matchups['1_g'.$g_num] == $result_matchups['2_g'.$g_num]) {
							$ties++;
						}
					}
				}
				$query_matchups->free_result();
			}
			// now that the results are known for this team, update their data in the standings table
			$conn1->query("INSERT INTO standings (standings_id, season_id, store_id, division_id, team_id, wins, losses, ties, total_points, standings_order) VALUES (null, $season_id, $store_id, $division_id, {$result_teams['team']}, $wins, $losses, $ties, $total_points, 0) ON DUPLICATE KEY UPDATE wins=$wins, losses=$losses, ties=$ties, total_points=$total_points, standings_order=0");
		}
		$query_teams->free_result();
	}
	// now that the standings table has been populated with new data, set the standings_order for each team
	$query_teams_standings = $conn1->query("SELECT team_id, (wins+(.5*ties))/(wins+losses+ties) AS pct, total_points FROM standings WHERE season_id=$season_id && store_id=$store_id && division_id=$division_id ORDER BY pct DESC, total_points DESC");
	if ($query_teams_standings->num_rows > 0) {
		$rank = 1;
		while ($result_teams_standings = $query_teams_standings->fetch_assoc()) {
			$conn1->query("UPDATE standings SET standings_order=$rank WHERE season_id=$season_id && store_id=$store_id && division_id=$division_id && team_id={$result_teams_standings['team_id']}");
			$rank++;
		}
		$query_teams_standings->free_result();
	}
	$header_url = "Location: results6_update_success.php?status=done&season_id=" . $season_id . "&store_id=" . $store_id . "&division_id=" . $division_id . "&week_id=" . $week_id;
	header($header_url);
}
?>