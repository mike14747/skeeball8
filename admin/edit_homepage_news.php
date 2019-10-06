<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include("admin_header.php");

echo "<h2>Edit / Hide / Delete a Homepage News Item</h2>";
echo "<hr /><br />";
if (isset($_POST['submit']) && $_POST['submit'] == "Submit News") {
	// an updated news item has been submitted
	// set error variables for blank testing and format testing
	// set the total errors variable
	$t_errors = 0;
	// set the blank field error variable
	$b_errors = 0;
	// set the date formatting error variable
	$f_errors = 0;
	// if the submit button has been clicked, check to see if any of the fields are blank
	if (($_POST['content_heading'] == "") OR ($_POST['newsdate'] == "") OR ($_POST['page_content'] == "")) {
		$b_errors++;
		$t_errors++;
	}
	// if the submit button has been clicked, check to see if the date field is formatted correctly
	if (($_POST['newsdate'] != "") AND (!preg_match("/^20[0-9]{2}-[0-1][0-9]-[0-3][0-9]$/", $_POST['newsdate']))) {
		$f_errors++;
		$t_errors++;
	}
	if ($t_errors == 0) {
		// if no $_POST items are blank or not formatted properly proceed with form processing
		$newsheader = $conn1->real_escape_string($_POST['content_heading']);
		$newsdate = $conn1->real_escape_string($_POST['newsdate']);
		$newstext = $conn1->real_escape_string($_POST['page_content']);
		$display_content = (int)$_POST['display_content'];
		// insert new news item into the database since there is no existing news_id
		$conn1->query("UPDATE store_text SET content_heading='$newsheader', page_content='$newstext', text_date='$newsdate', display_content=$display_content WHERE page_id={$_POST['page_id']}");
		// Display confirmation message that the info has been added to the database
		echo "<p class=\"t16\"><span class=\"blue\"><b>The news item has been edited with the following:</b></span></p><br />";
		echo "<h4>" . strtoupper($newsheader) . "</h4>";
		echo "<span class=\"t12\">Posted on: " . $newsdate . "</span>";
		echo "<span class=\"t14\">" . $_POST['page_content'] . "</span><br />";
		echo "<span class=\"t14\"><b>Display this item:</b> ";
		if ($display_content == 0) {
			echo "No";
		} elseif ($display_content == 1) {
			echo "Yes";
		}
		echo "</span>";
	}
}
if (isset($_POST['submit']) && $_POST['submit'] == "Delete News" && isset($_POST['page_id'])) {
	// a news item was selected for deletion
	echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
		echo "<p class=\"t16\">Are you sure you want to delete the selected news item? &nbsp; ";
		echo "<input type=\"hidden\" name=\"page_id\" value=\"" . $_POST['page_id'] . "\" />";
		echo "<input type=\"submit\" name=\"submit\" value=\"Delete\" />";
		echo "</p>";
	echo "</form>";
} elseif (isset($_POST['submit']) && $_POST['submit'] == "Delete" && isset($_POST['page_id'])) {
	// confirmation to delete a news item was given, so delete it
	$conn1->query("DELETE FROM store_text WHERE page_id={$_POST['page_id']}");
	echo "<p class=\"t16\">The selected (and confirmed) item has been deleted.</p>";
}
if (isset($_POST['submit']) && (($_POST['submit'] == "Select News Item") || ($_POST['submit'] == "Submit News" && isset($t_errors) && $t_errors > 0))) {
	// an item was selected, so load the current data asscoiated with that news item from the query near the top of this page... or validation failed and the $_POST data needs to be added to the form
	if ($_POST['submit'] == "Select News Item") {
		// since a news item was just selected to edit or hide, get the info about it from the database
		$query_news_item = $conn1->query("SELECT * FROM store_text WHERE page_id={$_POST['page_id']} ORDER BY text_date DESC, page_id DESC");
		$result_news_item = $query_news_item->fetch_assoc();
		$query_news_item->free_result();
	}
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
	// since submit button has not been clicked, there are errors after validation or a news item has just been selected for editing, display the form
	echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"page_id\" value=\"" . $_POST['page_id'] . "\" />";
		echo "<p class=\"t16\"><b>News Item Header</b>: &nbsp;<input type=\"text\" name=\"content_heading\" size=\"50\" maxlength=\"100\"";
		if (isset($_POST['content_heading'])) {
			echo " value=\"" . $_POST['content_heading'] . "\"";
		} elseif ($_POST['submit'] == "Select News Item") {
			echo " value=\"" . $result_news_item['content_heading'] . "\"";
		}
		echo " /></p>";
		echo "<p class=\"t16\"><b>News Item Date</b> (in this format: YYYY-MM-DD): &nbsp;<input type=\"text\" name=\"newsdate\" id=\"datepicker\" size=\"15\" maxlength=\"10\"";
		if (isset($_POST['newsdate'])) {
			echo " value=\"" . $_POST['newsdate'] . "\"";
		} elseif ($_POST['submit'] == "Select News Item") {
			echo " value=\"" . $result_news_item['text_date'] . "\"";
		}
		echo " /></p>";
		echo "<p class=\"t16\"><b>News Item Content</b> (for a single line break hold Shift, then press Enter):<br /><br /><textarea name=\"page_content\" rows=\"20\" cols=\"100\">";
		if (isset($_POST['page_content'])) {
			echo $_POST['page_content'];
		} elseif ($_POST['submit'] == "Select News Item") {
			echo $result_news_item['page_content'];
		}
		echo "</textarea></p>";
		echo "<p class=\"t16\"><b>Display This News Item:</b> &nbsp;";
		echo "<select name=\"display_content\">";
			if (isset($_POST['display_content'])) {
				$display_content = $_POST['display_content'];
			} elseif ($_POST['submit'] == "Select News Item") {
				$display_content = $result_news_item['display_content'];
			}
			echo "<option value=\"1\"";
			if ($display_content == 1) {
				echo " selected=\"selected\"";
			}
			echo ">Yes</option>";
			echo "<option value=\"0\"";
			if ($display_content == 0) {
				echo " selected=\"selected\"";
			}
			echo ">No</option>";
		echo "</select>";
		echo "</p>";
		echo "<p class=\"t12\"><span class=\"strong\">for reference:</span><br /><br />team_stats.php?team_id=<br /><br />player_stats.php?player_id=<br /><br />images/</p>";
		echo "<hr />";
		echo "<p class=\"t16\">Click 'Submit News' to make the changes to this news item: &nbsp; <input type=\"submit\" name=\"submit\" value=\"Submit News\" /></p><br />";
	echo "</form>";
	echo "<hr /><br />";
	echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
		echo "<p class=\"t16\"><b>Delete this news item</b> (this is permanent and cannot be undone): &nbsp; ";
		echo "<input type=\"hidden\" name=\"page_id\" value=\"" . $_POST['page_id'] . "\" />";
		echo "<input type=\"submit\" name=\"submit\" value=\"Delete News\" />";
		echo "</p>";
	echo "</form>";
} elseif (!isset($_POST['submit'])) {
	// select a homepage news item to edit or hide since submit was not clicked
	$query_homepage_news = $conn1->query("SELECT page_id, content_heading, text_date FROM store_text WHERE store_id=10 ORDER BY text_date DESC");
	if ($query_homepage_news->num_rows > 0) {
		echo "<p class=\"t16\"><b>Please select a homepage news item to edit or hide:</b></p><br />";
		while ($result_homepage_news = $query_homepage_news->fetch_assoc()) {
			echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"page_id\" value=\"" . $result_homepage_news['page_id'] . "\" />";
				echo "<p class=\"t16\"><input type=\"submit\" name=\"submit\" value=\"Select News Item\" /> &nbsp;" . $result_homepage_news['content_heading'] . " (" . $result_homepage_news['text_date'] . ")</p><br />";
			echo "</form>";
		}
		$query_homepage_news->free_result();
	} else {
		echo "There are currently no homepage news items to edit or hide.";
	}
}

include("admin_footer.php"); ?>