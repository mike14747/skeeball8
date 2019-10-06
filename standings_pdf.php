<?php
require_once('connections/conn.php');
if (isset($get_pdf) && $get_pdf == 1 && isset($get_season_id) && isset($get_store_id) && isset($get_division_id)) {
    // find the currently selected store
    $query_store = $conn->query("SELECT DISTINCT(concat(st.store_id, st.division_id)) AS night, s.store_city, d.day_name, d.division_id FROM standings AS st JOIN stores AS s ON (st.store_id=s.store_id) JOIN divisions AS d ON (st.division_id=d.division_id) WHERE st.season_id=$get_season_id && s.store_id=$get_store_id && d.division_id=$get_division_id ORDER BY s.store_city ASC, st.division_id ASC LIMIT 1");
    if ($query_store->num_rows == 1) {
        require('classes/standingsPDFClass.php');
        $result_store = $query_store->fetch_assoc();
        $store_division = strtoupper($result_store['store_city']) . ' (' . $result_store['day_name'] . ')';
        $query_store->free_result();
        // find most recent games played through date
        $query_recent_games = $conn->query("SELECT DATE_FORMAT(week_date, '%M %d, %Y') AS week_date FROM schedule WHERE week_id=(SELECT MAX(week_id) AS _max_week FROM results WHERE season_id=$get_season_id && store_id=$get_store_id && division_id=$get_division_id) && season_id=$get_season_id && store_id=$get_store_id && division_id=$get_division_id LIMIT 1");
        $result_recent_games = $query_recent_games->fetch_assoc();

        $pdf = new standingsPDF\StandingsPDFClass();
        $pdf->SetTextColor(0, 0, 0);
        // $pdf->AddFont('Calibri','','calibri.php');
        // $pdf->AddFont('Calibri-Bold','','calibrib.php');
        $pdf->AddPage();
        $pdf->Image('images/swt_logo1.jpg', 10, 16, 40, 27.5);
        $pdf->SetFont('Arial', 'B', 18);
        $standings_text_width = $pdf->GetStringWidth('Standings:');
        $standings_text_position = 31 + (.5 * (164 - $standings_text_width));
        $pdf->SetXY($standings_text_position, 18);
        $pdf->Cell(0, 0, 'Standings:', 0, 0, 'L');
        $pdf->Ln(15);
        $pdf->SetFont('Arial', 'B', 26);
        $store_text_width = $pdf->GetStringWidth($store_division);
        $store_text_position = 36 + (.5 * (164 - $store_text_width));
        $pdf->SetX($store_text_position);
        $pdf->Cell(0, 0, $store_division, 0, 0, 'L');
        $pdf->Ln(15);
        $pdf->SetFont('Arial', '', 14);
        $formatted_date = $result_recent_games['week_date'];
        $through_text = '(Through games played on: ' . $formatted_date . ')';
        $through_text_width = $pdf->GetStringWidth($through_text);
        $through_text_position = 36 + (.5 * (164 - $through_text_width));
        $pdf->SetX($through_text_position);
        $pdf->Cell(0, 0, $through_text, 0, 0, 'L');
        $page_width = $pdf->getPageWidth();
        $right_line_ends = $page_width - 10;
        $pdf->Line(10, 60, $right_line_ends, 60);
        $pdf->SetY(70);
        $pdf->SetFont('Arial', 'B', 22);
        // Column headings
        $header = array('TEAM', 'W', 'L', 'T', 'TOT POINTS');
        // Data loading
        $query_standings = $conn->query("SELECT t.team_name, s.wins, s.losses, s.ties, s.total_points, (s.wins-s.losses) AS pct FROM teams AS t JOIN standings AS s ON (t.team_id=s.team_id) WHERE s.season_id=$get_season_id && t.store_id=$get_store_id && s.division_id=$get_division_id ORDER BY pct DESC, total_points DESC");
        $data = array();
        if ($query_standings->num_rows > 0) {
            $rows_query_standings = $query_standings->num_rows;
            for ($i = 0; $i < $rows_query_standings; $i++) {
                $result_standings = $query_standings->fetch_row();
                array_push($data, $result_standings);
            }
            $query_standings->free_result();
        }
        $pdf->SetFont('Arial', '', 16);
        // $pdf->AddPage();
        $pdf->standingsTable($header, $data);
        // output pdf file to the browser
        $pdf_output = 'standings(' . $result_store['night'] . '-' . $result_store['day_name'] . ').pdf';
        $pdf->Output($pdf_output, 'I');
    } else {
        include('components/header/header.php');
        echo '<div class="row">';
        echo '<div class="col-sm-12 pt-4 pb-4">';
        echo '<p class="text-center text-danger"><b>The season being selected for a printable pdf is invalid!</b></p>';
        echo '</div>';
        echo '</div>';
        include('components/footer/footer.php');
    }
} else {
    include('components/header/header.php');
    echo '<div class="row">';
    echo '<div class="col-sm-12 pt-4 pb-4">';
    echo '<p class="text-center text-danger"><b>The season being selected for a printable pdf is invalid!</b></p>';
    echo '</div>';
    echo '</div>';
    include('components/footer/footer.php');
}
