<?php
session_start();
require_once 'config/database.php';
require_once 'fpdf.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

function generatePDF($data, $reportName) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Risk Management Report', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'R');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 7, 'Risk Name', 1);
    $pdf->Cell(30, 7, 'Level', 1);
    $pdf->Cell(40, 7, 'Category', 1);
    $pdf->Cell(30, 7, 'Probability', 1);
    $pdf->Cell(30, 7, 'Impact', 1);
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 10);
    foreach ($data['risks'] as $risk) {
        $pdf->Cell(40, 6, $risk['name'], 1);
        $pdf->Cell(30, 6, $risk['level'], 1);
        $pdf->Cell(40, 6, $risk['category'], 1);
        $pdf->Cell(30, 6, $risk['probability'], 1);
        $pdf->Cell(30, 6, $risk['impact'], 1);
        $pdf->Ln();
    }
    $pdf->Output('D', $reportName . '.pdf');
    exit;
}

function generateCSV($data, $reportName) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $reportName . '.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Risk Name', 'Level', 'Category', 'Probability', 'Impact', 'Controls']);
    foreach ($data['risks'] as $risk) {
        fputcsv($output, [
            $risk['name'],
            $risk['level'],
            $risk['category'],
            $risk['probability'],
            $risk['impact'],
            $risk['controls']
        ]);
    }
    fclose($output);
    exit;
}

if (isset($_GET['format'])) {
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $risks_query = "
            SELECT 
                r.id,
                r.name,
                r.description,
                r.probability,
                r.impact,
                r.brutCriticality,
                rf.name as category,
                CASE 
                    WHEN r.brutCriticality >= 18 THEN 'High'
                    WHEN r.brutCriticality >= 12 THEN 'Medium'
                    ELSE 'Low'
                END as level,
                (
                    SELECT GROUP_CONCAT(c.name SEPARATOR ', ')
                    FROM RiskControl rc
                    JOIN Control c ON rc.controlId = c.id
                    WHERE rc.riskId = r.id
                ) as controls
            FROM Risk r
            LEFT JOIN RiskFamily rf ON r.riskFamilyId = rf.id
            WHERE r.active = 1
            ORDER BY r.brutCriticality DESC";
        $stmt = $conn->query($risks_query);
        $risks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [
            'risks' => $risks,
            'generated_date' => date('Y-m-d H:i:s')
        ];
        $reportName = 'Risk_Report_' . date('Y-m-d');
        if ($_GET['format'] === 'pdf') {
            generatePDF($data, $reportName);
        } elseif ($_GET['format'] === 'xlsx' || $_GET['format'] === 'csv') {
            generateCSV($data, $reportName);
        }
    } catch (Exception $e) {
        error_log("Download error: " . $e->getMessage());
        header("Location: reports.php?error=download_failed");
        exit;
    }
} else {
    header("Location: reports.php?error=invalid_format");
    exit;
}
