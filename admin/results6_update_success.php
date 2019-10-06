<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_GET['status']) && isset($_GET['season_id']) && isset($_GET['store_id']) && isset($_GET['division_id']) && isset($_GET['week_id'])) {
	$get_status = $conn1->real_escape_string($_GET['status']);
	$get_season_id = (int)$_GET['season_id'];
	$get_store_id = (int)$_GET['store_id'];
	$get_division_id = (int)$_GET['division_id'];
	$get_week_id = (int)$_GET['week_id'];
	if ($get_status == "done") {
		$query_store_week = $conn1->query("SELECT DATE_FORMAT(sch.week_date, '%M %d, %Y') AS week_date1, s.store_name, d.day_name, sch.alley, sch.start_time, sch.away_team_id, (SELECT team_name FROM teams WHERE team_id=sch.away_team_id) AS t1, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g1 ELSE NULL END) AS 1_g1, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g2 ELSE NULL END) AS 1_g2, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g3 ELSE NULL END) AS 1_g3, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g4 ELSE NULL END) AS 1_g4, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g5 ELSE NULL END) AS 1_g5, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g6 ELSE NULL END) AS 1_g6, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g7 ELSE NULL END) AS 1_g7, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g8 ELSE NULL END) AS 1_g8, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g9 ELSE NULL END) AS 1_g9, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g10 ELSE NULL END) AS 1_g10, SUM(CASE WHEN r.team_id=sch.away_team_id THEN r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10 ELSE NULL END) AS 1_tot, sch.home_team_id, (SELECT team_name FROM teams WHERE team_id=sch.home_team_id) AS t2, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g1 ELSE NULL END) AS 2_g1, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g2 ELSE NULL END) AS 2_g2, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g3 ELSE NULL END) AS 2_g3, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g4 ELSE NULL END) AS 2_g4, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g5 ELSE NULL END) AS 2_g5, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g6 ELSE NULL END) AS 2_g6, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g7 ELSE NULL END) AS 2_g7, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g8 ELSE NULL END) AS 2_g8, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g9 ELSE NULL END) AS 2_g9, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g10 ELSE NULL END) AS 2_g10, SUM(CASE WHEN r.team_id=sch.home_team_id THEN r.g1+r.g2+r.g3+r.g4+r.g5+r.g6+r.g7+r.g8+r.g9+r.g10 ELSE NULL END) AS 2_tot FROM schedule AS sch JOIN results AS r ON (sch.season_id=r.season_id AND sch.store_id=r.store_id AND sch.division_id=r.division_id AND sch.week_id=r.week_id AND (sch.away_team_id=r.team_id OR sch.home_team_id=r.team_id)) JOIN stores AS s ON (sch.store_id=s.store_id) JOIN divisions AS d ON (sch.division_id=d.division_id) WHERE sch.season_id=$get_season_id && sch.store_id=$get_store_id && sch.division_id=$get_division_id && sch.week_id=$get_week_id GROUP BY sch.start_time, sch.alley ORDER BY sch.start_time ASC, sch.alley ASC");
		if ($query_store_week->num_rows > 0) {
			echo "<p class=\"t16\"><span class=\"green\"><b>SUCCESS!</b></span></p>";
			echo "<p class=\"t16\">The new/edited results have successfully been uploaded to the database for:</p>";
			// since results were submitted, start mail formatting and email the results to me
			$from = "admin@skeeballworldtour.com";
			$to = "mike@automaticmusic.com";
			$subject = "Skeeball scores have been submitted!";
			// $message = "<html><body><div style=\"font-family: arial, helvetica, sans-serif; font-size: 14px;\">";
			$message = "<html><body>";
			$counter = 1;
			while ($result_store_week = $query_store_week->fetch_assoc()) {
				if ($counter == 1) {
					// add to email and display store name and week number info
					$message .= "<p><b>" . $result_store_week['store_name'] . " (" . $result_store_week['day_name'] . ")</b><br />";
					$message .= "<b>Week: " . $get_week_id . " (" . $result_store_week['week_date1'] . ")</b></p>";
					echo "<p class=\"t16\"><b>" . $result_store_week['store_name'] . " (" . $result_store_week['day_name'] . ")</b><br /><span class=\"t14\"><b>Week: " . $get_week_id . " (" . $result_store_week['week_date1'] . ")</b></span></p>";
				}
				$counter++;
				// initialize all variables for home and away teams since these are new matchups we're looping through
				$a_wins = 0;
				$a_losses = 0;
				$a_ties = 0;
				$a_tot_pts = $result_store_week['1_tot'];
				$h_wins = 0;
				$h_losses = 0;
				$h_ties = 0;
				$h_tot_pts = $result_store_week['2_tot'];
				for ($g=1; $g<=10; $g++) {
					if ($result_store_week['1_g'.$g] > $result_store_week['2_g'.$g]) {
						$a_wins++;
						$h_losses++;
					} elseif ($result_store_week['2_g'.$g] > $result_store_week['1_g'.$g]) {
						$h_wins++;
						$a_losses++;
					} elseif ($result_store_week['1_g'.$g] == $result_store_week['2_g'.$g]) {
						$a_ties++;
						$h_ties++;
					}
				}
				// add the data to the email for this matchup
				$message .= $result_store_week['t1'] . ": " . $a_wins . "-" . $a_losses . "-" . $a_ties . " (" . $a_tot_pts . ")" . "<br />";
				echo $result_store_week['t1'] . ": " . $a_wins . "-" . $a_losses . "-" . $a_ties . " (" . $a_tot_pts . ")" . "<br />";
				$message .= $result_store_week['t2'] . ": " . $h_wins . "-" . $h_losses . "-" . $h_ties . " (" . $h_tot_pts . ")" . "<br />";
				echo $result_store_week['t2'] . ": " . $h_wins . "-" . $h_losses . "-" . $h_ties . " (" . $h_tot_pts . ")" . "<br />";
				$message .= "<br />";
				echo "<br />";
			}
			$query_store_week->free_result();
			$message .= "</body></html>";
			// $message .= "</div></body></html>";
			$message = wordwrap($message, 70);
			$mailheaders = "MIME-Version: 1.0\r\n";
			$mailheaders .= "Content-type: text/html; charset=ISO-8859-1\r\n";
			$mailheaders .= "From: $from\r\n";
			// Start sending email
			$sent = mail($to, $subject, $message, $mailheaders);
			// Display successful submission confirmation message if it was sent
			if ($sent) {
				echo "<hr />";
				echo "<p class=\"t16\">An email containing all the submitted data has been sent to SkeeballWorldTour admin.</p><br />";
			}
			if (isset($_SESSION['swt_username']) && isset($_SESSION['access_level']) && $_SESSION['access_level'] == 1) {
				$_SESSION = array();
				session_unset();
				session_destroy();
				echo "<p class=\"red\"></p>You have now been logged out. <a href=\"http://www.skeeballworldtour.com/admin/\">Click here</a> to log back in.";
			} elseif (isset($_SESSION['swt_username']) && isset($_SESSION['access_level']) && $_SESSION['access_level'] == 2) {
				echo "<form action=\"results3_enter_scores.php\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"season_id\" value=\"" . $get_season_id . "\" />";
				echo "<input type=\"hidden\" name=\"store_id\" value=\"" . $get_store_id . "\" />";
				echo "<input type=\"hidden\" name=\"division_id\" value=\"" . $get_division_id . "\" />";
				echo "<input type=\"hidden\" name=\"week_id\" value=\"" . $get_week_id . "\" />";
				echo "If you'd like to edit the results you've just uploaded, click: ";
				echo "<input type=\"submit\" name=\"submit_week_id\" value=\"Resubmit the same store and week\" />";
				echo "</form>";
				echo "<br />or<br /><br />";
				echo "<form action=\"results1_select_store.php\" method=\"post\">";
				echo "If you'd like to enter results for a different store, click: ";
				echo "<input type=\"submit\" name=\"submit\" value=\"Enter results for a different store\" />";
				echo "</form>";
			}
		} else {
			echo "<p class=\"red\"><b>Error...</b> The transmitted season, store, division and week do not appear in the schedule.</p>";
		}
	} else {
		echo "<p class=\"red\"><b>Error...</b> Status does not indicate that the standings were successfully updated.</p>";
	}
}

include ("admin_footer.php");
?>