<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");

if (isset($_POST['submit_results']) && $_POST['submit_results'] == "Submit Results") {
	foreach ($_POST as $name => $value) {
		$$name = $value;
	}
	// just in case there might be errors, save all the $_POST data that was submitted to a $_SESSION array to have it ready to send back to the enter scores page
	foreach ($_POST as $key => $value) {
		${$key} = $value;
		$_SESSION[$key] = $value;
	}
	$errors = 0;
	$error_string = "";
	// loop through the matchups
	for ($m = 1; $m <= $matchup_num; $m++) {
		for ($ah=1; $ah<=2; $ah++) {
			// $ah=1 is the visiting team, $ah=2 is the home team
			for ($p_num = 1; $p_num <= 3; $p_num++) {
				if (${"m{$m}_t{$ah}_p{$p_num}s"} == 0) {
					// the player should have been selected from the list... make sure they were
					if (${"m{$m}_t{$ah}_p{$p_num}"} == "") {
						$_SESSION['m'.$m.'_t'.$ah.'_p'.$p_num] = "ERR";
						$errors++;
					}
				} elseif (${"m{$m}_t{$ah}_p{$p_num}s"} == 1 && ${"m{$m}_t{$ah}_p{$p_num}"} == "") {
					$_SESSION['m'.$m.'_t'.$ah.'_p'.$p_num] = "ERR";
					$errors++;
				} elseif ((${"m{$m}_t{$ah}_p{$p_num}s"} == 1 && ${"m{$m}_t{$ah}_p{$p_num}"} != "") || ${"m{$m}_t{$ah}_p{$p_num}s"} == 2) {
					// the player should have been added or edited... make sure they were
					$aname_length = strlen(${"m{$m}_t{$ah}_p{$p_num}n"});
					if ($aname_length == 0) {
						$_SESSION['m'.$m.'_t'.$ah.'_p'.$p_num.'n'] = "ERR";
						$errors++;
					} elseif ($aname_length == 1) {
						if (!preg_match("/^[a-zA-Z]$/", ${"m{$m}_t{$ah}_p{$p_num}n"})) {
							$_SESSION['m'.$m.'_t'.$ah.'_p'.$p_num.'n'] = "ERR";
							$errors++;
						}
						if (!preg_match("/^[a-zA-Z0-9,'.-]+/", ${"m{$m}_t{$ah}_p{$p_num}n"})) {
							$_SESSION['m'.$m.'_t'.$ah.'_p'.$p_num.'n'] = "ERR";
							$errors++;
						}
					}
				}
				for ($g_num = 1; $g_num <= 10; $g_num++) {
					// make sure each game's score is numeric, divisible by 10 without a remainder, >=0, <=900 and not blank
					if (!is_numeric(${"m{$m}_t{$ah}_p{$p_num}_g{$g_num}"}) || ${"m{$m}_t{$ah}_p{$p_num}_g{$g_num}"} > 900 || ${"m{$m}_t{$ah}_p{$p_num}_g{$g_num}"} < 0 || ${"m{$m}_t{$ah}_p{$p_num}_g{$g_num}"}%10 != 0) {
						$_SESSION['m'.$m.'_t'.$ah.'_p'.$p_num.'_g'.$g_num] = "ERR";
						$errors++;
					}
				}
			}
		}
	}
	// end of form validation... process it if there are no errors or return it to the user if there are errors
	if ($errors == 0) {
		if (isset($_SESSION['num_errors'])) {
			unset($_SESSION['num_errors']);
		}
		// since there are no form errors, update the database with the submitted info
		// loop through the matchups
		for ($m = 1; $m <= $matchup_num; $m++) {
			for ($ah=1; $ah<=2; $ah++) {
				// $ah=1 is the visiting team, $ah=2 is the home team
				$team_id = ${"m{$m}_team_id_{$ah}"};
				for ($p_num = 1; $p_num <= 3; $p_num++) {
					if (${"m{$m}_t{$ah}_p{$p_num}s"} == 0) {
						$cur_player_id = ${"m{$m}_t{$ah}_p{$p_num}"};
					} elseif (${"m{$m}_t{$ah}_p{$p_num}s"} == 1) {
						$cur_player_id = ${"m{$m}_t{$ah}_p{$p_num}"};
						$cur_player_name = $conn1->real_escape_string(${"m{$m}_t{$ah}_p{$p_num}n"});
						// since the player status is set to 1, update the player name in the database, then proceed with uploading results
						$conn1->query("UPDATE players SET full_name='$cur_player_name' WHERE player_id=$cur_player_id");
					} elseif (${"m{$m}_t{$ah}_p{$p_num}s"} == 2) {
						// since the player status is set to 2, check to see if the player already exists before inserting them as a new player
						$cur_player_name = $conn1->real_escape_string(${"m{$m}_t{$ah}_p{$p_num}n"});
						$query_existing_player = $conn1->query("SELECT player_id FROM players WHERE store_id=$store_id && full_name='$cur_player_name' LIMIT 1");
						if ($query_existing_player->num_rows == 1) {
							// since the player to be added already exists, set the cur_player_id to this already existing player_id
							$result_existing_player = $query_existing_player->fetch_assoc();
							$cur_player_id = $result_existing_player['player_id'];
							$query_existing_player->free_result();
						} elseif ($query_existing_player->num_rows == 0) {
							// since the player to be added doesn't already exist, insert them as a new player and set the cur_player_id to this new player_id
							$conn1->query("INSERT INTO players VALUES (null, '$cur_player_name', $store_id)");
							// since this is a new player, set a variable equal to the new player_id
							$cur_player_id = $conn1->insert_id;
						}
					}
					// now that the players table is taken care of and we know the cur_player_id, loop through the games (adding each game to a variable), then insert the results
					for ($g_num = 1; $g_num <= 10; $g_num++) {
						${"g{$g_num}"} = ${"m{$m}_t{$ah}_p{$p_num}_g{$g_num}"};
					}
					$conn1->query("INSERT INTO results VALUES (null, $season_id, $store_id, $division_id, $week_id, $team_id, $p_num, $cur_player_id, $g1, $g2, $g3, $g4, $g5, $g6, $g7, $g8, $g9, $g10) ON DUPLICATE KEY UPDATE player_id=$cur_player_id, g1=$g1, g2=$g2, g3=$g3, g4=$g4, g5=$g5, g6=$g6, g7=$g7, g8=$g8, g9=$g9, g10=$g10");
				}
			}
		}
		// set $_SESSSION variable for the redirect to update standings
		$_SESSION['status'] = "update_standings";
		// if the season_id is less than 6, redirect to the special update_standings(for_seasons_1_through_5).php page
		if ($season_id < 6) {
			header("Location: update_standings(for_seasons_1_through_5).php");
		} else {
			// redirect to update_standings.php to update this store's standings
			header("Location: results5_update_standings.php");
		}
	} elseif ($errors > 0) {
		$_SESSION['status'] = "error";
		$_SESSION['num_errors'] = $errors;
		// since there are errors, redirect back to the enter scores page with the errors highlighted in red
		header("Location: results3_enter_scores.php");
	}
}
?>