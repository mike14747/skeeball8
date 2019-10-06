<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include("admin_header.php");

echo "<h2>Add New Homepage News Item</h2>";
echo "<hr /><br />";
if (isset($_POST['newsheader'])) {
	$newsheader = $conn1->real_escape_string($_POST['newsheader']);
}
if (isset($_POST['newsdate'])) {
	$newsdate = $conn1->real_escape_string($_POST['newsdate']);
}
if (isset($_POST['newstext'])) {
	$newstext = $conn1->real_escape_string($_POST['newstext']);
}
if (isset($_POST['display_content'])) {
	$display_content = (int)$_POST['display_content'];
}	
if (isset($_POST['submit']) && $_POST['submit'] == "Submit News") {
	// A new news item has been submitted
	// set error variables for blank testing and format testing
	// set the total errors variable
	$t_errors = 0;
	// set the blank field error variable
	$b_errors = 0;
	// set the date formatting error variable
	$f_errors = 0;
	// If the submit button has been clicked, check to see if any of the fields are blank
	if (($newsheader == "") || ($newsdate == "") || ($newstext == "")) {
		$b_errors++;
		$t_errors++;
	}
	// If the submit button has been clicked, check to see if the date field is formatted correctly
	if (($newsdate != "") && (!preg_match("/^20[0-9]{2}-[0-1][0-9]-[0-3][0-9]$/", $newsdate))) {
		$f_errors++;
		$t_errors++;
	}
	if ($t_errors == 0) {
		// If no $_POST items are blank or not formatted properly proceed with form processing
		// Insert new news item into the database since there is no existing news_id
		$conn1->query("INSERT INTO store_text (store_id, content_heading, text_date, page_content, display_content) VALUES (10, '$newsheader', '$newsdate', '$newstext', $display_content)");
		// Display confirmation message that the info has been added to the database
		echo "<p class=\"t16\"><span class=\"blue\"><b>A news item has been added with the following:</b></span></p><br />";
		echo "<h4>" . $newsheader . "</h4>";
		echo "<span class=\"t12\">Posted on: " . $newsdate . "</span>";
		echo "<span class=\"t14\">" . stripslashes($_POST['newstext']) . "</span><br />";
		echo "<span class=\"t14\"><b>Display thie item:</b> ";
		if ($display_content == 0) {
			echo "No";
		} elseif ($display_content == 1) {
			echo "Yes";
		}
		echo "</span>";
	}
}
if ((isset($t_errors) && $t_errors > 0) || !isset($_POST['submit'])) {
	if (isset($t_errors) && $t_errors > 0) {
		echo "<p class=\"t16r\"><b>FAILED!</b></p>";
		echo "<p class=\"t16r\">Correct these items and resubmit the form:</p>";
		if (isset($b_errors) && $b_errors > 0) {
			echo "<p class=\"t16r\"><b>--</b> One or more or the fields have been left <b>blank</b>. <b>--</b></p>";
		}
		if (isset($f_errors) && $f_errors > 0) {
			echo "<p class=\"t16r\"><b>--</b> The <b>date field</b> is not in the proper format. <b>--</b></p>";
		}
	}
	// since submit button has not been clicked or there are errors, display the form
	echo "<form action=\"add_homepage_news.php\" method=\"post\">";
		echo "<p class=\"t16\"><b>News Item Header</b>: &nbsp;<input type=\"text\" name=\"newsheader\" size=\"50\" maxlength=\"100\"";
		if (isset($newsheader)) {
			echo " value=\"" . $newsdate . "\"";
		}
		echo " /></p>";
		echo "<p class=\"t16\"><b>News Item Date</b> (in this format: YYYY-MM-DD): &nbsp;<input type=\"text\" name=\"newsdate\" id=\"datepicker\" size=\"15\" maxlength=\"10\"";
		if (isset($newsdate)) {
			echo " value=\"" . $newsdate . "\"";
		}
		echo " /></p>";
		echo "<p class=\"t16\"><b>News Item Content</b> (for a single line break hold Shift, then press Enter):<br /><br />";
		echo "<textarea name=\"newstext\" rows=\"20\" cols=\"100\">";
			if (isset($newstext)) {
				echo $newstext;
			}
		echo "</textarea>";
		echo "</p><p class=\"t16\"><b>Display This News Item</b>: &nbsp;";
		echo "<select name=\"display_content\">";
			echo "<option value=\"1\" selected=\"selected\">Yes</option>";
			echo "<option value=\"0\">No</option>";
		echo "</select>";
		echo "</p>";
		echo "<p class=\"t12\"><span class=\"strong\">for reference:</span><br /><br />team_stats.php?team_id=<br /><br />player_stats.php?player_id=<br /><br />images/</p>";
		echo "<hr />";
		echo "<p class=\"t16\">Click 'Submit News' to add this news item: <input type=\"submit\" name=\"submit\" value=\"Submit News\" /></p><br />";
	echo "</form>";
}

include("admin_footer.php"); ?>