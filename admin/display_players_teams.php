<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_GET['store_id'])) {
	$get_store_id = (int)$_GET['store_id'];
}

$query_stores = $conn1->query("SELECT store_id, store_name FROM stores WHERE store_id!=99 ORDER BY store_name ASC");
if ($query_stores->num_rows > 0) {
	echo "<p><b>Pick a store to display teams/players for:</b></p>";
	while ($result_stores = $query_stores->fetch_assoc()) {
		echo "<a href=\"display_players_teams.php?store_id=" . $result_stores['store_id'] . "\">" . $result_stores['store_name'] . "</a><br /><br />";
	}
	$query_stores->free_result();
} else {
	echo "<p><b>There are no stores to pick from in the database!</b></p>";
}
if (isset($get_store_id)) {
	// find the current store name
	echo "<hr width=\"100%\"><br />";
	// find the current store name
	$query_current_store = $conn1->query("SELECT store_name FROM stores WHERE store_id=$get_store_id LIMIT 1");
	while ($result_current_store = $query_current_store->fetch_assoc()) {
		$cur_store_name = $result_current_store['store_name'];
	}
	$query_current_store->free_result();
	echo "<span class=\"t16\"><b>" . $cur_store_name . "</b></span><br /><br />";
	// find all teams and players with team_ids and player_ids that have played in the current store
	$query_players_teams = $conn1->query("SELECT DISTINCT t.team_id, t.team_name, r.player_id, p.full_name FROM results AS r, players AS p, (SELECT team_id, team_name FROM teams WHERE store_id=$get_store_id) AS t WHERE r.store_id=$get_store_id && r.team_id=t.team_id && r.player_id=p.player_id ORDER BY t.team_name ASC, p.full_name ASC");
	echo "<table class=\"schedule3b\"><tr class=\"rowbg\">";
	echo "<td class=\"schedule1\"><b>Team Name</b></td>";
	echo "<td class=\"schedule2\"><b>Team ID</b></td>";
	echo "<td class=\"schedule1\"><b>Player Name</b></td>";
	echo "<td class=\"schedule2\"><b>Player ID</b></td>";
	echo "</tr>";
	while ($result_players_teams = $query_players_teams->fetch_assoc()) {
		// check to see if the current team_id has changed
		if (isset($cur_team_id) && $cur_team_id != $result_players_teams['team_id']) {
			// since the current team_id has changed, make the next row a divider
			echo "<tr class=\"rowbg2\">";
			echo "<td class=\"schedule1\">----------------------------------------</td>";
			echo "<td class=\"schedule2\">------</td>";
			echo "<td class=\"schedule1\">-----------------------------------</td>";
			echo "<td class=\"schedule2\">------</td>";
			echo "</tr>";
		}
		echo "<tr class=\"white\">";
		echo "<td class=\"schedule1\"><a href=\"../team_stats.php?team_id=" . $result_players_teams['team_id'] . "\">" . $result_players_teams['team_name'] . "</a></td>";
		echo "<td class=\"schedule2\"><a href=\"../team_stats.php?team_id=" . $result_players_teams['team_id'] . "\">" . $result_players_teams['team_id'] . "</a></td>";
		echo "<td class=\"schedule1\"><a href=\"../player_stats.php?player_id=" . $result_players_teams['player_id'] . "\">" . $result_players_teams['full_name'] . "</a></td>";
		echo "<td class=\"schedule2\"><a href=\"../player_stats.php?player_id=" . $result_players_teams['player_id'] . "\">" . $result_players_teams['player_id'] . "</a></td>";
		echo "</tr>";
		$cur_team_id = $result_players_teams['team_id'];
	}
	$query_players_teams->free_result();
	echo "</table>";
}

include ("admin_footer.php");
?>