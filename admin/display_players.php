<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include("admin_header.php");

echo "<p class=\"t16\"><b>The database currently contains the following players:</b></p>";
if (isset($_GET['sort_by'])) {
	$sort_by = $conn1->real_escape_string($_GET['sort_by']);
	$query_players = $conn1->query("SELECT p.full_name AS full_name, p.player_id AS player_id, s.store_id AS store_id, s.store_name AS store_name FROM players AS p JOIN stores AS s ON (p.store_id=s.store_id) ORDER BY $sort_by ASC");
} else {
	$query_players = $conn1->query("SELECT p.full_name AS full_name, p.player_id AS player_id, s.store_id AS store_id, s.store_name AS store_name FROM players AS p JOIN stores AS s ON (p.store_id=s.store_id) ORDER BY player_id ASC");
}
if ($query_players->num_rows > 0) {
	echo "<table class=\"schedule\"><tr class=\"rowbg\"><td class=\"schedule2\">";
	echo "<a href=\"display_players.php?sort_by=player_id\"\"><b>Player ID ^</b></a></td><td class=\"schedule1\">";
	echo "<a href=\"display_players.php?sort_by=full_name\"\"><b>Player Name ^</b></a></td><td class=\"schedule2\">";
	echo "<a href=\"display_players.php?sort_by=store_id\"\"><b>Store ID ^</b></a></td><td class=\"schedule1\">";
	echo "<a href=\"display_players.php?sort_by=store_name\"\"><b>Store Name ^</b></a></td></tr>";
	while ($result_players = $query_players->fetch_assoc()) {
		echo "<tr class=\"white\"><td class=\"schedule2\">";
		echo "<a href=\"../player_stats.php?player_id=" . $result_players['player_id'] . "\">" . $result_players['player_id'] . "</a></td><td class=\"schedule1\">";
		echo "<a href=\"../player_stats.php?player_id=" . $result_players['player_id'] . "\">" . $result_players['full_name'] . "</a></td><td class=\"schedule2\">";
		echo "<a href=\"../store_home.php?store_id=" . $result_players['store_id'] . "\">" . $result_players['store_id'] . "</a></td><td class=\"schedule1\">";
		echo "<a href=\"../store_home.php?store_id=" . $result_players['store_id'] . "\">" . $result_players['store_name'] . "</a></td></tr>";
	}
	$query_players->free_result();
	echo "</table></div>";
} else {
	echo "<p class=\"t16r\">The database doesn't currently contain any players.</p>";
}

include("admin_footer.php");
?>