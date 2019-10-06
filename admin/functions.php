<?php 
function mysql_prep($value) {
	$magic_quotes_active = get_magic_quotes_gpc();
	$new_enough_php = function_exists("mysql_real_escape_string");
	// check to see if the version of php is at least v4.3.0
	if ($new_enough_php) {
		// since php version is at least v4.3.0
		if ($magic_quotes_active) {
			// since magic quotes are active
			$value = stripslashes($value);
		}
		$value = mysql_real_escape_string($value);
	} else {
		// since php version is older than v4.3.0 or if magic quotes are turned off, add the slashes manually
		if (!$magic_quotes_active) {
			$value = addslashes($value);
		}
	}
	return $value;
}
// used on results3_enter_scores.php to break up players at a store into players that have ever played for a specific team
function unique_player_list($full_player_list, $team_id) {
	$cur_team_players = array();
	$ct_player_list = array();
	$key_array = array();
	$i = 0;
	$other_unique_players = array();
	foreach ($full_player_list as $cur) {
		if ($cur['team_id'] == $team_id) {
			$cur_team_players[] = array("player_id"=>$cur['player_id'],"full_name"=>$cur['full_name']);
			$ct_player_list[] = $cur['player_id'];
		}
	}
	foreach ($full_player_list as $rest) {
		if (!in_array($rest['player_id'], $key_array) && !in_array($rest['player_id'], $ct_player_list)) {
			$key_array[$i] = $rest['player_id'];
			$other_unique_players[$i] = array("player_id"=>$rest['player_id'],"full_name"=>$rest['full_name']);
		}
		$i++;
	}
	return array($cur_team_players, $other_unique_players);
}
?>