<?php
// risk_manager_dashboard.php
// Dashboard for risk managers to manage reports and project risks
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Risk Manager') {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';
// Fetch all reports and projects (replace with your actual query)
$reports = [];
$projects = [];
try {
    $db = (new Database())->getConnection();
    $stmt = $db->query('SELECT id, name, created_at FROM report');
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt2 = $db->query('SELECT id, name FROM project');
    $projects = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Could not load data.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Risk Manager Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['firstName']); ?>!</h1>
    <h2>All Reports</h2>
    <?php if (!empty($reports)): ?>
        <table>
            <thead>
                <tr><th>Report Name</th><th>Date</th><th>Edit</th></tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['name']); ?></td>
                        <td><?php echo htmlspecialchars($report['created_at']); ?></td>
                        <td><a href="edit_report.php?id=<?php echo $report['id']; ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reports available.</p>
    <?php endif; ?>
    <h2>Projects & Risks</h2>
    <?php if (!empty($projects)): ?>
        <ul>
            <?php foreach ($projects as $project): ?>
                <li>
                    <?php echo htmlspecialchars($project['name']); ?>
                    <a href="manage_risks.php?project_id=<?php echo $project['id']; ?>">Manage Risks</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No projects found.</p>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</body>
</html>
