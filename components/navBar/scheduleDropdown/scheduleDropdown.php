<?php
// find out if the schedule tab should be allowed based on available matches left in the season
$query_schedule_valid = $conn->query("SELECT week_date FROM schedule WHERE season_id=$cur_season_id && CURDATE()<=(SELECT MAX(week_date) FROM schedule WHERE season_id=$cur_season_id)");
if ($query_schedule_valid->num_rows > 0) {
    $query_schedule_valid->free_result();
    echo '<li class="nav-item dropdown navbar-custom mr-2 mb-1">';
    echo '<a class="nav-link dropdown-toggle a-custom2" href="#" id="navbardrop" data-toggle="dropdown">Schedule</a>';
    echo '<div class="dropdown-menu pt-0 pb-0">';
    foreach ($store_division_array as $schedule) {
        echo '<a class="dropdown-item a-custom mb-2 p-3" href="schedule.php?store_id=' . $schedule['store_id'] . '&amp;division_id=' . $schedule['division_id'] . '">' . $schedule['store_city'] . ' (' . $schedule['day_name'] . ')</a></p>';
    }
    echo '</div>';
    echo '</li>';
}
