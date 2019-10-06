<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");
require_once("functions.php");

echo "<p class=\"t16\"><b>Add / Edit Results:</b></p>";
if ( (isset($_POST['submit_week_id']) && ($_POST['submit_week_id'] == "Select this week" || $_POST['submit_week_id'] == "Resubmit the same store and week")) || (isset($_SESSION['status']) && $_SESSION['status'] == "error") ) {
	if (isset($_POST['submit_week_id'])) {
		// this page was accessed for the first time after selecting the season, store, division and week from the previous page, otherwise these things will be set in the next command through the $_SESSION array
		$season_id = $_POST['season_id'];
		$store_id = $_POST['store_id'];
		$division_id = $_POST['division_id'];
		$week_id = $_POST['week_id'];
	} elseif (isset($_SESSION['status'])) {
		// if $_SESSION['status']=="error", set all $_SESSION elements to variables of the same name so they can fill the form with the previously submitted data
		foreach ($_SESSION as $name => $value) {
			$$name = $value;
		}
	}
	// find the store and division names based on the store_id
	$query_store = $conn1->query("SELECT s.store_id, s.store_name, d.division_id, d.day_name, DATE_FORMAT(sch.week_date, '%b-%d, %Y') AS week_date1, se.season_name, se.year FROM stores AS s, divisions AS d, schedule AS sch JOIN seasons AS se ON (sch.season_id=se.season_id) WHERE sch.season_id=$season_id && sch.week_id=$week_id && s.store_id=$store_id && d.division_id=$division_id LIMIT 1");
	if ($query_store->num_rows == 1) {
		// since store_id and division_id are set, show the store link for this store
		$result_store = $query_store->fetch_assoc();
		$query_store->free_result();
		echo "<div class=\"centered\"><b>" . $result_store['store_name'] . "</b> (" . $result_store['day_name'] . ")";
		echo " &nbsp;| &nbsp; Season: " . $result_store['season_name'] . ", " . $result_store['year'] . " &nbsp;| &nbsp; <span class=\"green\"><b>Week " . $week_id . "</b> (" . $result_store['week_date1'] . ")</span>";
		echo "</div>";
		echo "<hr /><br />";
		if (isset($_SESSION['num_errors'])) {
			echo "<p><span class=\"red\">The information you've submitted has <b>" . $_SESSION['num_errors'] . " ERROR(s)</b> and the data was <b>NOT</b> added to the database!</span></p>";
			echo "<p>The fields with errors are displayed below in <span class=\"red\"><b>RED</b></span>. Please correct the errors and resubmit the form.</p>";
			echo "<p>Some tips for avoiding errors are:<ul>";
			echo "<li>Scores have to be a number from 0 to 900 that's divisible by 10... ie: 131 is not a valid entry.</li>";
			echo "<li>If you've chosen to edit an existing or add a new player name, they have to begin with a letter and can contain letters, numbers, spaces, dashes, commas or periods after that, but no other characters are allowed.</li>";
			echo "</ul></p><br />";
		}
		$query_matchups = $conn1->query("SELECT s.week_id, s.away_team_id AS team_id_1, s.home_team_id AS team_id_2, s.alley, s.start_time, MAX(CASE WHEN t.team_id=s.away_team_id THEN t.team_name ELSE NULL END) AS t1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.player_id ELSE NULL END) AS 1_p1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g1 ELSE NULL END) AS 1_p1_g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g2 ELSE NULL END) AS 1_p1_g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g3 ELSE NULL END) AS 1_p1_g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g4 ELSE NULL END) AS 1_p1_g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g5 ELSE NULL END) AS 1_p1_g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g6 ELSE NULL END) AS 1_p1_g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g7 ELSE NULL END) AS 1_p1_g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g8 ELSE NULL END) AS 1_p1_g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g9 ELSE NULL END) AS 1_p1_g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=1 THEN r.g10 ELSE NULL END) AS 1_p1_g10, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.player_id ELSE NULL END) AS 1_p2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g1 ELSE NULL END) AS 1_p2_g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g2 ELSE NULL END) AS 1_p2_g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g3 ELSE NULL END) AS 1_p2_g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g4 ELSE NULL END) AS 1_p2_g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g5 ELSE NULL END) AS 1_p2_g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g6 ELSE NULL END) AS 1_p2_g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g7 ELSE NULL END) AS 1_p2_g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g8 ELSE NULL END) AS 1_p2_g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g9 ELSE NULL END) AS 1_p2_g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=2 THEN r.g10 ELSE NULL END) AS 1_p2_g10, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.player_id ELSE NULL END) AS 1_p3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g1 ELSE NULL END) AS 1_p3_g1, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g2 ELSE NULL END) AS 1_p3_g2, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g3 ELSE NULL END) AS 1_p3_g3, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g4 ELSE NULL END) AS 1_p3_g4, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g5 ELSE NULL END) AS 1_p3_g5, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g6 ELSE NULL END) AS 1_p3_g6, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g7 ELSE NULL END) AS 1_p3_g7, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g8 ELSE NULL END) AS 1_p3_g8, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g9 ELSE NULL END) AS 1_p3_g9, MAX(CASE WHEN r.team_id=s.away_team_id && r.player_num=3 THEN r.g10 ELSE NULL END) AS 1_p3_g10, MAX(CASE WHEN t.team_id=s.home_team_id THEN t.team_name ELSE NULL END) AS t2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.player_id ELSE NULL END) AS 2_p1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g1 ELSE NULL END) AS 2_p1_g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g2 ELSE NULL END) AS 2_p1_g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g3 ELSE NULL END) AS 2_p1_g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g4 ELSE NULL END) AS 2_p1_g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g5 ELSE NULL END) AS 2_p1_g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g6 ELSE NULL END) AS 2_p1_g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g7 ELSE NULL END) AS 2_p1_g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g8 ELSE NULL END) AS 2_p1_g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g9 ELSE NULL END) AS 2_p1_g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=1 THEN r.g10 ELSE NULL END) AS 2_p1_g10, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.player_id ELSE NULL END) AS 2_p2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g1 ELSE NULL END) AS 2_p2_g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g2 ELSE NULL END) AS 2_p2_g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g3 ELSE NULL END) AS 2_p2_g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g4 ELSE NULL END) AS 2_p2_g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g5 ELSE NULL END) AS 2_p2_g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g6 ELSE NULL END) AS 2_p2_g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g7 ELSE NULL END) AS 2_p2_g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g8 ELSE NULL END) AS 2_p2_g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g9 ELSE NULL END) AS 2_p2_g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=2 THEN r.g10 ELSE NULL END) AS 2_p2_g10, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.player_id ELSE NULL END) AS 2_p3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g1 ELSE NULL END) AS 2_p3_g1, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g2 ELSE NULL END) AS 2_p3_g2, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g3 ELSE NULL END) AS 2_p3_g3, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g4 ELSE NULL END) AS 2_p3_g4, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g5 ELSE NULL END) AS 2_p3_g5, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g6 ELSE NULL END) AS 2_p3_g6, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g7 ELSE NULL END) AS 2_p3_g7, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g8 ELSE NULL END) AS 2_p3_g8, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g9 ELSE NULL END) AS 2_p3_g9, MAX(CASE WHEN r.team_id=s.home_team_id && r.player_num=3 THEN r.g10 ELSE NULL END) AS 2_p3_g10 
FROM schedule AS s LEFT JOIN results AS r ON (s.season_id=r.season_id AND s.store_id=r.store_id AND s.division_id=r.division_id AND s.week_id=r.week_id AND (r.team_id=s.away_team_id OR r.team_id=s.home_team_id)) LEFT JOIN players AS p ON (r.player_id=p.player_id) JOIN teams AS t ON (s.away_team_id=t.team_id OR s.home_team_id=t.team_id) WHERE s.season_id=$season_id && s.store_id=$store_id && s.division_id=$division_id && s.week_id=$week_id GROUP BY s.start_time, s.alley ORDER BY s.start_time ASC, s.alley ASC, r.team_id ASC, r.player_num ASC");
		$query_players = $conn1->query("SELECT p.player_id, p.full_name, r.team_id FROM players AS p LEFT JOIN results AS r ON (p.player_id=r.player_id) WHERE p.store_id=$store_id || p.store_id=99 GROUP BY r.team_id, p.player_id ORDER BY p.full_name ASC");
		$player_list = array();
		while ($result_players = $query_players->fetch_assoc()) {
			$player_list[] = array("player_id"=>$result_players['player_id'],"full_name"=>$result_players['full_name'],"team_id"=>$result_players['team_id']);
		}
		$query_players->free_result();
		if ($query_matchups->num_rows > 0) {
			// since there are matchups to enter results for and maybe even results already in the database for this store, division and week, start the form
			echo "<form action=\"results4_validate_input.php\" method=\"post\">";
			echo "<input type=\"hidden\" name=\"season_id\" value=\"" . $season_id . "\" />";
			echo "<input type=\"hidden\" name=\"store_id\" value=\"" . $store_id . "\" />";
			echo "<input type=\"hidden\" name=\"division_id\" value=\"" . $division_id . "\" />";
			echo "<input type=\"hidden\" name=\"week_id\" value=\"" . $week_id . "\" />";
			// set matchup number counter
			$matchup_num = 0;
			while ($result_matchups = $query_matchups->fetch_assoc()) {
				$matchup_num++;
				// start table for current matchup
				echo "<span class=\"green\"><b>Matchup #" . $matchup_num . "</b></span><br />";
				echo "<div class=\"centered\">";
				echo "<table class=\"schedule3a\">";
				for ($ah=1; $ah<=2; $ah++) {
					// $ah=1 is the visiting team, $ah=2 is the home team
					echo "<tr class=\"rowbg\"><td class=\"schedule1a\"><span class=\"t12\">";
					if ($ah == 1) {
						echo "away team: ";
					} elseif ($ah == 2) {
						echo "home team: ";
					}
					echo "</span><span class=\"t14\"><b>" . $result_matchups['t'.$ah] . "</b></span></td>";
					for ($g = 1; $g <= 10; $g++) {
						echo "<td class=\"schedule2\"><b>" . $g . "</b></td>";
					}
					echo "</tr>";
					echo "<input type=\"hidden\" name=\"m" . $matchup_num . "_team_id_" .  $ah. "\" value=\"" . $result_matchups['team_id_'.$ah] . "\" />";
					for ($p_num = 1; $p_num <= 3; $p_num++) {
						// call function for getting unique player_ids in the player dropdown list
						list($ct_players, $player_list1) = unique_player_list($player_list, $result_matchups['team_id_'.$ah]);
						echo "<tr class=\"white\"><td class=\"schedule1\">";
						echo "<div class=\"bot_margin\">";
						if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}"} == "ERR") {
							echo "<span class=\"t10error\">";
						} else {
							echo "<span class=\"t10\">";
						}
						echo "<b>PLAYER " . $p_num . "</b> </span>";
						echo "<select name=\"m" . $matchup_num . "_t" . $ah . "_p" . $p_num . "\">";
							echo "<option value=\"\">Select a Player</option>";
							// show the players that have played for the current team
							foreach ($ct_players as $ct) {
								// echo "<option value=\"" . $ct['player_id'] . "\">" . $ct['full_name'] . "</option>";
								echo "<option value=\"" . $ct['player_id'] .  "\"";
								if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}"} == $ct['player_id']) {
									echo " selected=\"selected\"";
								} elseif ($result_matchups[$ah.'_p'.$p_num] == $ct['player_id']) {
									echo " selected=\"selected\"";
								}
								echo " >" . $ct['full_name'] . "</option>";
							}
							echo "<option value=\"\">---------------</option>";
							foreach ($player_list1 as $fl) {
								echo "<option value=\"" . $fl['player_id'] .  "\"";
								if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}"} == $fl['player_id']) {
									echo " selected=\"selected\"";
								}
								echo " >" . $fl['full_name'] . "</option>";
							}
						echo "</select></div>";
						// the value of status will be 0 since the radio button is set to use the selected player
						echo "<input type=\"radio\" name=\"m" . $matchup_num . "_t" . $ah . "_p" . $p_num . "s\" value=\"0\"";
						if ((isset(${"m{$matchup_num}_t{$ah}_p{$p_num}s"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}s"} == 0) || isset($_POST['submit_week_id'])) {
							echo " checked=\"checked\"";
						}
						echo " /><span class=\"t10\">Use the above selected player</span><br />";
						// the value of status will be 1 since the radio button is set to edit the selected player
						echo "<input type=\"radio\" name=\"m" . $matchup_num . "_t" . $ah . "_p" . $p_num . "s\" value=\"1\"";
						if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}s"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}s"} == 1) {
							echo " checked=\"checked\"";
						}
						echo " /><span class=\"t10\">Edit selected name &nbsp;&nbsp;</span>";
						// the value of status will be 2 since the radio button is set to add a new player
						echo "<input type=\"radio\" name=\"m" . $matchup_num . "_t" . $ah . "_p" . $p_num . "s\" value=\"2\"";
						if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}s"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}s"} == 2) {
							echo " checked=\"checked\"";
						}
						echo " /><span class=\"t10\">Add new name</span>";
						echo "<div class=\"top_margin\">";
						if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}n"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}n"} == "ERR") {
							echo "<span class=\"t10error\">";
						} else {
							echo "<span class=\"t10\">";
						}
						echo "New/Edited Name: </span>";
						echo "<input type=\"text\" name=\"m" . $matchup_num . "_t" . $ah . "_p" . $p_num . "n\" maxlength=\"40\" value=\"";
						if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}s"}) && (${"m{$matchup_num}_t{$ah}_p{$p_num}s"} == 1 || ${"m{$matchup_num}_t{$ah}_p{$p_num}s"} == 2) && isset(${"m{$matchup_num}_t{$ah}_p{$p_num}n"})) {
							if (isset(${"m{$matchup_num}_t{$ah}_p{$p_num}n"}) && ${"m{$matchup_num}_t{$ah}_p{$p_num}n"} == "ERR") {
								echo "";
							} else {
								echo ${"m{$matchup_num}_t{$ah}_p{$p_num}n"};
							}
						}
						echo "\" /></div></td>";
						for ($g_num = 1; $g_num <= 10; $g_num++) {
							echo "<td class=\"schedule2a\"><input type=\"text\" name=\"m" . $matchup_num . "_t" . $ah . "_p" . $p_num . "_g" . $g_num . "\" maxlength=\"3\" value=\"";
							if (isset($_SESSION['status']) && isset(${"m{$matchup_num}_t{$ah}_p{$p_num}_g{$g_num}"})) {
								if (${"m{$matchup_num}_t{$ah}_p{$p_num}_g{$g_num}"} == "ERR") {
									echo ${"m{$matchup_num}_t{$ah}_p{$p_num}_g{$g_num}"} . "\" class=\"error\" />";
								} else {
									echo ${"m{$matchup_num}_t{$ah}_p{$p_num}_g{$g_num}"} . "\" class=\"admin1\" />";
								}
							} else {
								echo $result_matchups[$ah.'_p'.$p_num.'_g'.$g_num] . "\" class=\"admin1\" />";
							}
							echo "</td>";
						}
						echo "</tr>";
					}
					if ($ah == 1) {
						echo "<tr><td height=\"10px\"></td></tr>";
					}
				}
				echo "</table></div>";
				echo "<hr /><br />";
			}
			echo "<input type=\"hidden\" name=\"matchup_num\" value=\"" . $matchup_num . "\" />";
			echo "<div class=\"centered\"><input type=\"submit\" name=\"submit_results\" value=\"Submit Results\" /></div>";
			echo "</form><br /><br />";
			$query_matchups->free_result();
		} else {
			echo "<div class=\"centered\"><p class=\"t16r\"><b>There are no results for the selected season.</b></p></div>";
		}	
	} else {
		echo "<hr /><br />";
		echo "<div class=\"centered\"><p class=\"t16r\"><b>The selected store is not valid.</b></p></div>";
	}
} else {
	echo "<hr /><br />";
	echo "<div class=\"centered\"><p class=\"t16r\"><b>No store has been selected.</b></p></div>";
	echo "</form><br /><br />";
}

include ("admin_footer.php");
?>