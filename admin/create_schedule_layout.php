<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

$errStr = "";
echo "<h2>CREATE BASIC SCHEDULE LAYOUT</h2>";
echo "<hr /><br />";
// check to see if criteria is being submitted
if (isset($_POST['submit']) && $_POST['submit'] == "Submit Criteria") {
	if (isset($_POST['season_id']) && $_POST['season_id'] == "") {
		$errStr .= "No season was selected.<br />";
	}
	if (isset($_POST['store_id']) && $_POST['store_id'] == "") {
		$errStr .= "No store was selected.<br />";
	}
	if (isset($_POST['division_id']) && $_POST['division_id'] == "") {
		$errStr .= "No night was selected.<br />";
	}
	if (isset($_POST['num_weeks']) && $_POST['num_weeks'] == "") {
		$errStr .= "Number of weeks was not selected.<br />";
	}
	if (isset($_POST['num_teams']) && $_POST['num_teams'] == "") {
		$errStr .= "Number of teams was not selected.<br />";
	}
	if (isset($_POST['num_alleys']) && $_POST['num_alleys'] == "") {
		$errStr .= "Number of alleys was not selected.<br />";
	}
	if (isset($_POST['first_time_slot']) && $_POST['first_time_slot'] == "") {
		$errStr .= "First time slot was not selected.<br />";
	}
	// determine how many time slots are needed
	if ((isset($_POST['num_teams']) && $_POST['num_teams'] != "") && (isset($_POST['num_alleys']) && $_POST['num_alleys'] != "")) {
		$needed = ceil($_POST['num_teams']/$_POST['num_alleys']/2);
		if ($needed > 1) {
			if (isset($_POST['second_time_slot']) && $_POST['second_time_slot'] == "") {
				$errStr .= "Second time slot was not selected, but is needed.<br />";
			}
		}
		if ($needed == 3) {
			if (isset($_POST['third_time_slot']) && $_POST['third_time_slot'] == "") {
				$errStr .= "Third time slot was not selected, but is needed.<br />";
			}
		}
		if ($needed > 3) {
			$errStr .= "Too many teams or not enough alleys were selected to support this configuration.<br />";
		}
	}
	if (!empty($errStr)) {
		echo "<p class=\"t16r\"><b>ERROR!</b><br />" . $errStr . "</p>";
	} elseif (empty($errStr)) {
		//  since the form was submitted without errors, display the results in a table
		// start header row for the table
		echo "<p>schedule_id, season_id, store_id, division_id, week_id, week_date, start_time, alley, away_team_id, home_team_id</p>";
		echo "<table class=\"cst\">";
			// find the date of the first night of the season for this configuration
			$query_season = $conn1->query("SELECT start_date FROM seasons WHERE season_id={$_POST['season_id']} LIMIT 1");
			$result_season = $query_season->fetch_assoc();
			// find out how many rows per week are needed for this configuration
			$rows_needed = $_POST['num_teams'] / 2;
			// for loop to loop through weeks in the season
			for ($w=1; $w<=$_POST['num_weeks']; $w++) {
				$row_counter = 1;
				// for loop to loop through the time slots
				for ($t=1; $t<=$needed; $t++) {
					// for loop to loop through the alleys
					for ($a=1; $a<=$_POST['num_alleys']; $a++) {
						if ($row_counter <= $rows_needed) {
							echo "<tr>";
							echo "<td class=\"cst_td\">NULL</td>";
							echo "<td class=\"cst_td\">" . $_POST['season_id'] . "</td>";
							echo "<td class=\"cst_td\">" . $_POST['store_id'] . "</td>";
							echo "<td class=\"cst_td\">" . $_POST['division_id'] . "</td>";
							echo "<td class=\"cst_td\">" . $w . "</td>";
							// set the date of the current night this store plays on in this week
							$adj_date = date("Y-m-d", strtotime($result_season['start_date'] . " + " . ((7*($w-1))+($_POST['division_id']-1)) . " days"));
							echo "<td class=\"cst_td\">" . $adj_date . "</td>";
							// if there are 8 teams playing on 3 alleys, break up the matches over the 2 time slots so alleys 1 and 3 are used in both time slots
							if (($_POST['num_alleys'] == 3) && ($_POST['num_teams'] == 8) && (($a > 1) || ($t > 1))) {
								if ($t == 1 && $a == 2) {
									echo "<td class=\"cst_td\">" . $_POST['first_time_slot'] . "</td>";
									echo "<td class=\"cst_td\">3</td>"; 
								} elseif ($t == 1 && $a == 3) {
									echo "<td class=\"cst_td\">" . $_POST['second_time_slot'] . "</td>";
									echo "<td class=\"cst_td\">1</td>"; 
								} elseif ($t == 2 && $a == 1) {
									echo "<td class=\"cst_td\">" . $_POST['second_time_slot'] . "</td>";
									echo "<td class=\"cst_td\">3</td>"; 
								}
							} else {
								echo "<td class=\"cst_td\">";
								if ($t == 1) {
									echo $_POST['first_time_slot'];
								} elseif ($t == 2) {
									echo $_POST['second_time_slot'];
								} elseif ($t == 3) {
									echo $_POST['third_time_slot'];
								}
								echo "</td>";
								echo "<td class=\"cst_td\">";
								// if there are 10 or 16 teams playing on 3 alleys, make the final time slot play on alleys 1 and 3 instead of on 1 and 2
								if (($_POST['num_alleys'] == 3 && $_POST['num_teams'] == 10 && $t == 2 && $a == 2) || ($_POST['num_alleys'] == 3 && $_POST['num_teams'] == 16 && $t == 3 && $a == 2)) {
									echo "3";
								} else {
									echo $a;
								}
								echo "</td>";
							}
							echo "<td class=\"cst_td\"></td>";
							echo "<td class=\"cst_td\"></td>";
							echo "</tr>";
							$row_counter++;
						}
					}
				}
			}
			$query_season->free_result();
		echo "</table><br /><br />";
		echo "<hr /><br />";
		echo "<p class=\"t16\">If this doesn't look as intended, edit your criteria below and resubmit your selections.</p>";
	}
}
// start the form for the criteria selection part of the page
echo "<form action=\"create_schedule_layout.php\" method=\"post\">";
	// find seasons for dropdown
	$query_seasons = $conn1->query("SELECT season_id, season_name, year FROM seasons ORDER BY season_id ASC");
	echo "<span class=\"t16\"><b>Season</b>:</span><br />";
	echo "<select name=\"season_id\">";
		echo "<option value=\"\">Select a season</option>";
		while ($result_seasons = $query_seasons->fetch_assoc()) {
			echo "<option value=\"";
			echo $result_seasons['season_id'];
			echo "\"";
			if (isset($_POST['season_id']) && $_POST['season_id'] == $result_seasons['season_id']) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $result_seasons['season_name'] . ", " . $result_seasons['year'];
			echo "</option>";
		}
	echo "</select><br /><br />";
	$query_seasons->free_result();
	// find stores for dropdown
	$query_stores = $conn1->query("SELECT store_id, store_name FROM stores ORDER BY store_name ASC");
	echo "<span class=\"t16\"><b>Store</b>:</span><br />";
	echo "<select name=\"store_id\">";
		echo "<option value=\"\">Select a store</option>";
		while ($result_stores = $query_stores->fetch_assoc()) {
			echo "<option value=\"";
			echo $result_stores['store_id'];
			echo "\"";
			if (isset($_POST['store_id']) && $_POST['store_id'] == $result_stores['store_id']) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $result_stores['store_name'];
			echo "</option>";
		}
	echo "</select><br /><br />";
	$query_stores->free_result();
	// find divisions for dropdown
	$query_divisions = $conn1->query("SELECT division_id, day_name FROM divisions ORDER BY division_id ASC");
	echo "<span class=\"t16\"><b>Night</b> of the week:</span><br />";
	echo "<select name=\"division_id\">";
		echo "<option value=\"\">Select a night</option>";
		while ($result_divisions = $query_divisions->fetch_assoc()) {
			echo "<option value=\"";
			echo $result_divisions['division_id'];
			echo "\"";
			if (isset($_POST['division_id']) && $_POST['division_id'] == $result_divisions['division_id']) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $result_divisions['day_name'];
			echo "</option>";
		}
	echo "</select><br /><br />";
	// dropdown for weeks in the season
	echo "<span class=\"t16\">Number of <b>weeks</b> in the season:</span><br />";
	echo "<select name=\"num_weeks\">";
		echo "<option value=\"\">Select number of weeks</option>";
		echo "<option value=\"7\"";
		if (isset($_POST['num_weeks']) && $_POST['num_weeks'] == 7) {
			echo " selected=\"selected\"";
		}
		echo ">7</option>";
		echo "<option value=\"9\"";
		if (isset($_POST['num_weeks']) && $_POST['num_weeks'] == 9) {
			echo " selected=\"selected\"";
		}
		echo ">9</option>";
	echo "</select><br /><br />";
	// dropdown for number of teams at the store and night
	echo "<span class=\"t16\">Number of <b>teams</b> at the store and night:</span><br />";
	echo "<select name=\"num_teams\">";
		echo "<option value=\"\">Select number of teams</option>";
		for ($t=4; $t<=18; $t+=2) {
			echo "<option value=\"";
			echo $t;
			echo "\"";
			if (isset($_POST['num_teams']) && $_POST['num_teams'] == $t) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $t;
		}
	echo "</select><br /><br />";
	// dropdown for number of alleys at the store
	echo "<span class=\"t16\">Number of <b>alleys</b> at the store:</span><br />";
	echo "<select name=\"num_alleys\">";
		echo "<option value=\"\">Select number of alleys</option>";
		for ($a=1; $a<=3; $a++) {
			echo "<option value=\"";
			echo $a;
			echo "\"";
			if (isset($_POST['num_alleys']) && $_POST['num_alleys'] == $a) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $a;
		}
	echo "</select><br /><br />";
	// create the array for the possible time slots
	$time_slots = array("6:00 PM","6:15 PM","6:30 PM","6:45 PM","7:00 PM","7:15 PM","7:30 PM","7:45 PM","8:00 PM","8:15 PM","8:30 PM","8:45 PM","9:00 PM","9:15 PM","9:30 PM");
	// dropdown for first time slot
	echo "<span class=\"t16\"><b>First</b> time slot:</span><br />";
	echo "<select name=\"first_time_slot\">";
		echo "<option value=\"\">Select first time slot</option>";
		foreach ($time_slots as $ts) {
			echo "<option value=\"";
			echo $ts;
			echo "\"";
			if (isset($_POST['first_time_slot']) && $_POST['first_time_slot'] == $ts) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $ts;
			echo "</option>";
		}
	echo "</select><br /><br />";
	// dropdown for second time slot
	echo "<span class=\"t16\"><b>Second</b> time slot (if applicable):</span><br />";
	echo "<select name=\"second_time_slot\">";
		echo "<option value=\"\">Select second time slot</option>";
		foreach ($time_slots as $ts) {
			echo "<option value=\"";
			echo $ts;
			echo "\"";
			if (isset($_POST['second_time_slot']) && $_POST['second_time_slot'] == $ts) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $ts;
			echo "</option>";
		}
	echo "</select><br /><br />";
	// dropdown for third time slot
	echo "<span class=\"t16\"><b>Third</b> time slot (if applicable):</span><br />";
	echo "<select name=\"third_time_slot\">";
		echo "<option value=\"\">Select third time slot</option>";
		foreach ($time_slots as $ts) {
			echo "<option value=\"";
			echo $ts;
			echo "\"";
			if (isset($_POST['third_time_slot']) && $_POST['third_time_slot'] == $ts) {
				echo " selected=\"selected\"";
			}
			echo ">";
			echo $ts;
			echo "</option>";
		}
	echo "</select><br /><br />";
	echo "<br /><input type=\"submit\" name=\"submit\" value=\"Submit Criteria\" />";
echo "</form>";

include("admin_footer.php");
?>