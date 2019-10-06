<?php
require_once('connections/conn.php');
if (isset($get_pdf) && $get_pdf == 1 && isset($get_season_id) && isset($get_store_id) && isset($get_division_id)) {
    // find the currently selected store
    $query_store = $conn->query("SELECT DISTINCT(concat(sch.store_id, sch.division_id)) AS night, s.store_city, d.day_name, d.division_id FROM schedule AS sch JOIN stores AS s ON (sch.store_id=s.store_id) JOIN divisions AS d ON (sch.division_id=d.division_id) WHERE sch.season_id=$get_season_id && s.store_id=$get_store_id && d.division_id=$get_division_id  LIMIT 1");
    if ($query_store->num_rows == 1) {
        require('classes/schedulePDFClass.php');
        $result_store = $query_store->fetch_row();
        $query_store->free_result();
        $store_division = strtoupper($result_store[1]) . ' (' . $result_store[2] . ')';
        if ($get_week_id == '99') {
            echo '99';
        } else {
            $pdf = new schedulePDF\SchedulePDFClass();
            $pdf->SetTextColor(0, 0, 0);
            $pdf->AddPage('L');
            $pdf->Image('images/swt_logo1.jpg', 10, 16, 40, 27.5);
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->SetY(18);
            $pdf->Cell(0, 0, 'Schedule:', 0, 0, 'C');
            $pdf->Ln(12);
            $pdf->SetFont('Arial', 'B', 26);
            $pdf->Cell(0, 0, $store_division, 0, 0, 'C');
            $pdf->Ln(15);
            $pdf->SetFont('Arial', 'B', 18);
            // find the date for this week's schedule
            $query_schedule_date = $conn->query("SELECT DATE_FORMAT(week_date, '%M %d, %Y') AS week_date FROM schedule WHERE season_id=$get_season_id && store_id=$get_store_id && division_id=$get_division_id && week_id=$get_week_id LIMIT 1");
            $result_schedule_date = $query_schedule_date->fetch_row();
            $query_schedule_date->free_result();
            $week_text = 'Week ' . $get_week_id . ' - ' . $result_schedule_date[0];
            $pdf->Cell(0, 0, $week_text, 0, 0, 'C');
            $page_width = $pdf->getPageWidth();
            $right_line_ends = $page_width - 10;
            $pdf->Line(10, 60, $right_line_ends, 60);
            $pdf->SetY(70);
            $pdf->SetFont('Arial', 'B', 22);
            $header = array('AWAY TEAM', 'HOME TEAM', 'ALLEY', 'START TIME');
            // Data loading
            // query schedule for away_team_id, home_team_id and schedule criteria
            $query_matchup = $conn->query("SELECT (SELECT IF(CHAR_LENGTH(t.team_name) > 25, CONCAT(LEFT(t.team_name, 25),'...'), t.team_name) FROM teams AS t WHERE t.team_id=ds.away_team_id) AS away_team, (SELECT IF(CHAR_LENGTH(t.team_name) > 25, CONCAT(LEFT(t.team_name, 25),'...'), t.team_name) FROM teams AS t WHERE t.team_id=ds.home_team_id) AS home_team, ds.alley, ds.start_time FROM (SELECT s.away_team_id AS away_team_id, s.home_team_id AS home_team_id, s.alley AS alley, s.start_time AS start_time FROM schedule AS s WHERE s.season_id={$_GET['season_id']} && s.store_id={$_GET['store_id']} && s.division_id={$_GET['division_id']} && s.week_id={$_GET['week_id']} ORDER BY s.start_time ASC, s.alley ASC) AS ds, teams AS t WHERE t.team_id=ds.away_team_id ORDER BY ds.start_time ASC, ds.alley ASC");
            $data = array();
            for ($i=0; $i<$query_matchup->num_rows; $i++) {
                $result_matchup = $query_matchup->fetch_row();
                array_push($data, $result_matchup);
            }
            $query_matchup->free_result();
            $pdf->SetFont('Arial', '', 20);
            // $pdf->AddPage();
            $pdf->scheduleTable($header, $data);
            // output pdf to a file on the server
            $pdf_output = 'schedule(' . $result_store[1] . '-' . $result_store[3] . ').pdf';
            $pdf->Output($pdf_output, 'I');
        }
    } else {
        include('components/header/header.php');
        echo '<div class="row">';
            echo '<div class="col-sm-12 pt-4 pb-4">';
                echo '<p class="text-center text-danger"><b>The schedule being selected for a printable pdf is invalid!</b></p>';
            echo '</div>';
        echo '</div>';
        include('components/footer/footer.php');
    }
} else {
    include('components/header/header.php');
    echo '<div class="row">';
        echo '<div class="col-sm-12 pt-4 pb-4">';
            echo '<p class="text-center text-danger"><b>The schedule being selected for a printable pdf is invalid!</b></p>';
        echo '</div>';
    echo '</div>';
    include('components/footer/footer.php');
}
