<?php
namespace schedulePDF;

require('fpdf/fpdf.php');

class SchedulePDFClass extends \fpdf\FPDF
{
    public function scheduleTable($header, $data)
    {
        // set background colors, border colors, border width and bold font
        $this->SetFillColor(200, 200, 200);
        $this->SetDrawColor(81, 81, 81);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial', 'B');
        // Header
        $w = array(100, 100, 30, 47);
        $this->Cell($w[0], 12, $header[0], 1, 0, 'L', true);
        $this->Cell($w[1], 12, $header[1], 1, 0, 'L', true);
        $this->Cell($w[2], 12, $header[2], 1, 0, 'C', true);
        $this->Cell($w[3], 12, $header[3], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFont('Arial', '');
        // Data
        foreach ($data as $row) {
            $this->Cell($w[0], 15, $row[0], '1', 0, 'L');
            $this->Cell($w[1], 15, $row[1], '1', 0, 'L');
            $this->Cell($w[2], 15, $row[2], '1', 0, 'C');
            $this->Cell($w[3], 15, $row[3], '1', 0, 'C');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
