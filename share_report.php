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
        
        // Get report details for sharing
        $stmt = $conn->prepare("SELECT * FROM Report WHERE id = ?");
        $stmt->execute([$reportId]);
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($report) {
            // Generate a temporary sharing link
            $shareToken = bin2hex(random_bytes(16));
            $expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
            
            // In a real application, you would save this token to a share_links table
            // For now, we'll just show the sharing interface
            header('Content-Type: text/html; charset=utf-8');
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Share Report - <?php echo htmlspecialchars($report['name']); ?></title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        margin: 0;
                        padding: 20px;
                        background: #f8fafc;
                    }
                    .share-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background: white;
                        padding: 30px;
                        border-radius: 10px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    .share-title {
                        font-size: 24px;
                        color: #1e293b;
                        margin: 0 0 20px 0;
                    }
                    .share-link {
                        padding: 15px;
                        background: #f1f5f9;
                        border-radius: 5px;
                        margin: 20px 0;
                        word-break: break-all;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        background: #2563eb;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        margin: 5px;
                        cursor: pointer;
                    }
                    .button:hover {
                        background: #1d4ed8;
                    }
                    .back-button {
                        background: #64748b;
                    }
                    .back-button:hover {
                        background: #475569;
                    }
                </style>
            </head>
            <body>
                <div class="share-container">
                    <h1 class="share-title">Share Report: <?php echo htmlspecialchars($report['name']); ?></h1>
                    
                    <p>Share this report with others:</p>
                    
                    <div class="share-link" id="shareLink">
                        <?php 
                        $shareUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . 
                                   dirname($_SERVER['PHP_SELF']) . '/view_report.php?id=' . $report['id'] . '&token=' . $shareToken;
                        echo htmlspecialchars($shareUrl);
                        ?>
                    </div>
                    
                    <p>This link will expire in 7 days.</p>
                    
                    <button class="button" onclick="copyLink()">
                        <i class="fas fa-copy"></i> Copy Link
                    </button>
                    
                    <a href="reports.php" class="button back-button">
                        <i class="fas fa-arrow-left"></i> Back to Reports
                    </a>
                </div>
                
                <script>
                function copyLink() {
                    const linkText = document.getElementById('shareLink').textContent;
                    navigator.clipboard.writeText(linkText.trim()).then(() => {
                        alert('Link copied to clipboard!');
                    }).catch(err => {
                        console.error('Failed to copy link:', err);
                        alert('Failed to copy link. Please try selecting and copying manually.');
                    });
                }
                </script>
            </body>
            </html>
            <?php
            exit;
        }
    } catch (PDOException $e) {
        error_log("Share error: " . $e->getMessage());
    }
}

// If we get here, something went wrong
header('Location: reports.php?error=share_failed');
exit;
