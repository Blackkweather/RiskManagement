<?php
session_start();
require_once 'config/database.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $reportId = intval($_GET['id']);
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Get report details
        $stmt = $conn->prepare("SELECT * FROM Report WHERE id = ? AND status = 'Published'");
        $stmt->execute([$reportId]);
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($report) {
            // Record the download
            $stmt = $conn->prepare("INSERT INTO ReportDownload (reportId, downloadedBy) VALUES (?, ?)");
            $stmt->execute([$reportId, $_SESSION['user_name'] ?? 'Anonymous']);
            
            // Update download count
            $stmt = $conn->prepare("UPDATE Report SET download_count = download_count + 1 WHERE id = ?");
            $stmt->execute([$reportId]);
            
            // For now, we'll just send a PDF with some content
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $report['name'] . '.pdf"');
            header('Cache-Control: must-revalidate');
            
            // In a real application, you would:
            // 1. Either serve an existing PDF file from storage
            // 2. Or generate the PDF dynamically based on the report data
            // For this demo, we'll just create a simple text file
            $content = "Report: " . $report['name'] . "\n";
            $content .= "Generated on: " . date('Y-m-d H:i:s') . "\n";
            $content .= "Type: " . $report['type'] . "\n";
            $content .= "Description: " . $report['description'] . "\n";
            
            echo $content;
            exit;
        }
    } catch (PDOException $e) {
        // Log error and redirect with error message
        error_log("Download error: " . $e->getMessage());
    }
}

// If we get here, something went wrong
header('Location: reports.php?error=download_failed');
exit;
