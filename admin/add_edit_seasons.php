<?php
require_once('connections/conn1.php');
require_once('admin_prehead.php');
include('admin_header.php');

if (isset($_POST['season_id'])) {
    $season_id = (int) $_POST['season_id'];
}
if (isset($_POST['season_num'])) {
    $season_num = (int) $_POST['season_num'];
    if ($season_num == 1) {
        $season_name = 'Winter';
    } elseif ($season_num == 2) {
        $season_name = 'Spring';
    } elseif ($season_num == 3) {
        $season_name = 'Summer';
    } elseif ($season_num == 4) {
        $season_name = 'Fall';
    }
}
if (isset($_POST['year'])) {
    $year = (int) $_POST['year'];
}
if (isset($_POST['season_games'])) {
    $season_games = (int) $_POST['season_games'];
}
if (isset($_POST['tourny_team_id'])) {
    $tourny_team_id = (int) $_POST['tourny_team_id'];
}
if (isset($_POST['comments'])) {
    $comments = $conn1->real_escape_string($_POST['comments']);
}
if (isset($_POST['reg_ends'])) {
    $reg_ends = $conn1->real_escape_string($_POST['reg_ends']);
}
if (isset($_POST['start_date'])) {
    $start_date = $conn1->real_escape_string($_POST['start_date']);
}
if (isset($_POST['end_date'])) {
    $end_date = $conn1->real_escape_string($_POST['end_date']);
}
if (isset($_POST['tourny_date'])) {
    $tourny_date = $conn1->real_escape_string($_POST['tourny_date']);
}
// check to see if a season is being added
if (isset($_POST['add_season']) && $_POST['add_season'] == 'Add Season') {
    if (isset($season_num) && $season_num != '' && isset($year) && $year != '') {
        $conn1->query("INSERT INTO seasons (season_id, season_num, season_name, year, season_games, tourny_team_id, comments, reg_ends, start_date, end_date, tourny_date) VALUES (null, '$season_num', '$season_name', $year, $season_games, $tourny_team_id, '$comments', '$reg_ends', '$start_date', '$end_date', '$tourny_date')");
        echo '<p class="t16"><b>You\'ve just entered the following season into the database:</b></p>';
        echo '<p class="t14"><b>Season Name:</b> ' . $season_name . '<br /><b>Year:</b> ' . $year . '<br /><b>Season Games:</b> ' . $season_games . '<br /><b>Tournament Winner:</b> ';
        if ($tourny_team_id > 0) {
            echo $tourny_team_id;
        } else {
            echo '--- No Champion Yet ---';
        }
        echo '<br />';
        echo '<b>Comments:</b> ' . $comments . '<br /><b>Registration Ends:</b> ' . $reg_ends . '<br /><b>Start Date:</b> ' . $start_date . '<br /><b>End Date:</b> ' . $end_date . '<br /><b>Tournament Date:</b> ' . $tourny_date;
        echo '</p><br /><br />';
        echo 'Return to: <a href="add_edit_seasons.php"><b>Add/Edit Season</b></a> page?<br /><br />';
    } else {
        echo '<p class="t16"><b>No season name and/or year were entered.</b></p><br /><br />';
        echo 'Return to: <a href="add_edit_seasons.php"><b>Add/Edit Seasons</b></a> page?<br /><br />';
    }
    // check to see if a season is being editted
} elseif (isset($_POST['edit_season']) && $_POST['edit_season'] == 'Edit') {
    if (isset($season_num) && $season_num != '' && isset($year) && $year != '') {
        $conn1->query("UPDATE seasons SET season_num='$season_num', season_name='$season_name', year=$year, season_games=$season_games, tourny_team_id=$tourny_team_id, comments='$comments', reg_ends='$reg_ends', start_date='$start_date', end_date='$end_date', tourny_date='$tourny_date'  WHERE season_id=$season_id");
        echo '<p class="t16"><b>You\'ve just entered the following info into the database:</b></p>';
        echo '<p class="t14"><b>Season Name:</b> ' . $season_name . '<br /><b>Year:</b> ' . $year . '<br /><b>Season Games:</b> ' . $season_games . '<br /><b>Tournament Winner:</b> ';
        if ($tourny_team_id > 0) {
            echo $tourny_team_id;
        } else {
            echo '--- No Champion Yet ---';
        }
        echo '<b>Comments:</b> ' . $comments . '<br /><b>Registration Ends:</b> ' . $reg_ends . '<br /><b>Start Date:</b> ' . $start_date . '<br /><b>End Date:</b> ' . $end_date . '<br /><b>Tournament Date:</b> ' . $tourny_date;
        echo '</p><br /><br />';
        echo 'Return to: <a href="add_edit_seasons.php"><b>Add/Edit Seasons</b></a> page?<br /><br />';
    } else {
        echo '<p class="t16"><b>No season name and/or year were entered.</b></p><br /><br />';
        echo 'Return to: <a href="add_edit_seasons.php"><b>Add/Edit Seasons</b></a> page?<br /><br />';
    }
} elseif (isset($_POST['delete_season']) && $_POST['delete_season'] == "Del") {
    echo '<p class="t16r"><b>Are you sure you want to delete:</b></p>';
    echo '<p class="t14"><b>Season Name:</b> ' . $season_name . '<br /><b>Year:</b> ' . $year . '<br /><b>Season Games:</b> ' . $season_games . '<br /><b>Tournament Winner:</b> ';
    if ($tourny_team_id > 0) {
        echo $tourny_team_id;
    } else {
        echo 'N/A';
    }
    echo '<b>Comments:</b> ' . $comments . '<br /><b>Registration Ends:</b> ' . $reg_ends . '<br /><b>Start Date:</b> ' . $start_date . '<br /><b>End Date:</b> ' . $end_date . '<br /><b>Tournament Date:</b> ' . $tourny_date;
    echo '</p>';
    echo '<form action="add_edit_seasons.php" method="post">';
    // set the hidden fields
    echo '<input type="hidden" name="season_id" value="' . $season_id . '" />';
    echo '<input type="hidden" name="season_num" value="' . $season_num . '" />';
    echo '<input type="hidden" name="season_name" value="' . $season_name . '" />';
    echo '<input type="hidden" name="year" value="' . $year . '" />';
    echo '<input type="hidden" name="season_games" value="' . $season_games . '" />';
    echo '<input type="hidden" name="tourny_team_id" value="' . $tourny_team_id . '" />';
    echo '<input type="hidden" name="comments" value="' . $comments . '" />';
    echo '<input type="hidden" name="reg_ends" value="' . $reg_ends . '" />';
    echo '<input type="hidden" name="start_date" value="' . $start_date . '" />';
    echo '<input type="hidden" name="end_date" value="' . $end_date . '" />';
    echo '<input type="hidden" name="tourny_date" value="' . $tourny_date . '" />';
    echo '<input type="submit" name="delete_season" value="Delete Season" />';
    echo '</form><br /><br /><br />';
    echo 'Return to: <a href="add_edit_seasons.php"><b>Add/Edit Seasons</b></a> page?<br /><br />';
} elseif (isset($_POST['delete_season']) && $_POST['delete_season'] == "Delete Season" && isset($season_id)) {
    $conn1->query("DELETE FROM seasons WHERE season_id=$season_id");
    echo '<p class="t16"><b>The following season has been deleted:</b></p>';
    echo '<p class="t14"><b>Season Name:</b> ' . $season_name . '<br /><b>Year:</b> ' . $year . '<br /><b>Season Games:</b> ' . $season_games . '<br /><b>Tournament Winner:</b> ';
    if ($tourny_team_id > 0) {
        echo $tourny_team_id;
    } else {
        echo 'N/A';
    }
    echo '<b>Comments:</b> ' . $comments . '<br /><b>Registration Ends:</b> ' . $reg_ends . '<br /><b>Start Date:</b> ' . $start_date . '<br /><b>End Date:</b> ' . $end_date . '<br /><b>Tournament Date:</b> ' . $tourny_date;
    echo '</p><br /><br />';
    echo 'Return to: <a href="add_edit_seasons.php"><b>Add/Edit Seasons</b></a> page?<br /><br />';
} else {
    // since no submit button has ben clicked, start the page normally
    // start add a season area
    echo '<div class="centered"><span class="t16"><b>Add a new season:</b></span></div><br />';
    echo '<form action="add_edit_seasons.php" method="post">';
    echo '<table class="schedule">';
    echo '<tr class="white"><td class="schedule5r"><b><span class="t14">Season Name </span></b></td><td class="schedule1">';
    echo '<select name="season_num">';
    for ($s = 1; $s <= 4; $s++) {
        echo '<option value="" . $s . "">';
        if ($s == 1) {
            echo 'Winter';
        } elseif ($s == 2) {
            echo 'Spring';
        } elseif ($s == 3) {
            echo 'Summer';
        } elseif ($s == 4) {
            echo 'Fall';
        }
        echo '</option>';
    }
    echo '</select>';
    echo '</td></tr>';
    echo '<tr class="white"><td class="schedule5r"><b><span class="t14">Year </span></b></td><td class="schedule1">';
    echo '<select name="year">';
    echo '<option value="">Select a Year</option>';
    for ($y = 2010; $y <= 2030; $y++) {
        echo '<option value="' . $y . '">' . $y . '</option>';
    }
    echo '</select>';
    echo '</td></tr>';
    echo '<tr class="white"><td class="schedule5r"><b><span class="t14">Games per Team </span></b></td><td class="schedule1">';
    echo '<select name="season_games">';
    echo '<option value="70">70</option>';
    echo '<option value="90" selected="selected">90</option>';
    echo '</select>';
    echo '</td></tr>';
    echo '<tr class="white"><td class="schedule5r"><b><span class="t14">Tournament Winner </span></b></td><td class="schedule1">';
    echo '<select name="tourny_team_id">';
    echo '<option value="0">--- No Champion Yet ---</option>';
    // start a query to find all the teams in the league
    $query_teams_info = $conn1->query("SELECT t.team_id, t.team_name, s.store_city FROM teams AS t JOIN stores AS s ON t.store_id=s.store_id ORDER BY t.team_name ASC, s.store_city ASC");
    while ($result_teams_info = $query_teams_info->fetch_assoc()) {
        echo '<option value="';
        echo $result_teams_info['team_id'];
        echo '">';
        echo $result_teams_info['team_name'] . ' (' . $result_teams_info['store_city'] . ')';
        echo '</option>';
    }
    $query_teams_info->free_result();
    echo '</select>';
    echo '</td></tr>';
    echo '<tr class="white"><td class="schedule5r"><b><span class="t14">Comments </span></b></td><td class="schedule1"><input type="text" name="comments" size="40" value="" maxlength="50" /></td></tr>';
    echo '<tr class="white"><td class="schedule5r"><span class="t14"><b>Registration End Date</b> </td><td class="schedule1"><input type="text" name="reg_ends" id="datepicker" size="15" maxlength="10" /></span><span class="t12"> &nbsp;(in this format: YYYY-MM-DD)</span></td></tr>';
    echo '<tr class="white"><td class="schedule5r"><span class="t14"><b>Season Start Date</b> </td><td class="schedule1"><input type="text" name="start_date" id="datepicker1" size="15" maxlength="10" /></span><span class="t12"> &nbsp;(in this format: YYYY-MM-DD)</span></td></tr>';
    echo '<tr class="white"><td class="schedule5r"><span class="t14"><b>Season End Date</b> </td><td class="schedule1"><input type="text" name="end_date" id="datepicker2" size="15" maxlength="10" /></span><span class="t12"> &nbsp;(in this format: YYYY-MM-DD)</span></td></tr>';
    echo '<tr class="white"><td class="schedule5r"><span class="t14"><b>Tournament Date</b> </td><td class="schedule1"><input type="text" name="tourny_date" id="datepicker3" size="15" maxlength="10" /></span><span class="t12"> &nbsp;(in this format: YYYY-MM-DD)</span></td></tr>';
    echo '</table>';
    echo '<div class="centered"><input type="submit" name="add_season" value="Add Season" /></div><br />';
    echo '</form>';
    echo '<hr /><br />';
    // start edit an existing season area
    echo '<div class="centered"><span class="t16"><b>Edit (or delete) an existing season:</b></span></div><br />';
    $query_seasons = $conn1->query("SELECT * FROM seasons ORDER BY season_id ASC");
    if ($query_seasons->num_rows > 0) {
        echo '<span class="t14">';
        echo '<table class="schedule"><tr class="rowbg">';
        echo '<td class="schedule2"><b>Season<br />ID</b></td>';
        echo '<td class="schedule1"><b>Season<br />& Year</b></td>';
        echo '<td class="schedule2"><b>Games<br />/ Team</b></td>';
        echo '<td class="schedule2"><b>Champion</b></td>';
        echo '<td class="schedule1"><b>Comments</b></td>';
        echo '<td class="schedule1"><b>Dates</b><br /><span class="t12">(in this format:<br />YYYY-MM-DD)</span></td>';
        echo '<td class="schedule2"><b>Task</b></td>';
        echo '</tr>';
        while ($result_seasons = $query_seasons->fetch_assoc()) {
            echo '<form action="add_edit_seasons.php" method="post">';
            // set the hidden field for season_id
            echo '<tr class="white"><td class="schedule2"><input type="hidden" name="season_id" value="' . $result_seasons['season_id'] . '" />' . $result_seasons['season_id'] . "</td>";
            echo '<td class="schedule1">Season Name<br />';
            echo '<select name="season_num">';
            for ($s = 1; $s <= 4; $s++) {
                echo '<option value="' . $s . '"';
                if ($s == $result_seasons['season_num']) {
                    echo ' selected="selected"';
                }
                echo ">";
                if ($s == 1) {
                    echo 'Winter';
                } elseif ($s == 2) {
                    echo 'Spring';
                } elseif ($s == 3) {
                    echo 'Summer';
                } elseif ($s == 4) {
                    echo 'Fall';
                }
                echo '</option>';
            }
            echo '</select><br /><br />';
            echo 'Year<br />';
            echo '<select name="year">';
            echo '<option value="">Select a Year</option>';
            for ($y = 2010; $y <= 2030; $y++) {
                echo '<option value="' . $y . '"';
                if ($y == $result_seasons['year']) {
                    echo ' selected="selected"';
                }
                echo '>' . $y . '</option>';
            }
            echo '</select>';
            echo '</td>';
            echo '<td class="schedule2">';
            echo '<select name="season_games">';
            $season_games_array = array(5, 7, 70, 90);
            foreach ($season_games_array as $sg) {
                echo '<option value="' . $sg . '"';
                if ($result_seasons['season_games'] == $sg) {
                    echo ' selected="selected"';
                }
                echo '>' . $sg . '</option>';
            }
            echo '</select>';
            echo '</td>';
            echo '<td class="schedule2">';
            echo '<select class="drop1" name="tourny_team_id">';
            echo '<option value="0">--- No Champion Yet ---</option>';
            // start a query to find all the teams in the league
            $query_teams_info = $conn1->query("SELECT t.team_id, t.team_name, s.store_city FROM teams AS t JOIN stores AS s ON t.store_id=s.store_id ORDER BY t.team_name ASC, s.store_city ASC");
            while ($result_teams_info = $query_teams_info->fetch_assoc()) {
                echo '<option value="' . $result_teams_info['team_id'] . '"';
                if ($result_teams_info['team_id'] == $result_seasons['tourny_team_id']) {
                    echo ' selected="selected"';
                }
                echo '>';
                echo $result_teams_info['team_name'] . ' (' . $result_teams_info['store_city'] . ')';
                echo '</option>';
            }
            $query_teams_info->free_result();
            echo '</td>';
            echo '</select>';
            echo '<td class="schedule1"><input type="text" name="comments" size="25" value="' . $result_seasons['comments'] . '" maxlength="50" /></td>';
            echo '<td class="schedule1">';
            echo 'Registration Ends<br />';
            echo '<input type="text" name="reg_ends" size="15" value="' . $result_seasons['reg_ends'] . '" maxlength="10" />';
            echo '<br /><br />';
            echo 'Season Starts<br />';
            echo '<input type="text" name="start_date" size="15" value="' . $result_seasons['start_date'] . '" maxlength="10" />';
            echo '<br /><br />';
            echo 'Season Ends<br />';
            echo '<input type="text" name="end_date" size="15" value="' . $result_seasons['end_date'] . '" maxlength="10" />';
            echo '<br /><br />';
            echo 'Tournament Date<br />';
            echo '<input type="text" name="tourny_date" size="15" value="' . $result_seasons['tourny_date'] . '" maxlength="10" />';
            echo '</td>';
            echo '<td class="schedule2"><input type="submit" name="edit_season" value="Edit" /><br /><br /><br /><input type="submit" name="delete_season" value="Del" /></td></tr>';
            echo '</form>';
        }
        $query_seasons->free_result();
        echo '</table></span></div>';
    } else {
        echo '<p class="t16r">The database doesn\'t currently contain any seasons.</p>';
    }
}

include('admin_footer.php');
