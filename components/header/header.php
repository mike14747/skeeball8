<!DOCTYPE html>
<html lang="en">

<?php
include('head/head.php');
?>

<body>

    <div class="container mt-2 mb-2">
        <div class="row align-items-end bg-header border-bottom">
            <div class="col-sm-3 pt-2 pb-2 ">
                <a href="index.php"><img class="img-fluid" src="components/header/images/skeeball_logo.png" alt="Skeeball World Tour" /></a>
            </div>
            <div class="col-sm-6 text-center">
                <h1>Skeeball World Tour</h1>
                <p class="mt-4 mb-0 font-weight-lighter">
                    Brought to you by:
                    <img class="ml-4" src="components/header/images/winking_lizard_logo.png" alt="Winking Lizard" style="width:70px;height:60px;" />
                    <img class="ml-4" src="components/header/images/bell_music_logo.png" alt="Bell Music" style="width:150px;height:56px;" />
                </p>
            </div>
            <div class="col-sm-3 text-right">
                <img class="img-fluid" src="components/header/images/flaming_skeeball2.png" alt="Skeeball World Tour" />
            </div>
        </div>

        <?php
        // check to see what the current season_id is
        $query_settings = $conn->query("SELECT current_season, show_reg_button, reg_button_url, reg_button_text, num_leaders, tourny_rankings_status, text_box_heading, text_box_text FROM settings WHERE setting_id=1");
        $result_settings = $query_settings->fetch_assoc();
        // set the current season_id to a variable
        $cur_season_id = $result_settings['current_season'];
        // set all the other settings to variables
        $text_box_heading = $result_settings['text_box_heading'];
        $text_box_text = $result_settings['text_box_text'];
        $show_reg_button = $result_settings['show_reg_button'];
        $reg_button_url = $result_settings['reg_button_url'];
        $reg_button_text = $result_settings['reg_button_text'];
        $num_leaders = $result_settings['num_leaders'];
        $tourny_rankings_status = $result_settings['tourny_rankings_status'];
        $query_settings->free_result();

        include('components/navBar/navBar.php');
        ?>