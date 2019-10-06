<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_POST['team_id'])) {
	$team_id = (int)$_POST['team_id'];
}
if (isset($_POST['team_name'])) {
	$team_name = $conn1->real_escape_string($_POST['team_name']);
}
if (isset($_POST['store_id'])) {
	$store_id = (int)$_POST['store_id'];
}
if (isset($_POST['tourny_show'])) {
	$tourny_show = (int)$_POST['tourny_show'];
}
if (isset($_POST['real_team'])) {
	$real_team = (int)$_POST['real_team'];
}
if (isset($_GET['sort'])) {
	$get_sort = $conn1->real_escape_string($_GET['sort']);
}
// check to see if a team is being added
if (isset($_POST['add_team']) && $_POST['add_team'] == "Add Team") {
	if (isset($team_name) && $team_name != "") {
		$conn1->query("INSERT INTO teams (team_id, team_name, store_id, tourny_show, real_team) VALUES (null, '$team_name', $store_id, $tourny_show, $real_team)");
		echo "<p class=\"t16\"><b>You've just entered the following team into the database:</b></p>";
		echo "<p class=\"t14\"><b>Team Name:</b> " . $team_name . "<br /><b>Store ID:</b> " . $store_id . "<br /><b>Tourny Show?:</b> " . $tourny_show . "<br /><b>Real Team?:</b> " . $real_team . "</p><br /><br />";
		echo "Return to: <a href=\"add_edit_teams.php\"><b>Add/Edit Teams</b></a> page?<br /><br />";
	} else {
		echo "<p class=\"t16\"><b>No team name was entered.</b></p><br /><br />";
		echo "Return to: <a href=\"add_edit_teams.php\"><b>Add/Edit Teams</b></a> page?<br /><br />";
	}
// check to see if tourny reset is being clicked
} elseif (isset($_POST['reset_tourny_show']) && $_POST['reset_tourny_show'] == "Reset Tourny Show") {
	$conn1->query("UPDATE teams SET tourny_show=1 WHERE real_team=1");
	echo "<p class=\"t16\"><b>Tourny Show status has been set to 1 (coming to the tournament) for all 'Real' teams.</b></p><br /><br />";
	echo "Return to: <a href=\"add_edit_teams.php\"><b>Add/Edit Teams</b></a> page?<br /><br />";
// check to see if a team is being editted
} elseif (isset($_POST['edit_team']) && $_POST['edit_team'] == "Edit") {
	if (isset($team_name) && $team_name != "") {
		$conn1->query("UPDATE teams SET team_name='$team_name', store_id=$store_id, tourny_show=$tourny_show, real_team=$real_team WHERE team_id=$team_id");
		echo "<p class=\"t16\"><b>You've just made the following change in the database:</b></p>";
		echo "<p class=\"t14\">Team ID: " . $team_id . "<br />Team Name: " . $team_name . "<br />Store ID: " . $store_id . "<br />Tourny Show?: " . $tourny_show . "<br />Real Team?: " . $real_team . "</p><br /><br />";
		echo "Return to: <a href=\"add_edit_teams.php\"><b>Add/Edit Teams</b></a> page?<br /><br />";
	} else {
		echo "<p class=\"t16\"><b>No team name was entered.</b></p><br /><br />";
		echo "Return to: <a href=\"add_edit_teams.php\"><b>Add/Edit Teams</b></a> page?<br /><br />";
	}
} elseif (isset($_POST['delete_team']) && $_POST['delete_team'] == "Del") {
	echo "<p class=\"t16r\"><b>Are you sure you want to delete:</b></p>";
	echo "<p class=\"t14\"><b>Team ID:</b> " . $team_id . "<br /><b>Team Name:</b> " . $team_name . "<br /><b>Store ID:</b> " . $store_id . "<br /><b>Tourny Show?:</b> " . $tourny_show . "<br /><b>Real Team?:</b> " . $real_team . "</p>";
	echo "<form action=\"add_edit_teams.php\" method=\"post\">";
	// set the hidden fields
	echo "<input type=\"hidden\" name=\"team_id\" value=\"" . $team_id . "\" />";
	echo "<input type=\"hidden\" name=\"team_name\" value=\"" . $team_name . "\" />";
	echo "<input type=\"hidden\" name=\"store_id\" value=\"" . $store_id . "\" />";
	echo "<input type=\"hidden\" name=\"tourny_show\" value=\"" . $tourny_show . "\" />";
	echo "<input type=\"hidden\" name=\"real_team\" value=\"" . $real_team . "\" />";
	echo "<input type=\"submit\" name=\"delete_team\" value=\"Delete Team\" /></form><br /><br /><br />";
	echo "Return to: <a href=\"add_edit_teams.php\"><b>Add/Edit Teams</b></a> page?<br /><br />";
} elseif (isset($_POST['delete_team']) && $_POST['delete_team'] == "Delete Team" && isset($team_id)) {
	$conn1->query("DELETE FROM teams WHERE team_id=$team_id");
	echo "<p class=\"t16\"><b>The following team has been deleted:</b></p>";
	echo "<p class=\"t14\"><b>Team ID:</b> " . $team_id . "<br /><b>Team Name:</b> " . $team_name . "<br /><b>Store ID:</b> " . $store_id . "<br /><b>Tourny Show?:</b> " . $tourny_show . "<br /><b>Real Team?:</b> " . $real_team . "</p><br /><br />";
	echo "Return to: <a href=\"add_edit_teams.php\"><b>Add/Edit Teams</b></a> page?<br /><br />";
} else {
	// since no submit button has ben clicked, start the page normally
	// find all stores and save them with their store_id and store_city to an array
	$query_stores = $conn1->query("SELECT store_id, store_city FROM stores ORDER BY store_id ASC");
	$store_info = array();
	while ($result_stores = $query_stores->fetch_assoc()) {
		$store_info[] = array("store_id"=>$result_stores['store_id'],"store_city"=>$result_stores['store_city']);
	}
	$query_stores->free_result();
	// start add a team area
	echo "<p class=\"t16\"><b>Add a Team:</b></p>";
	echo "<span class=\"t14\">";
	echo "<table class=\"schedule\"><tr class=\"rowbg\"><td class=\"schedule1\">";
	echo "<form action=\"add_edit_teams.php\" method=\"post\">";
	echo "<b>Team Name</b></td><td class=\"schedule1\">";
	echo "<b>Store</b></td><td class=\"schedule2\">";
	echo "<b>Tourny?</b></td><td class=\"schedule2\">";
	echo "<b>Real?</b></td><td class=\"schedule2\">";
	echo "<b>Task</b>";
	echo "</form>";
	echo "</td></tr>";
	echo "<form action=\"add_edit_teams.php\" method=\"post\">";
		echo "<tr class=\"white\"><td class=\"schedule2\">";
		echo "<input type=\"text\" name=\"team_name\" size=\"40\" value=\"\" /></td><td class=\"schedule1\">";
		echo "<select name=\"store_id\">";
		foreach ($store_info as $s) {
			echo "<option value=\"" . $s['store_id'] . "\">" . $s['store_id'] . " - " . $s['store_city'] . "</option>";
		}
		echo "</select></td>";
		echo "<td class=\"schedule2\">";
		echo "<select name=\"tourny_show\">";
			for ($t=0; $t<=1; $t++) {
				echo "<option value=\"" . $t . "\" ";
				if ($t == 1) {
					echo "selected=\"selected\" ";
				}
				echo "\">";
				if ($t == 0) {
					echo "No";
				} elseif ($t == 1) {
					echo "Yes";
				}
				echo "</option>";
			}
			echo "</select></td><td class=\"schedule2\">";
		echo "<select name=\"real_team\">";
		for ($r=0; $r<=1; $r++) {
			echo "<option value=\"" . $r . "\" ";
			if ($r == 1) {
				echo "selected=\"selected\" ";
			}
			echo "\">";
			if ($r == 0) {
				echo "No";
			} elseif ($r == 1) {
				echo "Yes";
			}
			echo "</option>";
		}
		echo "</select></td><td class=\"schedule2\">";
		echo "<input type=\"submit\" name=\"add_team\" value=\"Add Team\" /></td></tr>";
	echo "</form>";
	echo "</table></span>";
	echo "<hr /><br />";
	// start reset all tourny show status area
	echo "<p class=\"t16\"><b>Reset All Tourny Show Status:</b></p>";
	echo "<table class=\"schedule\"><tr class=\"white\">";
	echo "<td class=\"schedule1\"><span class=\"t14\">This will reset the 'tournament show' status for all 'REAL' teams to 1... meaning: 'yes they are coming to the tournament' (this should be done at the beginning of each new season).</span></td>";
	echo "<td class=\"schedule1\">";
	echo "<form action=\"add_edit_teams.php\" method=\"post\">";
	echo "<input type=\"submit\" name=\"reset_tourny_show\" value=\"Reset Tourny Show\" />";
	echo "</form>";
	echo "</td></tr></table>";
	echo "<hr /><br />";
	// start edit a team area
	echo "<p class=\"t16\"><b>Edit a Team:</b></p>";
	if (isset($get_sort) && $get_sort == "team_name") {
		$query_teams = $conn1->query("SELECT t.team_id, t.team_name, t.tourny_show, t.real_team, s.store_id, s.store_city FROM teams AS t JOIN stores AS s ON (t.store_id=s.store_id) ORDER BY t.team_name ASC, t.store_id ASC");
	} elseif (isset($get_sort) && $get_sort == "store_id") {
		$query_teams = $conn1->query("SELECT t.team_id, t.team_name, t.tourny_show, t.real_team, s.store_id, s.store_city FROM teams AS t JOIN stores AS s ON (t.store_id=s.store_id) ORDER BY t.store_id ASC, t.team_name ASC");
	} elseif (isset($get_sort) && $get_sort == "tourny") {
		$query_teams = $conn1->query("SELECT t.team_id, t.team_name, t.tourny_show, t.real_team, s.store_id, s.store_city FROM teams AS t JOIN stores AS s ON (t.store_id=s.store_id) ORDER BY tourny_show ASC, t.team_name ASC");
	} elseif (isset($get_sort) && $get_sort == "real") {
		$query_teams = $conn1->query("SELECT t.team_id, t.team_name, t.tourny_show, t.real_team, s.store_id, s.store_city FROM teams AS t JOIN stores AS s ON (t.store_id=s.store_id) ORDER BY t.real_team ASC, t.team_id ASC");
	} elseif ((isset($get_sort) && $get_sort == "team_id") || !isset($get_sort)) {
		$query_teams = $conn1->query("SELECT t.team_id, t.team_name, t.tourny_show, t.real_team, s.store_id, s.store_city FROM teams AS t JOIN stores AS s ON (t.store_id=s.store_id) ORDER BY t.team_id ASC");
	}
	if ($query_teams->num_rows > 0) {
		echo "<span class=\"t14\">";
		echo "<table class=\"schedule\"><tr class=\"rowbg\"><td class=\"schedule2\">";
		echo "<a href=\"add_edit_teams.php?sort=team_id\"><b>Team ID</b></a></td><td class=\"schedule1\">";
		echo "<a href=\"add_edit_teams.php?sort=team_name\"><b>Team Name</b></a></td><td class=\"schedule1\">";
		echo "<a href=\"add_edit_teams.php?sort=store_id\"><b>Store</b></a></td><td class=\"schedule2\">";
		echo "<a href=\"add_edit_teams.php?sort=tourny\"><b>Tourny?</b></a></td><td class=\"schedule2\">";
		echo "<a href=\"add_edit_teams.php?sort=real\"><b>Real?</b></a></td><td class=\"schedule2\">";
		echo "<b>Task</b>";
		echo "</td></tr>";
		while ($result_teams = $query_teams->fetch_assoc()) {
			echo "<form action=\"add_edit_teams.php\" method=\"post\">";
			echo "<tr class=\"white\">";
			// set the hidden field for team_id
			echo "<td class=\"schedule2\"><input type=\"hidden\" name=\"team_id\" value=\"" . $result_teams['team_id'] . "\" />" . $result_teams['team_id'] . "</td>";
			echo "<td class=\"schedule1\"><input type=\"text\" name=\"team_name\" size=\"40\" value=\"" . $result_teams['team_name'] . "\" /></td>";
			echo "<td class=\"schedule1\">";
			echo "<select name=\"store_id\">";
				foreach ($store_info as $s) {
					echo "<option value=\"" . $s['store_id'] . "\" ";
					if ($s['store_id'] == $result_teams['store_id']) {
						echo "selected=\"selected\" ";
					}
					echo "\">" . $s['store_id'] . " - " . $s['store_city'] . "</option>";
				}
			echo "</select></td>";
			echo "<td class=\"schedule2\">";
			echo "<select name=\"tourny_show\">";
			for ($t=0; $t<=1; $t++) {
				echo "<option value=\"" . $t . "\" ";
				if ($t == $result_teams['tourny_show']) {
					echo "selected=\"selected\" ";
				}
				echo "\">";
				if ($t == 0) {
					echo "No";
				} elseif ($t == 1) {
					echo "Yes";
				}
				echo "</option>";
			}
			echo "</select></td><td class=\"schedule2\">";
			echo "<select name=\"real_team\">";
			for ($r=0; $r<=1; $r++) {
				echo "<option value=\"" . $r . "\" ";
				if ($r == $result_teams['real_team']) {
					echo "selected=\"selected\" ";
				}
				echo "\">";
				if ($r == 0) {
					echo "No";
				} elseif ($r == 1) {
					echo "Yes";
				}
				echo "</option>";
			}
			echo "</select>";
			echo "</td>";
			echo "<td class=\"schedule2\"><input type=\"submit\" name=\"edit_team\" value=\"Edit\" /> &nbsp; <input type=\"submit\" name=\"delete_team\" value=\"Del\" /></td></tr>";
			echo "</form>";
		}
		$query_teams->free_result();
		echo "</table></span></div>";
	} else {
		echo "<p class=\"t16r\">The database doesn't currently contain any teams.</p>";
	}
}

include("admin_footer.php");
?>