<div class="row">
    <div class="col-sm-12 justify-content-center text-center">

        <?php
        // find all store nights active in the current season
        $store_division_array = array();
        $query_store_division = $conn->query("SELECT DISTINCT s.division_id, d.day_name, st.store_id, st.store_city FROM schedule AS s JOIN divisions AS d ON (s.division_id=d.division_id) JOIN stores AS st ON (s.store_id=st.store_id) WHERE s.season_id=$cur_season_id && st.active=1 ORDER BY st.store_city, d.division_id ASC");
        while ($result_store_division = $query_store_division->fetch_assoc()) {
            $store_division_array[] = $result_store_division;
        }
        $query_store_division->free_result();
        ?>

        <nav class="navbar navbar-expand-sm justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item mr-2 mb-1">
                    <a class="nav-link a-custom2" href="standings.php">Standings</a>
                </li>

                <?php
                // results dropdown
                include('resultsDropdown/resultsDropdown.php');
                // schedule dropdown
                include('scheduleDropdown/scheduleDropdown.php');
                // stores dropdown
                include('storesDropdown/storesDropdown.php');
                ?>

                <li class="nav-item mr-2 mb-1">
                    <a class="nav-link a-custom2" href="rules.php">Rules</a>
                </li>
                <li class="nav-item mr-2 mb-1">
                    <a class="nav-link a-custom2" href="champions.php">Champions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link a-custom2 mr-2" href="mailto:ktaylor@bellmusicco.com">Contact</a>
                </li>
            </ul>
        </nav>
        <div class="col-sm-12 justify-content-center text-center">
            <?php
            // leaders bar
            include('leadersBar/leadersBar.php');
            // search bar
            include('searchBar/searchBar.php');
            ?>
        </div>
    </div>
</div>