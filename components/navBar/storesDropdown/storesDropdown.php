<?php
echo '<li class="nav-item dropdown navbar-custom mr-2 mb-1">';
echo '<a class="nav-link dropdown-toggle a-custom2" href="#" id="navbardrop" data-toggle="dropdown">Stores</a>';
echo '<div class="dropdown-menu pt-0 pb-0">';
foreach ($store_division_array as $stores) {
    echo '<p class="small m-0"><a class="dropdown-item a-custom mb-2 p-3" href="store_home.php?store_id=' . $stores['store_id'] . '&amp;division_id=' . $stores['division_id'] . '">' . $stores['store_city'] . ' (' . $stores['day_name'] . ')</a></p>';
}
echo '</div>';
echo '</li>';
