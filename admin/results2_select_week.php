<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_POST['submit_store_division']) && $_POST['submit_store_division'] == "Select this store") {
	if (isset($_SESSION['num_errors'])) {
		unset($_SESSION['num_errors']);
	}
	// a store and division has been selected, so find all weekly matches and list them
	$query_store_matchups = $conn1->query("SELECT DISTINCT s.week_id, DATE_FORMAT(s.week_date, '%M %d, %Y') AS week_date1 FROM schedule AS s WHERE s.season_id={$_POST['season_id']} && s.store_id={$_POST['store_id']} && s.division_id={$_POST['division_id']} ORDER BY s.week_id ASC");
	if ($query_store_matchups->num_rows > 0) {
		echo "<p class=\"t16\"><b>Add / Edit Results for: <span class=\"green\">Winking Lizard " . $_POST['store_city'] . "</span></b> (" . $_POST['day_name'] . ")</p><br />";
		while ($result_store_matchups = $query_store_matchups->fetch_assoc()) {
			echo "<form action=\"results3_enter_scores.php\" method=\"post\">";
			echo "<input type=\"submit\" name=\"submit_week_id\" value=\"Select this week\" /> &nbsp;";
			echo "<input type=\"hidden\" name=\"season_id\" value=\"" . $_POST['season_id'] . "\" />";
			echo "<input type=\"hidden\" name=\"store_id\" value=\"" . $_POST['store_id'] . "\" />";
			echo "<input type=\"hidden\" name=\"division_id\" value=\"" . $_POST['division_id'] . "\" />";
			echo "<input type=\"hidden\" name=\"week_id\" value=\"" . $result_store_matchups['week_id'] . "\" />";
			echo "<span class=\"t14\"><b>Week " . $result_store_matchups['week_id'] . "</b> (" . $result_store_matchups['week_date1'] . ")</span>";
			echo "</form><br /><br />";
		}
		$query_store_matchups->free_result();
	} else {
		// there are no matchups in the schedule for this store
		echo "<p class=\"t16r\">No matchups were found for this store.</p>";
	}
} else {
	echo "No store has been selected.";
}

include ("admin_footer.php");
?>