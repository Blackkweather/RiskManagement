<?php
// client_dashboard.php
// Dashboard for client users to view and download their reports
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Client') {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';
// Fetch client reports from the database (replace with your actual query)
$userId = $_SESSION['user']['id'];
$reports = [];
try {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare('SELECT id, name, created_at FROM report WHERE client_id = :client_id');
    $stmt->execute(['client_id' => $userId]);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Could not load reports.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['firstName']); ?>!</h1>
    <h2>Your Reports</h2>
    <?php if (!empty($reports)): ?>
        <table>
            <thead>
                <tr><th>Report Name</th><th>Date</th><th>Download</th></tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['name']); ?></td>
                        <td><?php echo htmlspecialchars($report['created_at']); ?></td>
                        <td><a href="download_report.php?id=<?php echo $report['id']; ?>">Download</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reports available.</p>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</body>
</html>
