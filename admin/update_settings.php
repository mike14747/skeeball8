<?php 
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_POST['submit']) && isset($_POST['submit']) == "Submit Settings") {
	if (isset($_POST['reg_button_url'])) {
		$reg_button_url = $conn1->real_escape_string($_POST['reg_button_url']);
	}
	if (isset($_POST['reg_button_text'])) {
		$reg_button_text = $conn1->real_escape_string($_POST['reg_button_text']);
	}
	if (isset($_POST['text_box_heading'])) {
		$text_box_heading = $conn1->real_escape_string($_POST['text_box_heading']);
	}
	if (isset($_POST['text_box_text'])) {
		$text_box_text = $conn1->real_escape_string($_POST['text_box_text']);
	}
	$conn1->query("UPDATE settings SET tourny_rankings_status='{$_POST['tourny_status']}', num_leaders='{$_POST['leaders_show']}', current_season='{$_POST['current_season']}', display_schedule='{$_POST['display_schedule']}', show_reg_button='{$_POST['show_reg_button']}', reg_button_url='$reg_button_url', reg_button_text='$reg_button_text', text_box_heading='$text_box_heading', text_box_text='$text_box_text' WHERE setting_id=1");
	echo "<p class=\"green\"><b>The database has been updated with the new settings.</b></p>";
} else {
	echo "<h2>Update Website Settings</h2>";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
	$query_settings = $conn1->query("SELECT * FROM settings WHERE setting_id=1 LIMIT 1");
	$result_settings = $query_settings->fetch_assoc();
	echo "<p class=\"t16\"><b>Change the status of how the tournament qualifiers page is displayed:</b></p>";
	echo "<p class=\"t12\">The current status of tournament qualifier page is: '<b>";
	if ($result_settings['tourny_rankings_status'] == 0) {
		echo "Don't show the rankings";
	} elseif ($result_settings['tourny_rankings_status'] == 1) {
		echo "Show the rankings as current";
	} elseif ($result_settings['tourny_rankings_status'] == 2) {
		echo "Show the rankings as final";
	}
	echo "'</b></p>";
	echo "<select name=\"tourny_status\">";
		echo "<option value=\"0\"";
		if ($result_settings['tourny_rankings_status'] == 0) {
			echo " selected=\"selected\"";
		}
		echo ">Don't show the rankings</option>";
		echo "<option value=\"1\"";
		if ($result_settings['tourny_rankings_status'] == 1) {
			echo " selected=\"selected\"";
		}
		echo ">Show the rankings as current</option>";
		echo "<option value=\"2\"";
		if ($result_settings['tourny_rankings_status'] == 2) {
			echo " selected=\"selected\"";
		}
		echo ">Show the rankings as final</option>";
	echo "</select>";
	echo "<br /><p class=\"t14\"><b>How this setting works:</b></p>";
	echo "<ul><span class=\"t12\">";
	echo "<li><b>Don't show the rankings</b>- Does not display any of the tournament qualifiers, but instead displays only a 'Check back soon' type of message.</li><br />";
	echo "<li><b>Show the rankings as current</b>- Displays the current qualifiers in order and displays each team's average points per week. It also uses terms like 'Current' and shows a note about why average points are used instead of total points.</li><br />";
	echo "<li><b>Show the rankings as final</b>- Displays the final qualifiers in order and displays each team's total points. It also uses terms like 'Final' and removes the note about why average points are used instead of total points.</li>";
	echo "</span></ul><br />";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Set the number of leaders per category that will be shown on the leaders page:</b></p>";
	echo "<p class=\"t12\">The current number of leaders to be displayed is: '<b>";
	echo $result_settings['num_leaders'];
	echo "</b>'</p>";
	echo "<p><select name=\"leaders_show\">";
		echo "<option value=\"10\"";
		if ($result_settings['num_leaders'] == 10) {
			echo " selected=\"selected\"";
		}
		echo ">10</option>";
		echo "<option value=\"20\"";
		if ($result_settings['num_leaders'] == 20) {
			echo " selected=\"selected\"";
		}
		echo ">20</option>";
		echo "<option value=\"25\"";
		if ($result_settings['num_leaders'] == 25) {
			echo " selected=\"selected\"";
		}
		echo ">25</option>";
		echo "<option value=\"30\"";
		if ($result_settings['num_leaders'] == 30) {
			echo " selected=\"selected\"";
		}
		echo ">30</option>";
		echo "<option value=\"40\"";
		if ($result_settings['num_leaders'] == 40) {
			echo " selected=\"selected\"";
		}
		echo ">40</option>";
		echo "<option value=\"50\"";
		if ($result_settings['num_leaders'] == 50) {
			echo " selected=\"selected\"";
		}
		echo ">50</option>";
	echo "</select></p><br />";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Set the current season:</b></p>";
	echo "<p class=\"t12\">The current season_id is set to: '<b>";
	echo $result_settings['current_season'];
	echo "</b></p>";
	$query_seasons = $conn1->query("SELECT * FROM seasons ORDER BY season_id ASC");
	echo "<p><select name=\"current_season\">";
	while ($result_seasons = $query_seasons->fetch_assoc()) {
		echo "<option value=\"" . $result_seasons['season_id'] . "\"";
		if ($result_seasons['season_id'] == $result_settings['current_season']) {
			echo " selected=\"selected\"";
		}
		echo ">" . $result_seasons['season_name'] . " - " . $result_seasons['year'] . " (season_id " . $result_seasons['season_id'] . ")</option>";
	}
	echo "</select></p><br />";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Show 'Schedule' on the Navigation Bar:</b></p>";
	echo "<p class=\"t14\"><b>Note:</b> this should be set to 'No' after the season ends so people aren't looking at the schedule on the website for the upcoming season before the new season schedule is actually posted.</p>";
	echo "<p class=\"t12\">The current setting is: '<b>";
	if ($result_settings['display_schedule'] == 0) {
		echo "Don't show the schedule on the navigation bar";
	} elseif ($result_settings['display_schedule'] == 1) {
		echo "Show the schedule on the navigation bar";
	}
	echo "'</b></p>";
	echo "<p><select name=\"display_schedule\">";
		echo "<option value=\"0\"";
		if ($result_settings['display_schedule'] == 0) {
			echo " selected=\"selected\"";
		}
		echo ">Don't show the schedule on the navigation bar</option>";
		echo "<option value=\"1\"";
		if ($result_settings['display_schedule'] == 1) {
			echo " selected=\"selected\"";
		}
		echo ">Show the schedule on the navigation bar</option>";
	echo "</select></p><br />";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Show 'Register Now' button on the top of the homepage:</b></p>";
	echo "<p class=\"t14\"><b>Note:</b> this should be set to 'Yes' after the season ends and should stay active until after regisration closes for the upcoming season.</p>";
	echo "<p class=\"t12\">The current setting is: '<b>";
	if ($result_settings['show_reg_button'] == 0) {
		echo "Don't show the schedule on the navigation bar";
	} elseif ($result_settings['show_reg_button'] == 1) {
		echo "Show the schedule on the navigation bar";
	}
	echo "'</b></p>";
	echo "<p><select name=\"show_reg_button\">";
		echo "<option value=\"0\"";
		if ($result_settings['show_reg_button'] == 0) {
			echo " selected=\"selected\"";
		}
		echo ">Don't show the Register Now button</option>";
		echo "<option value=\"1\"";
		if ($result_settings['show_reg_button'] == 1) {
			echo " selected=\"selected\"";
		}
		echo ">Show the Register Now button on the top of the homepage</option>";
	echo "</select></p><br />";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Link URL for the 'Register Now' button on the top of the homepage:</b></p>";
	echo "<p class=\"t12\">The current link URL for the 'Register Now' button is:</p>";
	echo "<p class=\"t16\">";
	echo "<input name=\"reg_button_url\" size=\"60\" value=\"";
	if (isset($reg_button_url)) {
		echo $reg_button_url;
	} else {
		echo $result_settings['reg_button_url'];
	}
	echo "\">";
	echo "</p><br />";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Text that accompanies the 'Register Now' button on the top of the homepage:</b></p>";
	echo "<p class=\"t14\"><b>Note:</b> this text will not appear if the above Register Button setting is set to not show the button.<br /><br />(for a single line break hold Shift, then press Enter)</p>";
	echo "<p class=\"t12\">The current 'Register Now' text is:</p>";
	echo "<p class=\"t16\">";
	echo "<textarea name=\"reg_button_text\" rows=\"10\" cols=\"50\">";
	if (isset($reg_button_text)) {
		echo $reg_button_text;
	} else {
		echo $result_settings['reg_button_text'];
	}
	echo "</textarea>";
	echo "</p>";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Text box heading in the top right of the homepage:</b></p>";
	echo "<p class=\"t12\">The current 'text box heading' is:</p>";
	echo "<p class=\"t16\">";
	echo "<input type=\"text\" name=\"text_box_heading\" size=\"30\" value=\"";
	if (isset($text_box_heading)) {
		echo $text_box_heading;
	} else {
		echo $result_settings['text_box_heading'];
	}
	echo "\">";
	echo "</p>";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo "<p class=\"t16\"><b>Text box text that appears in the body of the text box in the top right of the homepage:</b></p>";
	echo "<p class=\"t14\"><b>Note:</b> for a single line break hold Shift, then press Enter.</p>";
	echo "<p class=\"t12\">The current 'text box text' is:</p>";
	echo "<p class=\"t16\">";
	echo "<textarea name=\"text_box_text\" rows=\"10\" cols=\"50\">";
	if (isset($text_box_text)) {
		echo $text_box_text;
	} else {
		echo $result_settings['text_box_text'];
	}
	echo "</textarea>";
	echo "</p>";
	echo "<hr /><br />";
	// -----------------------------------------------------------------------
	echo " <input type=\"submit\" name=\"submit\" value=\"Submit Settings\"></form>";
}

include("admin_footer.php");
?>