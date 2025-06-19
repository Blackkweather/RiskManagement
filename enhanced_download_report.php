<?php
session_start();
require_once 'config/database.php';
require_once 'vendor/autoload.php'; // For PDF generation libraries

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Dompdf\Dompdf;
use Dompdf\Options;

class ReportGenerator {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
    
    public function downloadReport($reportId, $format = 'pdf') {
        try {
            // Get report details
            $stmt = $this->conn->prepare("
                SELECT r.*, p.name as projectName, e.name as entityName 
                FROM report r 
                LEFT JOIN project p ON r.projectId = p.id 
                LEFT JOIN entity e ON r.entityId = e.id 
                WHERE r.id = ? AND r.status = 'Published'
            ");
            $stmt->execute([$reportId]);
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$report) {
                throw new Exception("Rapport non trouvé ou non publié");
            }
            
            // Record the download
            $this->recordDownload($reportId);
            
            // Generate report based on format
            switch (strtolower($format)) {
                case 'pdf':
                    return $this->generatePDF($report);
                case 'excel':
                    return $this->generateExcel($report);
                case 'word':
                    return $this->generateWord($report);
                default:
                    return $this->generatePDF($report);
            }
            
        } catch (Exception $e) {
            error_log("Download error: " . $e->getMessage());
            throw $e;
        }
    }
    
    private function recordDownload($reportId) {
        try {
            // Insert download record
            $stmt = $this->conn->prepare("
                INSERT INTO reportdownload (reportId, downloadedBy, downloadedAt, ipAddress, userAgent) 
                VALUES (?, ?, NOW(), ?, ?)
            ");
            $stmt->execute([
                $reportId,
                $_SESSION['user_name'] ?? 'Anonymous',
                $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ]);
            
            // Update download count
            $stmt = $this->conn->prepare("UPDATE report SET download_count = download_count + 1 WHERE id = ?");
            $stmt->execute([$reportId]);
            
        } catch (Exception $e) {
            error_log("Error recording download: " . $e->getMessage());
        }
    }
    
    private function generatePDF($report) {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // Get report data based on type
        $reportData = $this->getReportData($report);
        
        // Generate HTML content
        $html = $this->generateHTMLContent($report, $reportData);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Set headers for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $this->sanitizeFilename($report['name']) . '.pdf"');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($dompdf->output()));
        
