<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include("admin_header.php");

if (!isset($_POST['submit'])) {
	echo "<h2>Edit content on the Rules page</h2>";
	echo "<hr />";
	$query_cur_text = $conn1->query("SELECT content_heading, page_content FROM store_text WHERE store_id=97");
	if ($query_cur_text->num_rows == 1) {
		$result_cur_text = $query_cur_text->fetch_assoc();
		echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
			echo "<p class=\"t14\"><b>Page Heading</b></p>";
			echo "<input type=\"text\" name=\"rules_heading\" value=\"" . $result_cur_text['content_heading'] . "\" maxlength=\"100\" size=\"50\" /><br /><br />";
			echo "<p class=\"t14\"><b>Enter/edit the text on the Rules page:</b><br />";
			echo "<textarea name=\"rules_text\" rows=\"30\" cols=\"100\">";
				echo $result_cur_text['page_content'];
			echo "</textarea></p><br />";
			echo "<input type=\"submit\" name=\"submit\" value=\"Update Text\">";
		echo "</form>";
		$query_cur_text->free_result();
	} else {
		echo "<p>There currently is no content for League Rules in the database.</p>";
	}
} elseif ((isset($_POST['submit'])) && ($_POST['submit'] == "Update Text")) {
	$new_heading = $conn1->real_escape_string($_POST['rules_heading']);
	$new_content = $conn1->real_escape_string($_POST['rules_text']);
	// since submit has been clicked, update the database
	$conn1->query("UPDATE store_text SET content_heading='$new_heading', page_content='$new_content' WHERE store_id=97");
	echo "<p class=\"t16\"><b>You've successfully updated the database with the new text!</b></p>";
}

include("admin_footer.php"); ?>