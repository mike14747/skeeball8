<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_GET['season_id']) && $_GET['season_id'] != $cur_season_id) {
	$get_season_id = (int)$_GET['season_id'];
	// make sure the submitted season is valid, otherwise use the current season as the default season
	$query_season = $conn1->query("SELECT season_name, year FROM seasons WHERE season_id=$get_season_id LIMIT 1");
	if ($query_season->num_rows < 1) {
		$result_season = $query_season->fetch_assoc();
		$query_season_id = $cur_season_id;
		$query_season->free_result();
	} elseif ($query_season->num_rows == 1) {
		$query_season_id = $get_season_id;
	}
} else {
	$query_season_id = $cur_season_id;
}
// this is the first page you'll arrive at when entering results, so make a list of stores to pick from
if ($access_level == 1) {
	// the user has logged in to a specific store as a non-admin, so proceed
	echo "<p class=\"t16\"><b>Add / Edit Results:</b></p>";
	echo "<span class=\"green\">Select a store:</span><br /><br />";
	// find all stores that are active in the current season's schedule
	$query_stores_divisions = $conn1->query("SELECT DISTINCT(concat(s.store_id, s.division_id)), st.store_id, st.store_city, d.division_id, d.day_name FROM schedule AS s JOIN stores AS st ON (s.store_id=st.store_id) JOIN divisions AS d ON (s.division_id=d.division_id) WHERE s.season_id=$query_season_id && s.store_id={$_SESSION['store_id']} ORDER BY st.store_city ASC, d.division_id ASC");
	if ($query_stores_divisions->num_rows > 0) {
		while ($result_stores_divisions = $query_stores_divisions->fetch_assoc()) {
			echo "<form action=\"results2_select_week.php\" method=\"post\">";
			echo "<input type=\"submit\" name=\"submit_store_division\" value=\"Select this store\" /> &nbsp;";
			echo "<input type=\"hidden\" name=\"season_id\" value=\"" . $query_season_id . "\" />";
			echo "<input type=\"hidden\" name=\"store_id\" value=\"" . $result_stores_divisions['store_id'] . "\" />";
			echo "<input type=\"hidden\" name=\"store_city\" value=\"" . $result_stores_divisions['store_city'] . "\" />";
			echo "<input type=\"hidden\" name=\"division_id\" value=\"" . $result_stores_divisions['division_id'] . "\" />";
			echo "<input type=\"hidden\" name=\"day_name\" value=\"" . $result_stores_divisions['day_name'] . "\" />";
			echo "<span class=\"t14\">" . $result_stores_divisions['store_city'] . " (" . $result_stores_divisions['day_name'] . ") - " . $result_stores_divisions['store_id'] . "</span>";
			echo "</form><br /><br />";
		}
		$query_stores_divisions->free_result();
	} else {
		echo "There are no matches at this store in the schedule.";
	}
}
elseif ($access_level == 2) {
	// you have been confirmed as an admin user, so proceed
	echo "<p class=\"t16\"><b>Add / Edit Results:</b></p>";
	echo "<span class=\"green\">Select a store:</span><br /><br />";
	// find all stores that are active in the current season's schedule
	$query_stores_divisions = $conn1->query("SELECT DISTINCT(concat(s.store_id, s.division_id)), st.store_id, st.store_city, d.division_id, d.day_name FROM schedule AS s JOIN stores AS st ON (s.store_id=st.store_id) JOIN divisions AS d ON (s.division_id=d.division_id) WHERE s.season_id=$query_season_id ORDER BY st.store_city ASC, d.division_id ASC");
	if ($query_stores_divisions->num_rows > 0) {
		while ($result_stores_divisions = $query_stores_divisions->fetch_assoc()) {
			echo "<form action=\"results2_select_week.php\" method=\"post\">";
			echo "<input type=\"submit\" name=\"submit_store_division\" value=\"Select this store\" /> &nbsp;";
			echo "<input type=\"hidden\" name=\"season_id\" value=\"" . $query_season_id . "\" />";
			echo "<input type=\"hidden\" name=\"store_id\" value=\"" . $result_stores_divisions['store_id'] . "\" />";
			echo "<input type=\"hidden\" name=\"store_city\" value=\"" . $result_stores_divisions['store_city'] . "\" />";
			echo "<input type=\"hidden\" name=\"division_id\" value=\"" . $result_stores_divisions['division_id'] . "\" />";
			echo "<input type=\"hidden\" name=\"day_name\" value=\"" . $result_stores_divisions['day_name'] . "\" />";
			echo "<span class=\"t14\"><b>" . $result_stores_divisions['store_city'] . "</b> (" . $result_stores_divisions['day_name'] . ") - " . $result_stores_divisions['store_id'] . "</span>";
			echo "</form><br /><br />";
		}
		$query_stores_divisions->free_result();
	} else {
		echo "There are no matches at this store in the schedule.";
	}
}

include ("admin_footer.php");
?>