        echo $dompdf->output();
        exit;
    }
    
    private function generateExcel($report) {
        // Implementation for Excel generation using PhpSpreadsheet
        require_once 'vendor/autoload.php';
        
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set report header
        $sheet->setCellValue('A1', 'Rapport: ' . $report['name']);
        $sheet->setCellValue('A2', 'Type: ' . $report['type']);
        $sheet->setCellValue('A3', 'Généré le: ' . date('d/m/Y H:i:s'));
        $sheet->setCellValue('A4', 'Description: ' . $report['description']);
        
        // Get and add report data
        $reportData = $this->getReportData($report);
        $this->addDataToExcel($sheet, $reportData, $report['type']);
        
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $this->sanitizeFilename($report['name']) . '.xlsx"');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        $writer->save('php://output');
        exit;
    }
    
    private function generateWord($report) {
        // Implementation for Word generation using PhpWord
        require_once 'vendor/autoload.php';
        
        use PhpOffice\PhpWord\PhpWord;
        use PhpOffice\PhpWord\IOFactory;
        
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Add title
        $section->addTitle($report['name'], 1);
        $section->addTextBreak(1);
        
        // Add report info
        $section->addText('Type: ' . $report['type']);
        $section->addText('Généré le: ' . date('d/m/Y H:i:s'));
        $section->addText('Description: ' . $report['description']);
        $section->addTextBreak(2);
        
        // Get and add report data
        $reportData = $this->getReportData($report);
        $this->addDataToWord($section, $reportData, $report['type']);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $this->sanitizeFilename($report['name']) . '.docx"');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
    
    private function getReportData($report) {
        $data = [];
        
        switch ($report['type']) {
            case 'Risk Assessment':
                $data = $this->getRiskAssessmentData($report);
                break;
            case 'Compliance':
                $data = $this->getComplianceData($report);
                break;
            case 'Financial':
                $data = $this->getFinancialData($report);
                break;
            case 'Security':
                $data = $this->getSecurityData($report);
                break;
            case 'Executive':
                $data = $this->getExecutiveData($report);
                break;
        }
        
        return $data;
    }
    
    private function getRiskAssessmentData($report) {
        $stmt = $this->conn->prepare("
            SELECT r.*, e.name as entityName, a.name as activityName, p.name as processName
            FROM risk r
            LEFT JOIN entity e ON r.entityId = e.id
            LEFT JOIN activity a ON r.activityId = a.id
            LEFT JOIN process p ON a.processId = p.id
            WHERE r.active = 1
            ORDER BY r.brutCriticality DESC
        ");
        $stmt->execute();
        $risks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'risks' => $risks,
            'summary' => [
                'total_risks' => count($risks),
                'high_risks' => count(array_filter($risks, function($r) { return $r['brutCriticality'] >= 15; })),
                'medium_risks' => count(array_filter($risks, function($r) { return $r['brutCriticality'] >= 10 && $r['brutCriticality'] < 15; })),
                'low_risks' => count(array_filter($risks, function($r) { return $r['brutCriticality'] < 10; }))
            ]
        ];
    }
    
    private function getComplianceData($report) {
        // Get compliance-related data
        $stmt = $this->conn->prepare("
            SELECT rc.*, r.name as riskName, r.description as riskDescription
            FROM riskcontrol rc
            LEFT JOIN risk r ON rc.riskId = r.id
            WHERE r.active = 1
        ");
        $stmt->execute();
        $controls = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'controls' => $controls,
            'summary' => [
                'total_controls' => count($controls),
                'effective_controls' => count(array_filter($controls, function($c) { return $c['evaluation'] >= 80; })),
                'needs_improvement' => count(array_filter($controls, function($c) { return $c['evaluation'] < 80; }))
            ]
        ];
    }
    
    private function getFinancialData($report) {
        $stmt = $this->conn->prepare("
            SELECT r.*, 
                   (r.financialImpact * r.frequency / 100) as expectedLoss,
                   e.name as entityName
            FROM risk r
            LEFT JOIN entity e ON r.entityId = e.id
            WHERE r.active = 1 AND r.financialImpact > 0
            ORDER BY (r.financialImpact * r.frequency / 100) DESC
        ");
        $stmt->execute();
        $financialRisks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalExpectedLoss = array_sum(array_column($financialRisks, 'expectedLoss'));
        
        return [
            'financial_risks' => $financialRisks,
            'summary' => [
                'total_expected_loss' => $totalExpectedLoss,
                'high_impact_risks' => count(array_filter($financialRisks, function($r) { return $r['financialImpact'] >= 4; })),
                'total_risks_with_financial_impact' => count($financialRisks)
            ]
        ];
    }
    
    private function getSecurityData($report) {
        // Get security-related risks and controls
        $stmt = $this->conn->prepare("
            SELECT r.*, e.name as entityName, a.name as activityName
            FROM risk r
            LEFT JOIN entity e ON r.entityId = e.id
            LEFT JOIN activity a ON r.activityId = a.id
            WHERE r.active = 1 AND (
                r.description LIKE '%sécurité%' OR 
                r.description LIKE '%cyber%' OR 
                r.description LIKE '%données%' OR
                r.name LIKE '%sécurité%'
            )
            ORDER BY r.brutCriticality DESC
        ");
        $stmt->execute();
        $securityRisks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'security_risks' => $securityRisks,
            'summary' => [
                'total_security_risks' => count($securityRisks),
                'critical_security_risks' => count(array_filter($securityRisks, function($r) { return $r['brutCriticality'] >= 20; }))
            ]
        ];
    }
    
    private function getExecutiveData($report) {
        // Get high-level summary data for executives
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total_risks,
                AVG(brutCriticality) as avg_criticality,
                SUM(CASE WHEN brutCriticality >= 15 THEN 1 ELSE 0 END) as high_risks,
                SUM(CASE WHEN brutCriticality >= 10 AND brutCriticality < 15 THEN 1 ELSE 0 END) as medium_risks,
                SUM(CASE WHEN brutCriticality < 10 THEN 1 ELSE 0 END) as low_risks
            FROM risk 
            WHERE active = 1
        ");
        $stmt->execute();
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get top risks
        $stmt = $this->conn->prepare("
            SELECT r.name, r.brutCriticality, e.name as entityName
            FROM risk r
            LEFT JOIN entity e ON r.entityId = e.id
            WHERE r.active = 1
            ORDER BY r.brutCriticality DESC
            LIMIT 10
        ");
        $stmt->execute();
        $topRisks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'summary' => $summary,
            'top_risks' => $topRisks
        ];
    }
    
    private function generateHTMLContent($report, $data) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($report['name']) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .title { font-size: 24px; font-weight: bold; color: #333; }
                .subtitle { font-size: 14px; color: #666; margin-top: 10px; }
                .section { margin: 20px 0; }
                .section-title { font-size: 18px; font-weight: bold; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .risk-high { background-color: #ffebee; }
                .risk-medium { background-color: #fff3e0; }
                .risk-low { background-color: #e8f5e8; }
                .summary-box { background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="title">' . htmlspecialchars($report['name']) . '</div>
                <div class="subtitle">
                    Type: ' . htmlspecialchars($report['type']) . '<br>
                    Généré le: ' . date('d/m/Y H:i:s') . '<br>
                    ' . ($report['projectName'] ? 'Projet: ' . htmlspecialchars($report['projectName']) . '<br>' : '') . '
                    ' . ($report['entityName'] ? 'Entité: ' . htmlspecialchars($report['entityName']) . '<br>' : '') . '
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Description</div>
                <p>' . htmlspecialchars($report['description']) . '</p>
            </div>';
        
        // Add specific content based on report type
        $html .= $this->generateTypeSpecificHTML($report['type'], $data);
        
        $html .= '
            <div class="section">
                <div class="section-title">Informations sur le Rapport</div>
                <div class="summary-box">
                    <strong>Généré par:</strong> ' . htmlspecialchars($report['generated_by'] ?? 'Système') . '<br>
                    <strong>Date de création:</strong> ' . date('d/m/Y H:i:s', strtotime($report['createdAt'])) . '<br>
                    <strong>Dernière mise à jour:</strong> ' . date('d/m/Y H:i:s', strtotime($report['updatedAt'])) . '<br>
                    <strong>Nombre de téléchargements:</strong> ' . $report['download_count'] . '
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    private function generateTypeSpecificHTML($type, $data) {
        switch ($type) {
            case 'Risk Assessment':
                return $this->generateRiskAssessmentHTML($data);
            case 'Compliance':
                return $this->generateComplianceHTML($data);
            case 'Financial':
                return $this->generateFinancialHTML($data);
            case 'Security':
                return $this->generateSecurityHTML($data);
            case 'Executive':
                return $this->generateExecutiveHTML($data);
            default:
                return '<div class="section"><p>Données du rapport non disponibles.</p></div>';
        }
    }
    
    private function generateRiskAssessmentHTML($data) {
        $html = '<div class="section">
                    <div class="section-title">Résumé des Risques</div>
                    <div class="summary-box">
                        <strong>Total des risques:</strong> ' . $data['summary']['total_risks'] . '<br>
                        <strong>Risques élevés:</strong> ' . $data['summary']['high_risks'] . '<br>
                        <strong>Risques moyens:</strong> ' . $data['summary']['medium_risks'] . '<br>
                        <strong>Risques faibles:</strong> ' . $data['summary']['low_risks'] . '
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Détail des Risques</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom du Risque</th>
                                <th>Entité</th>
                                <th>Activité</th>
                                <th>Criticité Brute</th>
                                <th>Impact Financier</th>
                                <th>Fréquence</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        foreach ($data['risks'] as $risk) {
            $rowClass = '';
            if ($risk['brutCriticality'] >= 15) $rowClass = 'risk-high';
            elseif ($risk['brutCriticality'] >= 10) $rowClass = 'risk-medium';
            else $rowClass = 'risk-low';
            
            $html .= '<tr class="' . $rowClass . '">
                        <td>' . htmlspecialchars($risk['name']) . '</td>
                        <td>' . htmlspecialchars($risk['entityName'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($risk['activityName'] ?? 'N/A') . '</td>
                        <td>' . $risk['brutCriticality'] . '</td>
                        <td>' . $risk['financialImpact'] . '</td>
                        <td>' . $risk['frequency'] . '</td>
                      </tr>';
        }
        
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    private function generateComplianceHTML($data) {
        $html = '<div class="section">
                    <div class="section-title">Résumé des Contrôles</div>
                    <div class="summary-box">
                        <strong>Total des contrôles:</strong> ' . $data['summary']['total_controls'] . '<br>
                        <strong>Contrôles efficaces:</strong> ' . $data['summary']['effective_controls'] . '<br>
                        <strong>Nécessitent amélioration:</strong> ' . $data['summary']['needs_improvement'] . '
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Détail des Contrôles</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom du Contrôle</th>
                                <th>Risque Associé</th>
                                <th>Évaluation</th>
                                <th>Contrôle Proposé</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        foreach ($data['controls'] as $control) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($control['name'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($control['riskName'] ?? 'N/A') . '</td>
                        <td>' . ($control['evaluation'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($control['proposedControl'] ?? 'N/A') . '</td>
                      </tr>';
        }
        
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    private function generateFinancialHTML($data) {
        $html = '<div class="section">
                    <div class="section-title">Résumé Financier</div>
                    <div class="summary-box">
                        <strong>Perte attendue totale:</strong> ' . number_format($data['summary']['total_expected_loss'], 2) . ' €<br>
                        <strong>Risques à fort impact:</strong> ' . $data['summary']['high_impact_risks'] . '<br>
                        <strong>Total des risques avec impact financier:</strong> ' . $data['summary']['total_risks_with_financial_impact'] . '
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Risques Financiers</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom du Risque</th>
                                <th>Entité</th>
                                <th>Impact Financier</th>
                                <th>Fréquence</th>
                                <th>Perte Attendue</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        foreach ($data['financial_risks'] as $risk) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($risk['name']) . '</td>
                        <td>' . htmlspecialchars($risk['entityName'] ?? 'N/A') . '</td>
                        <td>' . $risk['financialImpact'] . '</td>
                        <td>' . $risk['frequency'] . '%</td>
                        <td>' . number_format($risk['expectedLoss'], 2) . ' €</td>
                      </tr>';
        }
        
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    private function generateSecurityHTML($data) {
        $html = '<div class="section">
                    <div class="section-title">Résumé Sécurité</div>
                    <div class="summary-box">
                        <strong>Total des risques de sécurité:</strong> ' . $data['summary']['total_security_risks'] . '<br>
                        <strong>Risques critiques de sécurité:</strong> ' . $data['summary']['critical_security_risks'] . '
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Risques de Sécurité</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom du Risque</th>
                                <th>Entité</th>
                                <th>Activité</th>
                                <th>Criticité</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        foreach ($data['security_risks'] as $risk) {
            $rowClass = $risk['brutCriticality'] >= 20 ? 'risk-high' : ($risk['brutCriticality'] >= 15 ? 'risk-medium' : 'risk-low');
            
            $html .= '<tr class="' . $rowClass . '">
                        <td>' . htmlspecialchars($risk['name']) . '</td>
                        <td>' . htmlspecialchars($risk['entityName'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($risk['activityName'] ?? 'N/A') . '</td>
                        <td>' . $risk['brutCriticality'] . '</td>
                        <td>' . htmlspecialchars(substr($risk['description'], 0, 100)) . '...</td>
                      </tr>';
        }
        
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    private function generateExecutiveHTML($data) {
        $html = '<div class="section">
                    <div class="section-title">Résumé Exécutif</div>
                    <div class="summary-box">
                        <strong>Total des risques:</strong> ' . $data['summary']['total_risks'] . '<br>
                        <strong>Criticité moyenne:</strong> ' . number_format($data['summary']['avg_criticality'], 2) . '<br>
                        <strong>Risques élevés:</strong> ' . $data['summary']['high_risks'] . '<br>
                        <strong>Risques moyens:</strong> ' . $data['summary']['medium_risks'] . '<br>
                        <strong>Risques faibles:</strong> ' . $data['summary']['low_risks'] . '
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Top 10 des Risques</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Nom du Risque</th>
                                <th>Entité</th>
                                <th>Criticité</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        $rank = 1;
        foreach ($data['top_risks'] as $risk) {
            $rowClass = $risk['brutCriticality'] >= 15 ? 'risk-high' : ($risk['brutCriticality'] >= 10 ? 'risk-medium' : 'risk-low');
            
            $html .= '<tr class="' . $rowClass . '">
                        <td>' . $rank++ . '</td>
                        <td>' . htmlspecialchars($risk['name']) . '</td>
                        <td>' . htmlspecialchars($risk['entityName'] ?? 'N/A') . '</td>
                        <td>' . $risk['brutCriticality'] . '</td>
                      </tr>';
        }
        
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    private function addDataToExcel($sheet, $data, $type) {
        $row = 6; // Start after header info
        
        switch ($type) {
            case 'Risk Assessment':
                $sheet->setCellValue('A' . $row, 'Nom du Risque');
                $sheet->setCellValue('B' . $row, 'Entité');
                $sheet->setCellValue('C' . $row, 'Criticité');
                $sheet->setCellValue('D' . $row, 'Impact Financier');
                $row++;
                
                foreach ($data['risks'] as $risk) {
                    $sheet->setCellValue('A' . $row, $risk['name']);
                    $sheet->setCellValue('B' . $row, $risk['entityName'] ?? 'N/A');
                    $sheet->setCellValue('C' . $row, $risk['brutCriticality']);
                    $sheet->setCellValue('D' . $row, $risk['financialImpact']);
                    $row++;
                }
                break;
                
            // Add other cases for different report types
        }
    }
    
    private function addDataToWord($section, $data, $type) {
        switch ($type) {
            case 'Risk Assessment':
                $section->addTitle('Résumé des Risques', 2);
                $section->addText('Total des risques: ' . $data['summary']['total_risks']);
                $section->addText('Risques élevés: ' . $data['summary']['high_risks']);
                $section->addTextBreak(1);
                
                $section->addTitle('Détail des Risques', 2);
                foreach ($data['risks'] as $risk) {
                    $section->addText('• ' . $risk['name'] . ' (Criticité: ' . $risk['brutCriticality'] . ')');
                }
                break;
                
            // Add other cases for different report types
        }
    }
    
    private function sanitizeFilename($filename) {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
    }
}

// Handle the download request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $reportId = intval($_GET['id']);
    $format = $_GET['format'] ?? 'pdf';
    
    try {
        $generator = new ReportGenerator();
        $generator->downloadReport($reportId, $format);
    } catch (Exception $e) {
        error_log("Download error: " . $e->getMessage());
        header('Location: reports.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: reports.php?error=invalid_request');
    exit;
}
?>

