<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include ("admin_header.php");

if (isset($_POST['store_id'])) {
	$store_id = (int)$_POST['store_id'];
}
if (isset($_POST['store_name'])) {
	$store_name = $conn1->real_escape_string($_POST['store_name']);
}
if (isset($_POST['store_address'])) {
	$store_address = $conn1->real_escape_string($_POST['store_address']);
}
if (isset($_POST['store_city'])) {
	$store_city = $conn1->real_escape_string($_POST['store_city']);
}
if (isset($_POST['store_state'])) {
	$store_state = $conn1->real_escape_string($_POST['store_state']);
}
if (isset($_POST['store_zip'])) {
	$store_zip = $conn1->real_escape_string($_POST['store_zip']);
}
if (isset($_POST['store_phone'])) {
	$store_phone = $conn1->real_escape_string($_POST['store_phone']);
}
if (isset($_POST['map_url'])) {
	$map_url = $conn1->real_escape_string($_POST['map_url']);
}
if (isset($_POST['active'])) {
	$active = (int)$_POST['active'];
}
// check to see if a store is being added
if (isset($_POST['add_store']) && $_POST['add_store'] == "Add Store") {
	if (isset($store_name) && $store_name != "" && isset($store_city) && $store_city != "") {
		$conn1->query("INSERT INTO stores (store_id, store_name, store_address, store_city, store_state, store_zip, store_phone, map_url, active) VALUES (null, '$store_name', $store_address, $store_city, $store_state, $store_zip, $store_phone, $map_url, $active)");
		echo "<p class=\"t16\"><b>You've just entered the following store into the database:</b></p>";
		echo "<p class=\"t14\"><b>Store Name:</b> " . $store_name . "<br /><b>Store Address:</b> " . $store_address . "<br /><b>Store City:</b> " . $store_city . "<br /><b>Store State:</b> " . $store_state . "<b>Store Zip:</b> " . $store_zip . "<br /><b>Store Phone:</b> " . $store_phone . "<br /><b>Map URL:</b> " . $map_url . "<br /><b>Active?:</b> ";
		if ($active == 1) {
			echo "Yes<br />";
		} else {
			echo "No<br />";
		}
		echo "</p><br /><br />";
		echo "Return to: <a href=\"add_edit_stores.php\"><b>Add/Edit Stores</b></a> page?<br /><br />";
	} else {
		echo "<p class=\"t16\"><b>No store name and/or city was entered.</b></p><br /><br />";
		echo "Return to: <a href=\"add_edit_stores.php\"><b>Add/Edit Stores</b></a> page?<br /><br />";
	}
// check to see if a store is being editted
} elseif (isset($_POST['edit_store']) && $_POST['edit_store'] == "Edit") {
	if (isset($store_name) && $store_name != "" && isset($store_city) && $store_city != "") {
		$conn1->query("UPDATE stores SET store_name='$store_name', store_address='$store_address', store_city='$store_city', store_state='$store_state', store_zip='$store_zip', store_phone='$store_phone', map_url='$map_url', active=$active  WHERE store_id=$store_id");
		echo "<p class=\"t16\"><b>You've just entered the following info into the database:</b></p>";
		echo "<p class=\"t14\">Store ID: " . $store_id . "<br />Store Name: " . $store_name . "<br />Store Address: " . $store_address . "<br /><b>Store City:</b> " . $store_city . "<br /><b>Store State:</b> " . $store_state . "<b>Store Zip:</b> " . $store_zip . "<br /><b>Store Phone:</b> " . $store_phone . "<br /><b>Map URL:</b> " . $map_url . "<br /><b>Active?:</b> ";
		if ($active == 1) {
			echo "Yes<br />";
		} else {
			echo "No<br />";
		}
		echo "</p><br /><br />";
		echo "Return to: <a href=\"add_edit_stores.php\"><b>Add/Edit Stores</b></a> page?<br /><br />";
	} else {
		echo "<p class=\"t16\"><b>No store name and/or city was entered.</b></p><br /><br />";
		echo "Return to: <a href=\"add_edit_stores.php\"><b>Add/Edit Stores</b></a> page?<br /><br />";
	}
} elseif (isset($_POST['delete_store']) && $_POST['delete_store'] == "Del") {
	echo "<p class=\"t16r\"><b>Are you sure you want to delete:</b></p>";
	echo "<p class=\"t14\"><b>Store ID:</b> " . $store_id . "<br /><b>Store Name:</b> " . $store_name . "<br /><b>Store Address:</b> " . $store_address . "<br /><b>Store City:</b> " . $store_city . "<br /><b>Store State:</b> " . $store_state . "<br /><b>Store Zip:</b> " . $store_zip . "<br /><b>Store Phone:</b> " . $store_phone . "<br /><b>Map URL:</b> " . $map_url . "<br /><b>Active?:</b> ";
		if ($active == 1) {
			echo "Yes<br />";
		} else {
			echo "No<br />";
		}
		echo "</p>";
	echo "<form action=\"add_edit_stores.php\" method=\"post\">";
	// set the hidden fields
	echo "<input type=\"hidden\" name=\"store_id\" value=\"" . $store_id . "\" />";
	echo "<input type=\"hidden\" name=\"store_name\" value=\"" . $store_name . "\" />";
	echo "<input type=\"hidden\" name=\"store_address\" value=\"" . $store_address . "\" />";
	echo "<input type=\"hidden\" name=\"store_city\" value=\"" . $store_city . "\" />";
	echo "<input type=\"hidden\" name=\"store_state\" value=\"" . $store_state . "\" />";
	echo "<input type=\"hidden\" name=\"store_zip\" value=\"" . $store_zip . "\" />";
	echo "<input type=\"hidden\" name=\"store_phone\" value=\"" . $store_phone . "\" />";
	echo "<input type=\"submit\" name=\"delete_store\" value=\"Delete Store\" /></form><br /><br /><br />";
	echo "Return to: <a href=\"add_edit_stores.php\"><b>Add/Edit Stores</b></a> page?<br /><br />";
} elseif (isset($_POST['delete_store']) && $_POST['delete_store'] == "Delete Store" && isset($store_id)) {
	$conn1->query("DELETE FROM stores WHERE store_id=$store_id");
	echo "<p class=\"t16\"><b>The following store has been deleted:</b></p>";
	echo "<p class=\"t14\">Store ID: " . $store_id . "<br />Store Name: " . $store_name . "<br />Store Address: " . $store_address . "<br /><b>Store City:</b> " . $store_city . "<br /><b>Store State:</b> " . $store_state . "<b>Store Zip:</b> " . $store_zip . "<br /><b>Store Phone:</b> " . $store_phone . "<br /><b>Map URL:</b> " . $map_url . "<br /><b>Active?:</b> ";
		if ($active == 1) {
			echo "Yes<br />";
		} else {
			echo "No<br />";
		}
		echo "</p><br /><br />";
	echo "Return to: <a href=\"add_edit_stores.php\"><b>Add/Edit Stores</b></a> page?<br /><br />";
} else {
	// since no submit button has ben clicked, start the page normally
	// start add a store area
	echo "<div class=\"centered\"><span class=\"t16\"><b>Add a new store:</b></span></div><br />";
	echo "<form action=\"add_edit_stores.php\" method=\"post\">";
	echo "<table class=\"schedule\">";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Store Name </span></b></td><td class=\"schedule1\"><input type=\"text\" name=\"store_name\" size=\"40\" value=\"\" /></td></tr>";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Store Address </span></b></td><td class=\"schedule1\"><input type=\"text\" name=\"store_address\" size=\"40\" value=\"\" /></td></tr>";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Store City </span></b></td><td class=\"schedule1\"><input type=\"text\" name=\"store_city\" size=\"40\" value=\"\" /></td></tr>";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Store State </span></b></td><td class=\"schedule1\"><input type=\"text\" name=\"store_state\" size=\"40\" value=\"\" /></td></tr>";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Store Zip </span></b></td><td class=\"schedule1\"><input type=\"text\" name=\"store_zip\" size=\"40\" value=\"\" /></td></tr>";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Store Phone </span></b></td><td class=\"schedule1\"><input type=\"text\" name=\"store_phone\" size=\"40\" value=\"\" /></td></tr>";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Map URL </span></b></td><td class=\"schedule1\"><input type=\"text\" name=\"map_url\" size=\"40\" value=\"\" /></td></tr>";
	echo "<tr class=\"white\"><td class=\"schedule5r\"><b><span class=\"t14\">Active? </span></b></td><td class=\"schedule1\">";
	echo "<select name=\"active\">";
	for ($t=0; $t<=1; $t++) {
		echo "<option value=\"" . $t . "\" ";
		if ($t == 1) {
			echo "selected=\"selected\" ";
		}
		echo "\">";
		if ($t == 0) {
			echo "No";
		} elseif ($t == 1) {
			echo "Yes";
		}
		echo "</option>";
	}
	echo "</select></td></tr>";
	echo "</table>";
	echo "<div class=\"centered\"><input type=\"submit\" name=\"add_store\" value=\"Add Store\" /></div><br />";
	echo "</form>";
	echo "<hr /><br />";
	// start edit an existing store area
	echo "<div class=\"centered\"><span class=\"t16\"><b>Edit (or delete) an existing store:</b></span></div><br />";
	$query_stores = $conn1->query("SELECT * FROM stores ORDER BY store_name ASC");
	if ($query_stores->num_rows > 0) {
		echo "<span class=\"t14\">";
		echo "<table class=\"schedule\"><tr class=\"rowbg\">";
		echo "<td class=\"schedule2\"><b>Store<br />ID</b></td>";
		echo "<td class=\"schedule1\"><b>Store Name</b></td>";
		echo "<td class=\"schedule1\"><b>Store Address</b><br />(plus City, State and Zip Code</td>";
		echo "<td class=\"schedule2\"><b>Store Phone<br />& Map URL</b></td>";
		echo "<td class=\"schedule2\"><b>Currently<br />active?</b></td>";
		echo "<td class=\"schedule2\"><b>Task</b></td>";
		echo "</tr>";
		while ($result_stores = $query_stores->fetch_assoc()) {
			echo "<form action=\"add_edit_stores.php\" method=\"post\">";
			echo "<tr class=\"white\">";
			// set the hidden field for store_id
			echo "<td class=\"schedule2\"><input type=\"hidden\" name=\"store_id\" value=\"" . $result_stores['store_id'] . "\" />" . $result_stores['store_id'] . "</td>";
			echo "<td class=\"schedule1\"><input type=\"text\" name=\"store_name\" size=\"25\" maxlength=\"40\" value=\"" . $result_stores['store_name'] . "\" /></td>";
			echo "<td class=\"schedule1\">";
			// start input fields for address, city, state and zip
			echo "Street Address:<br /><input type=\"text\" name=\"store_address\" size=\"20\" maxlength=\"30\" value=\"" . $result_stores['store_address'] . "\" /><br /><br />";
			echo "Store City:<br /><input type=\"text\" name=\"store_city\" size=\"15\" maxlength=\"20\" value=\"" . $result_stores['store_city'] . "\" /><br /><br />";
			echo "Store State:<br /><input type=\"text\" name=\"store_state\" size=\"10\" maxlength=\"20\" value=\"" . $result_stores['store_state'] . "\" /><br /><br />";
			echo "Zip Code::<br /><input type=\"text\" name=\"store_zip\" size=\"10\" maxlength=\"20\" value=\"" . $result_stores['store_zip'] . "\" />";
			echo "<td class=\"schedule1\">Phone Number:<br /><input type=\"text\" name=\"store_phone\" size=\"12\" maxlength=\"20\" value=\"" . $result_stores['store_phone'] . "\" /><br /><br />Map URL:<br /><input type=\"text\" name=\"map_url\" size=\"30\" maxlength=\"255\" value=\"" . $result_stores['map_url'] . "\" /></td>";
			echo "<td class=\"schedule2\">";
			echo "<select name=\"active\">";
			for ($t=0; $t<=1; $t++) {
				echo "<option value=\"" . $t . "\" ";
				if ($t == $result_stores['active']) {
					echo "selected=\"selected\" ";
				}
				echo "\">";
				if ($t == 0) {
					echo "No";
				} elseif ($t == 1) {
					echo "Yes";
				}
				echo "</option>";
			}
			echo "</select></td>";
			echo "<td class=\"schedule2\"><input type=\"submit\" name=\"edit_store\" value=\"Edit\" /><br /><br /><br /><input type=\"submit\" name=\"delete_store\" value=\"Del\" /></td></tr>";
			echo "</form>";
		}
		$query_stores->free_result();
		echo "</table></span></div>";
	} else {
		echo "<p class=\"t16r\">The database doesn't currently contain any stores.</p>";
	}
}

include("admin_footer.php");
?>