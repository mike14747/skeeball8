<li class="nav-item dropdown mr-2 mb-1">
    <a class="nav-link dropdown-toggle a-custom2" href="#" id="navbardrop" data-toggle="dropdown">Results</a>
    <div class="dropdown-menu pt-0 pb-0">
        <?php
        foreach ($store_division_array as $results) {
            echo '<p class="small m-0"><a class="dropdown-item a-custom mb-2 p-3" href="results.php?store_id=' . $results['store_id'] . '&amp;division_id=' . $results['division_id'] . '">' . $results['store_city'] . ' (' . $results['day_name'] . ')</a></p>';
        }
        ?>
    </div>
</li>