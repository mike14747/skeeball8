<?php
require_once('connections/conn1.php');
require_once('admin_prehead.php');
include('admin_header.php');

if ($access_level == 2) {
    ?>
    <!-- Store Admin -->
    <p class="t16"><b>Store Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="add_edit_stores.php"><b>Add / Edit</b></a> Stores table in the database.</div>
            </li><br />
        </ul>
    </p>
    <!-- Page Content Admin -->
    <p class="t16"><b>Page Content Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="add_homepage_news.php"><b>Add</b> a news item</a> to the home page.</div>
            </li><br />
            <li>
                <div class="t16"><a href="edit_homepage_news.php"><b>Edit</b> or <b>Hide</b> an existing</a> home page news item.</div>
            </li><br />
            <li>
                <div class="t16"><a href="edit_rules_text.php"><b>Edit League Rules</b></a> page content.</div>
            </li><br />
        </ul>
    </p>
    <!-- Team Admin -->
    <p class="t16"><b>Team Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="add_edit_teams.php"><b>Add / Edit</b></a> Teams table in the database.</div>
            </li><br />
            <li>
                <div class="t16"><a href="display_players_teams.php"><b>Display</b></a> which players have ever played for each team.</div>
            </li><br />
        </ul>
    </p>
    <!-- Player Admin -->
    <p class="t16"><b>Player Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="display_players.php"><b>Display</b> a list of players</a> (with their player ids, store ids and store names) currently in the database.</div>
            </li><br />
        </ul>
    </p>
    <!-- Schedule Admin -->
    <p class="t16"><b>Schedule Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="upload_schedule.php"><b>Upload</b> Schedule</a> to the database via .csv file (must be done at the beginning of the season before any results can be uploaded).</div>
            </li><br />
            <li>
                <div class="t16"><a href="create_schedule_layout.php"><b>Create</b> basic schedule layout</a> for adding a store's new season schedule to the master schedule spreadsheet.</div>
            </li><br />
        </ul>
    </p>
    <!-- Results Admin -->
    <p class="t16"><b>Results Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="results1_select_store.php"><b>Add / Edit</b> Results</a> to the database via web based interface.</div>
            </li><br />
        </ul>
    </p>
    <!-- Settings Admin -->
    <p class="t16"><b>Settings Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="update_settings.php"><b>Update</b> website settings</a>.</div>
            </li><br />
        </ul>
    </p>
    <!-- Season Admin -->
    <p class="t16"><b>Season Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="add_edit_seasons.php"><b>Add / Edit</b></a> season information. Adding a season will need to be done before each season. Editing this to add the tournament winner will need to be done after the tournament.</div>
            </li><br />
        </ul>
    </p>
    <?php
} elseif ($access_level == 1) {
    ?>
    <!-- Results Admin -->
    <p class="t16"><b>Results Admin</b>
        <ul id="aadmin">
            <li>
                <div class="t16"><a href="results1_select_store.php"><b>Add / Edit</b> Results</a> to the database via web based interface for your store.</div>
            </li><br />
        </ul>
    </p>
    <?php
}
?>

<?php
include('admin_footer.php');
?>