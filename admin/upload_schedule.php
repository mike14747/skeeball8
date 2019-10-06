<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_POST['submit']) && $_POST['submit'] == "Upload Schedule") {
	$errStr = "";
	set_time_limit(0);
	define("DESTINATION_FOLDER", "../upload");
	$uploaded_file = $_FILES['csv_file'];
	$file_name = $uploaded_file['name'];
	$tmp_file_name = $uploaded_file['tmp_name'];
	// do error checking on the file being uploaded
	if ($_FILES['csv_file']['size'] == 0) {
		$errStr .= "<br />No file was selected for upload.<br />";
	} elseif ($_FILES['csv_file']['size'] > 0) {
		if ($file_name != "schedule.csv") {
			$errStr .= "<br />File is not valid. Only the template file for schedule can be uploaded.<br />";
		}
		if ((!is_uploaded_file($uploaded_file['tmp_name'])) || ($uploaded_file['error'] != 0)) {
			$errStr .= "<br />There was an error uploading the file.<br />";
		}
	}
	if (!is_dir(DESTINATION_FOLDER) || !is_writeable(DESTINATION_FOLDER)) {
		$errStr .= "<br />Destination folder is either not present or is not writeable.<br />";
	}
}
echo "<h2>UPLOAD the entire SCHEDULE to the DATABASE</h2>";
echo "<hr /><br />";
if (isset($_POST['submit']) && $_POST['submit'] == "Upload Schedule") {
	if (!empty($errStr)) {
		echo "<p class=\"t16\"><span class=\"red\"><b>ERROR!<br /></b>" . $errStr . "</span></p>";
	} elseif (empty($errStr)) {
		if (copy($tmp_file_name,DESTINATION_FOLDER . "/" . $file_name)) {
			$conn1->query("DELETE FROM schedule");
			$handle = fopen ("../upload/".$file_name,"r");
			while (($data = fgetcsv ($handle, 1000, ",")) !== FALSE) {
				if(trim($data[0])=="schedule_id") {
					continue;
				}
				$schedule_id = trim($data[0]);
				$season_id = trim($data[1]);
				$store_id = trim($data[2]);
				$division_id = trim($data[3]);
				$week_id = trim($data[4]);
				$week_date = $conn1->real_escape_string(trim($data[5]));
				$start_time = $conn1->real_escape_string(trim($data[6]));
				$alley = trim($data[7]);
				$away_team_id = trim($data[8]);
				$home_team_id = trim($data[9]);
				$conn1->query("INSERT INTO schedule VALUES ($schedule_id, $season_id, $store_id, $division_id, $week_id, '$week_date', '$start_time', $alley, $away_team_id, $home_team_id)");
			}
			fclose ($handle);
			if (file_exists("../upload/schedule.csv")) {
				unlink("../upload/schedule.csv");
			}
		}
		echo "<p class=\"t16\"><span class=\"blue\">You've successfully uploaded the schedule to the database via the .csv file.</span></p>";
	}
}
if ((isset($_POST['submit']) && $_POST['submit'] == "Upload File" && !empty($errStr)) || (!isset($_POST['submit']))) {
	echo "<p class=\"t14r\"><b>Keep these things in mind when trying to upload the Schedule:</b></p>";
	echo "<span class=\"t14r\">";
	echo "<ul>";
	echo "<li>The only files allowed for upload are .csv files.</li>";
	echo "<li>You must use the template and it must be in the original column format (with 'schedule_id' in the first cell of the first row).</li>";
	echo "<li>This process will delete the entire Schedule table and replace it with the Schedule in the uploaded file.</li>";
	echo "</ul>";
	echo "</span><br />";

	echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\" enctype=\"multipart/form-data\" name=\"form1\">";
		echo "<p class=\"t14\"><b>Upload .csv File: </b>";
		echo "<input name=\"csv_file\" type=\"file\" id=\"csv_file\" size=\"50\"></p>";
		echo "<input type=\"submit\" name=\"submit\" value=\"Upload Schedule\">";
		echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input type=\"reset\" name=\"Reset\" value=\"Reset\">";
	echo "</form>";
}

include ("admin_footer.php");
?>